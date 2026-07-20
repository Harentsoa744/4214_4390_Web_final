<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Préfixes Téléphoniques</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ajouter un préfixe</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('operator/prefixes') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Préfixe (ex: 034)</label>
                        <input type="text" name="prefix" class="form-control" required pattern="[0-9]{3,5}">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" checked>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Préfixe</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($prefixes as $prefix): ?>
                            <tr>
                                <td><?= $prefix['id'] ?></td>
                                <td><strong><?= htmlspecialchars($prefix['prefix']) ?></strong></td>
                                <td>
                                    <?php if($prefix['is_active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <form action="<?= site_url('operator/prefixes/toggle/'.$prefix['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="<?= $prefix['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                    <form action="<?= site_url('operator/prefixes/delete/'.$prefix['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce préfixe ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($prefixes)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun préfixe trouvé.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
