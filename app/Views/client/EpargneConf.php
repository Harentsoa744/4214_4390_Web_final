<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <i class="bi bi-percent fs-2 me-3" style="color: #0C4650;"></i>
        <h1 class="h2 mb-0">Epargne Inter-Utilisateur</h1>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">Configuration de la Commission</h5>
            </div>
            <div class="card-body">
                <!-- <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i> Ce pourcentage sera appliqué à tous les transferts sortants vers les <strong>autres opérateurs</strong>.
                </div> -->
                <form action="<?= site_url('clients/epargne') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pourcentage d' epargne (%)</label>
                        <div class="input-group input-group-lg">
                            <input type="number" step="0.01" name="commission_percentage" class="form-control text-center fw-bold" required value="<?= htmlspecialchars($commission_percentage) ?>" placeholder="Ex: 5">
                            <span class="input-group-text bg-light fw-bold">%</span>
                        </div>
                    </div>
                    <div class="mb-4 form-check form-switch form-switch-lg">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" <?= $is_active ? 'checked' : '' ?>>
                        <label class="form-check-label ms-2 fw-bold" for="isActive">Activer la commission inter-opérateurs</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
