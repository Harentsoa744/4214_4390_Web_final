<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <i class="bi bi-bank fs-2 me-3" style="color: #0C4650;"></i>
        <h1 class="h2 mb-0">Situation des reversements</h1>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i> Cette page permet de visualiser les montants dus à chaque opérateur externe. 
    Les reversements sont effectués de manière simulée (aucun transfert bancaire réel n'est exécuté).
</div>

<div class="row g-4">
    <?php if(empty($settlements)): ?>
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Aucun reversement à afficher.</h5>
        </div>
    <?php else: ?>
        <?php foreach($settlements as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-<?= $s['status'] == 'SETTLED' ? 'success' : 'warning' ?>">
                    <div class="card-header bg-<?= $s['status'] == 'SETTLED' ? 'success' : 'warning' ?> bg-opacity-10 py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <?= htmlspecialchars($operatorNames[$s['destination_operator_id']] ?? 'Opérateur Inconnu') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Période:</span>
                            <span>Depuis <?= date('d/m/Y', strtotime($s['period_start'])) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Montant transféré:</span>
                            <span class="fw-semibold"><?= number_format($s['total_transfer_amount'], 2, ',', ' ') ?> Ar</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Commissions perçues (Opérateur principal):</span>
                            <span class="fw-semibold text-info"><?= number_format($s['total_commission'], 2, ',', ' ') ?> Ar</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-6">Montant à reverser:</span>
                            <span class="fw-bold fs-5 text-dark"><?= number_format($s['amount_to_settle'], 2, ',', ' ') ?> Ar</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-<?= $s['status'] == 'SETTLED' ? 'success' : 'warning text-dark' ?> fs-6">
                                <?= $s['status'] == 'SETTLED' ? 'Envoyé' : 'À envoyer' ?>
                            </span>
                            
                            <?php if($s['status'] != 'SETTLED' && $s['amount_to_settle'] > 0): ?>
                                <form action="<?= site_url('operator/settlements/markAsSent/'.$s['id']) ?>" method="post" onsubmit="return confirm('Confirmez-vous que ce reversement a bien été effectué ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check-circle"></i> Marquer comme envoyé
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($s['status'] == 'SETTLED'): ?>
                    <div class="card-footer bg-light">
                        <small class="text-muted">Réf: <?= htmlspecialchars($s['reference']) ?> (le <?= date('d/m/Y H:i', strtotime($s['settled_at'])) ?>)</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
