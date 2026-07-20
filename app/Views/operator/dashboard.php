<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <i class="bi bi-graph-up-arrow fs-2 me-3" style="color: #0C4650;"></i>
        <h1 class="h2 mb-0">Tableau de bord des Revenus</h1>
    </div>
</div>

<form method="get" class="row g-3 mb-4 bg-light p-3 rounded">
    <div class="col-md-3">
        <label class="form-label">Date début</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Date fin</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Type d'opération</label>
        <select name="type_code" class="form-select">
            <option value="">Tous les types</option>
            <?php foreach($operationTypes as $type): ?>
                <option value="<?= $type['code'] ?>" <?= ($filters['type_code'] ?? '') == $type['code'] ? 'selected' : '' ?>><?= htmlspecialchars($type['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filtrer</button>
    </div>
</form>

<ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="internal-tab" data-bs-toggle="tab" data-bs-target="#internal" type="button" role="tab" aria-controls="internal" aria-selected="true">
            <i class="bi bi-house-fill me-1"></i> Opérateur Principal (Interne)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button" role="tab" aria-controls="external" aria-selected="false">
            <i class="bi bi-globe me-1"></i> Autres Opérateurs (Externe)
        </button>
    </li>
</ul>

<div class="tab-content" id="dashboardTabsContent">
    <!-- ONGLET INTERNE -->
    <div class="tab-pane fade show active" id="internal" role="tabpanel" aria-labelledby="internal-tab">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100" style="background-color: #0C4650; color: white; border: 2px solid black;">
                    <div class="card-body">
                        <h6 class="card-title">Revenus Internes Totaux</h6>
                        <h3 class="card-text fw-bold text-white"><?= number_format($internalStats['total_revenue'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="border: 2px solid #0C4650;">
                    <div class="card-body">
                        <h6 class="card-title">Revenus Transferts Internes</h6>
                        <h3 class="card-text fw-bold" style="color: #0C4650;"><?= number_format($internalStats['fee_transfers'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="border: 2px solid #0C4650;">
                    <div class="card-body">
                        <h6 class="card-title">Revenus Retraits</h6>
                        <h3 class="card-text fw-bold" style="color: #0C4650;"><?= number_format($internalStats['fee_withdrawals'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Volume des Transactions Internes</h6>
                        <h2 class="card-text fw-bold text-dark"><?= number_format($internalStats['volume_internal'] ?? 0, 2, ',', ' ') ?> Ar</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Nombre de Transactions Internes</h6>
                        <h2 class="card-text fw-bold text-dark"><?= $internalStats['count_internal'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ONGLET EXTERNE -->
    <div class="tab-pane fade" id="external" role="tabpanel" aria-labelledby="external-tab">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100" style="background-color: #898B8F; color: white; border: 2px solid black;">
                    <div class="card-body">
                        <h6 class="card-title">Revenus sur Transferts Externes</h6>
                        <p class="small mb-1">(Frais de transfert gagnés par nous)</p>
                        <h3 class="card-text fw-bold text-white"><?= number_format($externalStats['total_revenue_for_main'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="background-color: #E6FF2A; color: black; border: 2px solid black;">
                    <div class="card-body">
                        <h6 class="card-title">Commissions Inter-Opérateurs</h6>
                        <p class="small mb-1">(Dues aux opérateurs externes)</p>
                        <h3 class="card-text fw-bold"><?= number_format($externalStats['total_commissions_generated'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="border: 2px solid #898B8F;">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Volume des Transferts Externes</h6>
                        <h3 class="card-text fw-bold text-dark mt-3"><?= number_format($externalStats['volume_external'] ?? 0, 2, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">Nombre de Transferts Inter-Opérateurs</h6>
                        <h2 class="card-text fw-bold text-dark"><?= $externalStats['count_external'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm" style="border: 1px solid #FFC107;">
                    <div class="card-header" style="background-color: #FFC107; color: black;">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-bank"></i> Montants à envoyer par Opérateur</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if (empty($commissionsByOperator)): ?>
                                <li class="list-group-item text-muted text-center py-4">Aucun montant à envoyer.</li>
                            <?php else: ?>
                                <?php foreach($commissionsByOperator as $opComm): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5"><?= htmlspecialchars($opComm['operator_name']) ?></span>
                                    <span class="badge rounded-pill fs-6" style="background-color: #FFC107; color: black; border: 1px solid black;">
                                        <?= number_format($opComm['total_commission'], 2, ',', ' ') ?> Ar
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="border-bottom pb-2 mb-3 d-flex justify-content-between align-items-center">
            <h3 class="h4 mb-0"><i class="bi bi-wallet2 text-success me-2"></i> Situation détaillée des gains (Frais & Commissions)</h3>
            
            <form method="get" class="d-flex" id="operatorFilterForm">
                <input type="hidden" name="start_date" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
                <input type="hidden" name="end_date" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
                <input type="hidden" name="type_code" value="<?= htmlspecialchars($filters['type_code'] ?? '') ?>">
                <select name="dest_operator" class="form-select form-select-sm" onchange="document.getElementById('operatorFilterForm').submit()">
                    <option value="">Tous les opérateurs (Interne + Externe)</option>
                    <?php foreach($operators as $op): ?>
                        <option value="<?= $op['id'] ?>" <?= ($filters['dest_operator'] ?? '') == $op['id'] ? 'selected' : '' ?>><?= htmlspecialchars($op['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date & Heure</th>
                                <th>Référence</th>
                                <th>Type</th>
                                <th>Opérateur Dest.</th>
                                <th>Expéditeur</th>
                                <th>Bénéficiaire</th>
                                <th class="text-end">Montant (Ar)</th>
                                <th class="text-end">Frais (Notre gain)</th>
                                <th class="text-end text-warning">Commission (Due)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($feeTransactions)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                        Aucun gain généré sur la période sélectionnée.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($feeTransactions as $t): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                        <td><code><?= htmlspecialchars($t['transaction_reference']) ?></code></td>
                                        <td>
                                            <?php if($t['transfer_type'] == 'INTER_OPERATOR'): ?>
                                                <span class="badge bg-warning text-dark border border-warning px-2 py-1">Externe</span>
                                            <?php elseif($t['transfer_type'] == 'INTERNAL' && $t['operation_code'] == 'TRANSFER'): ?>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">Interne</span>
                                            <?php elseif($t['operation_code'] == 'WITHDRAWAL'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Retrait</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $t['dest_operator_name'] ? htmlspecialchars($t['dest_operator_name']) : 'OPÉRATEUR PRINCIPAL' ?>
                                        </td>
                                        <td><?= htmlspecialchars($t['sender_phone'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($t['receiver_phone'] ?? 'N/A') ?></td>
                                        <td class="text-end fw-semibold">
                                            <?= number_format($t['amount'], 2, ',', ' ') ?>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            + <?= number_format($t['fee_amount'], 2, ',', ' ') ?>
                                        </td>
                                        <td class="text-end fw-bold text-warning">
                                            <?= $t['commission_amount'] > 0 ? '+ ' . number_format($t['commission_amount'], 2, ',', ' ') : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if(!empty($feeTransactions)): ?>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="7" class="text-end">Totaux de la sélection :</td>
                                    <td class="text-end text-success fs-6">
                                        + <?= number_format(array_sum(array_column($feeTransactions, 'fee_amount')), 2, ',', ' ') ?> Ar
                                    </td>
                                    <td class="text-end text-warning fs-6">
                                        + <?= number_format(array_sum(array_column($feeTransactions, 'commission_amount')), 2, ',', ' ') ?> Ar
                                    </td>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
