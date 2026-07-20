<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card mt-4 p-0 overflow-hidden">
    <div class="bg-light p-3 d-flex justify-content-between align-items-center border-bottom">
        <div class="d-flex align-items-center">
            <a href="<?= site_url('client/dashboard') ?>" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <h5 class="mb-0">Historique</h5>
        </div>
        <div>
            <form action="<?= site_url('client/history') ?>" method="get" class="d-flex">
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Tous les types</option>
                    <option value="DEPOSIT" <?= isset($_GET['type']) && $_GET['type'] == 'DEPOSIT' ? 'selected' : '' ?>>Dépôts</option>
                    <option value="WITHDRAWAL" <?= isset($_GET['type']) && $_GET['type'] == 'WITHDRAWAL' ? 'selected' : '' ?>>Retraits</option>
                    <option value="TRANSFER" <?= isset($_GET['type']) && $_GET['type'] == 'TRANSFER' ? 'selected' : '' ?>>Transferts</option>
                </select>
            </form>
        </div>
    </div>

    <ul class="list-group list-group-flush">
        <?php if(empty($transactions)): ?>
            <li class="list-group-item p-4 text-center text-muted">Aucune transaction trouvée.</li>
        <?php else: ?>
            <?php foreach($transactions as $t): ?>
                <?php 
                    $isSender = ($t['sender_client_id'] == $clientId);
                    $isReceiver = ($t['receiver_client_id'] == $clientId);
                    
                    $sign = '+';
                    $color = 'text-success';
                    $icon = 'bi-arrow-down-circle-fill text-success';
                    $desc = $t['operation_name'];

                    if ($t['operation_code'] == 'DEPOSIT') {
                        $desc = 'Dépôt';
                    } elseif ($t['operation_code'] == 'WITHDRAWAL') {
                        $sign = '-';
                        $color = 'text-danger';
                        $icon = 'bi-arrow-up-circle-fill text-danger';
                        $desc = 'Retrait';
                    } elseif ($t['operation_code'] == 'TRANSFER') {
                        if ($isSender) {
                            $sign = '-';
                            $color = 'text-danger';
                            $icon = 'bi-arrow-up-right-circle-fill text-danger';
                            $desc = 'Transfert vers ' . $t['receiver_phone'];
                        } else {
                            $icon = 'bi-arrow-down-left-circle-fill text-success';
                            $desc = 'Transfert de ' . $t['sender_phone'];
                        }
                    }
                ?>
                <li class="list-group-item p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi <?= $icon ?> fs-2 me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= $desc ?></h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?> • Réf: <?= $t['transaction_reference'] ?></small>
                                <?php if($t['fee_amount'] > 0 && $isSender): ?>
                                    <br><small class="text-muted">Frais: <?= number_format($t['fee_amount'], 2, ',', ' ') ?> Ar</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 fw-bold <?= $color ?>">
                                <?= $sign ?> <?= number_format($t['amount'], 2, ',', ' ') ?> Ar
                            </h6>
                            <small class="text-muted">Solde: <?= number_format($isSender ? $t['balance_after'] : ($t['operation_code'] == 'DEPOSIT' ? $t['balance_after'] : $t['balance_after']), 2, ',', ' ') ?> Ar</small>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    
    <div class="p-3">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>
