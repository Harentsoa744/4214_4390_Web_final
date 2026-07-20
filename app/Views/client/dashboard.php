<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4 mt-3">
    <div class="col-12">
        <div class="card balance-card p-4 text-center">
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
                <i class="bi bi-box-arrow-in-down fs-1 text-success mb-2"></i>
                <h6 class="text-dark">Dépôt</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/withdraw') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-box-arrow-up fs-1 text-danger mb-2"></i>
                <h6 class="text-dark">Retrait</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/transfer') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-send fs-1 text-primary mb-2"></i>
                <h6 class="text-dark">Transfert</h6>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="<?= site_url('client/history') ?>" class="text-decoration-none">
            <div class="card text-center p-3 h-100 action-card">
                <i class="bi bi-clock-history fs-1 text-secondary mb-2"></i>
                <h6 class="text-dark">Historique</h6>
            </div>
        </a>
    </div>
</div>

<style>
    .action-card { transition: transform 0.2s; }
    .action-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
</style>
<?= $this->endSection() ?>
