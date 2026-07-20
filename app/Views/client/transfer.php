<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card mt-4 p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="<?= site_url('client/dashboard') ?>" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h4 class="mb-0">Transférer de l'argent</h4>
    </div>

    <div class="alert alert-info">
        Solde disponible : <strong><?= number_format($client['balance'], 2, ',', ' ') ?> Ar</strong>
    </div>

    <form action="<?= site_url('client/transfer') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label class="form-label text-muted">Numéro du destinataire</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="receiver_phone_number" class="form-control fw-bold" value="<?= old('receiver_phone_number') ?>" placeholder="ex: 0340000002" required pattern="^[0-9]{10}$">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label text-muted">Montant à transférer (Ar)</label>
            <div class="input-group input-group-lg">
                <input type="number" name="amount" class="form-control fw-bold" value="<?= old('amount') ?>" placeholder="0" min="1" required>
                <span class="input-group-text">Ar</span>
            </div>
            <div class="form-text">Les frais de transfert seront déduits de votre solde.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">Valider le transfert</button>
    </form>
</div>
<?= $this->endSection() ?>
