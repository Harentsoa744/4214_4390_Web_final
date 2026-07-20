<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Types d'opérations</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Nouveau Type</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('operator/operation-types') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Code (unique, ex: PAIEMENT)</label>
                        <input type="text" name="code" class="form-control text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom (ex: Paiement facture)</label>
                        <input type="text" name="name" class="form-control" required>
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
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($types as $type): ?>
                            <tr>
                                <td><span class="badge bg-dark"><?= htmlspecialchars($type['code']) ?></span></td>
                                <td><?= htmlspecialchars($type['name']) ?></td>
                                <td>
                                    <?php if($type['is_active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <form action="<?= site_url('operator/operation-types/toggle/'.$type['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="<?= $type['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
