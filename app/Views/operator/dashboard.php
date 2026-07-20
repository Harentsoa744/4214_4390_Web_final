<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tableau de bord des Revenus</h1>
</div>

<form method="get" class="row g-3 mb-4 bg-light p-3 rounded">
    <div class="col-md-3">
        <label class="form-label">Date début</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Date fin</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Type d'opération</label>
        <select name="type_code" class="form-select">
            <option value="">Tous les types</option>
            <?php foreach($operationTypes as $type): ?>
                <option value="<?= $type['code'] ?>" <?= ($filters['type_code'] ?? '') == $type['code'] ? 'selected' : '' ?>><?= $type['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filtrer</button>
    </div>
</form>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h6 class="card-title">Revenus Totaux (Frais)</h6>
                <h3 class="card-text fw-bold"><?= number_format($stats['total_fees'] ?? 0, 2, ',', ' ') ?> Ar</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h6 class="card-title">Revenus Transferts</h6>
                <h3 class="card-text fw-bold"><?= number_format($stats['fee_transfers'] ?? 0, 2, ',', ' ') ?> Ar</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Revenus Retraits</h6>
                <h3 class="card-text fw-bold"><?= number_format($stats['fee_withdrawals'] ?? 0, 2, ',', ' ') ?> Ar</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-secondary h-100">
            <div class="card-body">
                <h6 class="card-title">Volume des Transactions</h6>
                <h3 class="card-text fw-bold"><?= number_format($stats['total_volume'] ?? 0, 2, ',', ' ') ?> Ar</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm text-center py-3">
            <h1 class="display-5 text-primary"><?= $stats['total_transactions'] ?? 0 ?></h1>
            <span class="text-muted">Total Opérations</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center py-3">
            <h1 class="display-5 text-success"><?= $stats['count_deposits'] ?? 0 ?></h1>
            <span class="text-muted">Dépôts</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center py-3">
            <h1 class="display-5 text-danger"><?= $stats['count_withdrawals'] ?? 0 ?></h1>
            <span class="text-muted">Retraits</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center py-3">
            <h1 class="display-5 text-warning"><?= $stats['count_transfers'] ?? 0 ?></h1>
            <span class="text-muted">Transferts</span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
