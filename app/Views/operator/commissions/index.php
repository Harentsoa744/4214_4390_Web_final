<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion des Commissions Inter-Opérateurs</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Configurer une Commission</div>
            <div class="card-body">
                <form action="<?= site_url('operator/commissions') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label>Opérateur Source</label>
                        <select name="source_operator_id" class="form-select" required>
                            <?php if($mainOperator): ?>
                                <option value="<?= $mainOperator['id'] ?>"><?= htmlspecialchars($mainOperator['name']) ?> (Principal)</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Opérateur Destinataire</label>
                        <select name="destination_operator_id" class="form-select" required>
                            <?php foreach($externalOperators as $op): ?>
                                <option value="<?= $op['id'] ?>"><?= htmlspecialchars($op['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pourcentage de commission (%)</label>
                        <input type="number" step="0.01" name="commission_percentage" class="form-control" required placeholder="Ex: 2.5">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Source</th>
                            <th>Destinataire</th>
                            <th>Pourcentage (%)</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $opNames = [];
                        if ($mainOperator) $opNames[$mainOperator['id']] = $mainOperator['name'];
                        foreach($externalOperators as $op) $opNames[$op['id']] = $op['name'];
                        
                        foreach($commissions as $comm): 
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($opNames[$comm['source_operator_id']] ?? 'Inconnu') ?></td>
                                <td><?= htmlspecialchars($opNames[$comm['destination_operator_id']] ?? 'Inconnu') ?></td>
                                <td><strong><?= number_format($comm['commission_percentage'], 2) ?> %</strong></td>
                                <td>
                                    <?php if($comm['is_active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
