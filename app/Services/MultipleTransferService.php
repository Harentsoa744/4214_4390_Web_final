<?php

namespace App\Services;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\TransferBatchModel;
use App\Models\OperationTypeModel;

class MultipleTransferService
{
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;
    protected TransferBatchModel $batchModel;
    protected OperationTypeModel $operationTypeModel;
    protected TransferCostCalculatorService $costCalculator;
    protected $db;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
        $this->batchModel = new TransferBatchModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->costCalculator = new TransferCostCalculatorService();
        $this->db = \Config\Database::connect();
    }

    /**
     * Exécute un envoi multiple de manière atomique
     * 
     * @param int $senderId ID de l'expéditeur
     * @param float $totalAmount Montant global à diviser
     * @param array $receiverPhones Tableau des numéros de destinataires
     * @param bool $includeWithdrawalFee Inclure ou non les frais de retrait
     * @return array Résultat de l'opération
     */
    public function executeMultipleTransfer(int $senderId, float $totalAmount, array $receiverPhones, bool $includeWithdrawalFee = false): array
    {
        $this->db->transStart();

        try {
            $sender = $this->clientModel->find($senderId);
            if (!$sender) throw new \Exception("Expéditeur introuvable.");

            $senderPhone = $sender['phone_number'];
            $recipientCount = count($receiverPhones);
            
            if ($recipientCount === 0) {
                throw new \Exception("Aucun destinataire spécifié.");
            }

            if ($totalAmount <= 0) {
                throw new \Exception("Le montant total doit être supérieur à zéro.");
            }

            // Vérifier que tous les numéros sont différents et que l'expéditeur n'est pas inclus
            if (count(array_unique($receiverPhones)) !== $recipientCount) {
                throw new \Exception("Les numéros des destinataires doivent être uniques.");
            }
            if (in_array($senderPhone, $receiverPhones)) {
                throw new \Exception("Vous ne pouvez pas vous envoyer de l'argent à vous-même.");
            }

            $amountPerRecipient = $totalAmount / $recipientCount;
            $totalFees = 0.00;
            $totalCommissions = 0.00;
            $globalTotalDebited = 0.00;

            $transactionsData = [];
            $receiversData = [];

            $transferOpType = $this->operationTypeModel->where('code', 'TRANSFER')->first();
            $opTypeId = (int) $transferOpType['id'];

            // Pré-calculs et vérifications pour chaque destinataire
            foreach ($receiverPhones as $receiverPhone) {
                $receiver = $this->clientModel->where('phone_number', $receiverPhone)->first();
                if (!$receiver) {
                    $operatorResolver = new \App\Services\OperatorResolverService();
                    $receiverOp = $operatorResolver->resolveOperator($receiverPhone);
                    if (!$receiverOp) {
                        throw new \Exception("Le numéro du destinataire {$receiverPhone} est invalide (préfixe inconnu).");
                    }
                    $newClientId = $this->clientModel->insert([
                        'phone_number' => $receiverPhone,
                        'balance'      => 0.00,
                        'status'       => 'active'
                    ]);
                    $receiver = $this->clientModel->find($newClientId);
                }

                $costs = $this->costCalculator->calculateCosts($senderPhone, $receiverPhone, $amountPerRecipient, $includeWithdrawalFee);
                
                if ($recipientCount > 1 && $costs['transfer_type'] === 'INTER_OPERATOR') {
                    throw new \Exception("L'envoi multiple est uniquement autorisé vers les numéros de notre opérateur (numéro invalide : {$receiverPhone}).");
                }
                
                $totalFees += $costs['transfer_fee'] + $costs['withdrawal_fee'];
                $totalCommissions += $costs['commission_amount'];
                $globalTotalDebited += $costs['total_debited'];

                $receiversData[] = [
                    'receiver' => $receiver,
                    'costs' => $costs
                ];
            }

            $senderBalanceBefore = (float) $sender['balance'];
            
            // Vérifier le solde de l'expéditeur
            if ($senderBalanceBefore < $globalTotalDebited) {
                throw new \Exception("Solde insuffisant pour couvrir le montant total de l'opération (" . number_format($globalTotalDebited, 2, ',', ' ') . " Ar).");
            }

            // Créer le batch
            $this->batchModel->insert([
                'sender_client_id' => $senderId,
                'total_amount' => $totalAmount,
                'total_fee' => $totalFees,
                'total_commission' => $totalCommissions,
                'include_withdrawal_fee' => $includeWithdrawalFee ? 1 : 0,
                'status' => 'COMPLETED'
            ]);
            $batchId = $this->batchModel->getInsertID();

            $currentSenderBalance = $senderBalanceBefore;

            // Effectuer les transferts
            foreach ($receiversData as $data) {
                $receiver = $data['receiver'];
                $costs = $data['costs'];

                // Débiter l'expéditeur
                $senderBalanceAfter = $currentSenderBalance - $costs['total_debited'];
                
                // Créditer le destinataire
                $receiverBalanceBefore = (float) $receiver['balance'];
                
                // Le bénéficiaire reçoit le montant demandé. 
                // Si inclut les frais de retrait, ils ont été débités à l'expéditeur, 
                // on crédite $amountPerRecipient + $withdrawalFee au destinataire ? 
                // Non, l'énoncé: "Le système calcule automatiquement le coût total nécessaire... 
                // Montant destiné au bénéficiaire: 100 000. Frais de retrait inclus: 800. 
                // Le destinataire doit recevoir 100 000 + 800 = 100 800 pour qu'il puisse retirer 100 000 nets."
                $amountToCredit = $costs['amount'];
                if ($includeWithdrawalFee) {
                    $amountToCredit += $costs['withdrawal_fee'];
                }

                $receiverBalanceAfter = $receiverBalanceBefore + $amountToCredit;

                // MAJ soldes (l'expéditeur est MAJ à la fin globalement, on le track en local)
                $this->clientModel->update($receiver['id'], ['balance' => $receiverBalanceAfter]);
                
                // Enregistrer transaction
                $ref = 'TRF-B' . $batchId . '-' . strtoupper(uniqid());
                $this->transactionModel->insert([
                    'transaction_reference' => $ref,
                    'batch_id' => $batchId,
                    'operation_type_id' => $opTypeId,
                    'sender_client_id' => $senderId,
                    'receiver_client_id' => $receiver['id'],
                    'destination_operator_id' => $costs['destination_operator_id'],
                    'transfer_type' => $costs['transfer_type'],
                    'amount' => $amountToCredit,
                    'fee_amount' => $costs['transfer_fee'] + $costs['withdrawal_fee'],
                    'commission_amount' => $costs['commission_amount'],
                    'total_amount' => $costs['total_debited'],
                    'balance_before' => $currentSenderBalance,
                    'balance_after' => $senderBalanceAfter,
                    'status' => 'completed'
                ]);

                $currentSenderBalance = $senderBalanceAfter;
            }

            // MAJ du solde final de l'expéditeur
            $this->clientModel->update($senderId, ['balance' => $currentSenderBalance]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception("Erreur inattendue lors de l'exécution de l'envoi multiple.");
            }

            return [
                'success' => true, 
                'batch_id' => $batchId, 
                'total_debited' => $globalTotalDebited, 
                'balance_after' => $currentSenderBalance
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
