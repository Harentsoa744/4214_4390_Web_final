<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-lg-10 col-xl-9">
        <div class="card p-0 overflow-hidden">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-6 d-none d-md-block" 
                     style="background: url('/assets/utils/Gemini_Generated_Image_se4s25se4s25se4s.png') center center / cover no-repeat; min-height: 450px;">
                </div>

                <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center">
                    <h2 class="mb-3 text-center">Bienvenue sur Mobile Money</h2>
                    <p class="text-muted text-center mb-4">Connectez-vous ou inscrivez-vous automatiquement avec votre numéro de téléphone.</p>
                    
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
                    
                    <hr class="mt-4">
                    <div class="text-center">
                        <a href="<?= site_url('operator/login') ?>" class="text-secondary text-decoration-none"><small>Espace Opérateur</small></a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
