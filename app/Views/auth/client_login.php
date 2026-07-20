<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card text-center p-4">
            <h2 class="mb-4">Bienvenue sur Mobile Money</h2>
            <p class="text-muted">Connectez-vous ou inscrivez-vous automatiquement avec votre numéro de téléphone.</p>
            
            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone_number" class="form-control" placeholder="Numéro de téléphone (ex: 0340000001)" required pattern="^[0-9]{10}$" title="Veuillez saisir un numéro à 10 chiffres.">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">Accéder à mon compte</button>
            </form>
            
            <hr class="mt-5">
            <a href="<?= site_url('operator/login') ?>" class="text-secondary text-decoration-none"><small>Espace Opérateur</small></a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
