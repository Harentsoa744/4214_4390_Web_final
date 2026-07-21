<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-lg-12 col-xl-12">
        <div class="card p-0 overflow-hidden">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-6 d-none d-md-block"
                    style="background: url('/assets/utils/logo2.png') center center / cover no-repeat; min-height: 450px;">
                </div>

                <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center">
                    <h2 class="mb-3 text-center">Bienvenue sur Mobile Money</h2>
                    <p class="text-muted text-center mb-4">Connectez-vous ou inscrivez-vous automatiquement avec votre
                        numéro de téléphone.</p>

                    <form action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <select name="prefix" class="form-select" style="max-width: 100px; font-size: 0.8rem;" required>
                                    <?php if(isset($prefixes) && is_array($prefixes)): ?>
                                        <?php foreach($prefixes as $p): ?>
                                            <option value="<?= htmlspecialchars($p['prefix']) ?>"><?= htmlspecialchars($p['prefix']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <input type="text" name="phone_number" class="form-control"
                                    placeholder="(ex: 0000001)" required pattern="^[0-9]{7}$"
                                    title="Veuillez saisir les 7 derniers chiffres." style="font-size: 0.8rem;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded">Accéder à mon compte</button>
                    </form>

                    <hr class="mt-4">
                    <div class="text-center">
                        <a href="<?= site_url('operator/login') ?>"
                            class="text-secondary text-decoration-none"><small>Espace Opérateur</small></a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>