<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card mt-4 p-4 shadow-sm border-0">
    <div class="d-flex align-items-center mb-4">
        <a href="<?= site_url('client/dashboard') ?>" class="text-dark me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <div class="me-3">
            <i class="bi bi-send-fill fs-1" style="color: #0C4650;"></i>
        </div>
        <h4 class="mb-0 fw-bold">Transférer de l'argent</h4>
    </div>

    <div class="alert alert-info border-info border-opacity-50">
        Solde disponible : <strong class="fs-5"><?= number_format($client['balance'], 2, ',', ' ') ?> Ar</strong>
    </div>

    <form action="<?= site_url('client/transfer') ?>" method="post" id="transferForm">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label class="form-label text-muted fw-bold">Montant TOTAL à transférer (Ar)</label>
            <div class="input-group input-group-lg">
                <input type="number" id="totalAmount" name="amount" class="form-control fw-bold fs-4" value="<?= old('amount') ?>" placeholder="0" min="1" required>
                <span class="input-group-text bg-light fw-bold text-muted">Ar</span>
            </div>
            <div class="form-text text-primary" id="amountDividerText">
                Ce montant sera divisé équitablement entre tous les destinataires.
            </div>
        </div>

        <div class="card bg-light mb-4 border-0">
            <div class="card-body">
                <div class="form-check form-switch form-switch-lg">
                    <input class="form-check-input" type="checkbox" role="switch" id="includeWithdrawalFee" name="include_withdrawal_fee" value="1">
                    <label class="form-check-label ms-2 fw-bold" for="includeWithdrawalFee">Inclure les frais de retrait dans le montant envoyé</label>
                </div>
                <small class="text-muted d-block mt-2">
                    Si activé, vous paierez les frais de retrait à la place du destinataire. Le destinataire recevra un montant légèrement supérieur pour pouvoir retirer la somme exacte souhaitée sans frais.
                </small>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-end">
            <label class="form-label text-muted fw-bold mb-0">Destinataires</label>
            <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="addRecipientBtn">
                <i class="bi bi-plus-circle"></i> Ajouter un destinataire
            </button>
        </div>

        <div id="recipientsContainer">
            <!-- Premier destinataire par défaut -->
            <div class="input-group input-group-lg mb-3 recipient-row">
                <span class="input-group-text bg-light"><i class="bi bi-person-fill text-muted"></i></span>
                <input type="text" name="receiver_phone_number[]" class="form-control fw-bold" placeholder="Numéro (ex: 0340000002)" required pattern="^[0-9]{10}$">
                <button type="button" class="btn btn-outline-danger remove-btn" disabled><i class="bi bi-trash"></i></button>
            </div>
        </div>
        
        <div class="alert alert-secondary mt-2 mb-4 d-none" id="previewAlert">
            <h6 class="fw-bold mb-2"><i class="bi bi-calculator"></i> Aperçu (Estimation)</h6>
            <div id="previewContent" class="small">
                <!-- Rempli par JS -->
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold fs-5 shadow-sm mt-3" onclick="return confirm('Confirmez-vous ce transfert ?');">
            <i class="bi bi-check-circle me-2"></i> Valider le transfert
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('recipientsContainer');
    const addBtn = document.getElementById('addRecipientBtn');
    const totalAmountInput = document.getElementById('totalAmount');
    const previewAlert = document.getElementById('previewAlert');
    const previewContent = document.getElementById('previewContent');

    function updatePreview() {
        const rows = document.querySelectorAll('.recipient-row');
        const count = rows.length;
        const total = parseFloat(totalAmountInput.value) || 0;
        
        if (count > 0 && total > 0) {
            const perRecipient = (total / count).toFixed(2);
            let html = `<div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <span>Montant par destinataire :</span>
                            <span class="fw-bold">${perRecipient} Ar</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Destinataires :</span>
                            <span>${count}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mt-2 small">
                            <em>Les frais exacts seront calculés à la validation finale selon les opérateurs (Inter-opérateurs génèrent des commissions).</em>
                        </div>`;
            previewContent.innerHTML = html;
            previewAlert.classList.remove('d-none');
        } else {
            previewAlert.classList.add('d-none');
        }
        
        // Mettre à jour l'état des boutons de suppression
        rows.forEach(r => {
            const btn = r.querySelector('.remove-btn');
            if (count === 1) {
                btn.disabled = true;
            } else {
                btn.disabled = false;
            }
        });
    }

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'input-group input-group-lg mb-3 recipient-row';
        row.innerHTML = `
            <span class="input-group-text bg-light"><i class="bi bi-person-fill text-muted"></i></span>
            <input type="text" name="receiver_phone_number[]" class="form-control fw-bold" placeholder="Numéro (ex: 0340000002)" required pattern="^[0-9]{10}$">
            <button type="button" class="btn btn-outline-danger remove-btn"><i class="bi bi-trash"></i></button>
        `;
        
        row.querySelector('.remove-btn').addEventListener('click', function() {
            row.remove();
            updatePreview();
        });
        
        container.appendChild(row);
        updatePreview();
    });

    totalAmountInput.addEventListener('input', updatePreview);
    
    // Initial setup
    updatePreview();
});
</script>
<?= $this->endSection() ?>
