<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion des Opérateurs</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Ajouter un Opérateur</div>
            <div class="card-body">
                <form action="<?= site_url('operator/operators') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: ORANGE">
                    </div>
                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control" required placeholder="Ex: OP_ORANGE">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($operators as $op): ?>
                            <tr>
                                <td><?= $op['id'] ?></td>
                                <td><strong><?= htmlspecialchars($op['name']) ?></strong></td>
                                <td><?= htmlspecialchars($op['code']) ?></td>
                                <td>
                                    <?php if($op['is_main_operator']): ?>
                                        <span class="badge bg-success">Principal</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Externe</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($op['is_active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!$op['is_main_operator']): ?>
                                        <form action="<?= site_url('operator/operators/toggle/'.$op['id']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-<?= $op['is_active'] ? 'danger' : 'success' ?>">
                                                <?= $op['is_active'] ? 'Désactiver' : 'Activer' ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
