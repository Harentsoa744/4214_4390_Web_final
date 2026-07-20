<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card mt-4 p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="<?= site_url('client/dashboard') ?>" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="mb-0">Effectuer un Dépôt</h4>
    </div>

    <form action="<?= site_url('client/deposit') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label class="form-label text-muted">Montant à déposer (Ar)</label>
            <div class="input-group input-group-lg">
                <input type="number" name="amount" class="form-control fw-bold" placeholder="0" min="1" required>
                <span class="input-group-text">Ar</span>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill">Valider le dépôt</button>
    </form>
</div>
<?= $this->endSection() ?>
