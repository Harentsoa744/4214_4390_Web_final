<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4 mt-3">
    <div class="col-12">
        <div class="card balance-card p-4 text-center">
            <div class="mb-3">
                <i class="bi bi-wallet2 fs-2"></i>
            </div>
            <h5>Solde disponible</h5>
            <h1 class="display-4 fw-bold"><?= number_format($client['balance'], 2, ',', ' ') ?> Ar</h1>
            <p class="mb-0 text-white-50">Compte : <?= htmlspecialchars($client['phone_number']) ?></p>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-6">
        <a href="<?= site_url('client/deposit') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-box-arrow-in-down fs-1 mb-2"></i>
                <h6>Dépôt</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/withdraw') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-box-arrow-up fs-1 mb-2"></i>
                <h6>Retrait</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/transfer') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-send fs-1 mb-2"></i>
                <h6>Transfert</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/history') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-clock-history fs-1 mb-2"></i>
                <h6>Historique</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/epargne') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-box-arrow-in-down fs-1 mb-2"></i>
                <h6>Epargne conf</h6>
            </div>
        </a>
    </div>
</div>
<?= $this->endSection() ?>
