<?php

namespace App\Services;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;

class TransactionService
{
    protected FeeCalculatorService $feeCalculator;
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;
    protected OperationTypeModel $operationTypeModel;
    protected $db;

    public function __construct()
    {
        $this->feeCalculator = new FeeCalculatorService();
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->db = \Config\Database::connect();
    }

    private function getOperationTypeId(string $code): int
    {
        $type = $this->operationTypeModel->where('code', $code)->first();
        if (!$type) {
            throw new \Exception("Type d'opération $code introuvable.");
        }
        return (int) $type['id'];
    }

    public function deposit(int $clientId, float $amount): array
    {
        $this->db->transStart();

        try {
            $client = $this->clientModel->find($clientId);
            if (!$client) throw new \Exception("Client introuvable.");

            $operationTypeId = $this->getOperationTypeId('DEPOSIT');
            $fee = $this->feeCalculator->calculateFee($operationTypeId, $amount);
            
            // Pour un dépôt, les frais (s'il y en a) sont généralement déduits du dépôt ou ajoutés. 
            // Ici, selon l'énoncé, "Total = Montant". Si frais > 0, on peut l'ajouter ou déduire.
            // On considère que le client dépose $amount, le système prend $fee, et on crédite $amount - $fee (ou inverse).
            // Mais l'énoncé dit "Dépôt : 100 000 Ar, Frais : X Ar, Solde avant : 50 000, Solde après : 150 000".
            // Donc le dépôt de 100 000 s'ajoute intégralement, les frais pourraient être payés séparément ou être 0. 
            // Pour rester simple: balance += amount.
            
            $totalAmount = $amount; 
            $balanceBefore = (float) $client['balance'];
            $balanceAfter = $balanceBefore + $amount; // Le montant net crédité

            // Update balance
            $this->clientModel->update($clientId, ['balance' => $balanceAfter]);

            // Save transaction
            $ref = 'DEP-' . strtoupper(uniqid());
            $this->transactionModel->insert([
                'transaction_reference' => $ref,
                'operation_type_id'     => $operationTypeId,
                'sender_client_id'      => null, // Pas d'expéditeur, c'est l'opérateur/système
                'receiver_client_id'    => $clientId,
                'amount'                => $amount,
                'fee_amount'            => $fee,
                'total_amount'          => $totalAmount,
                'balance_before'        => $balanceBefore,
                'balance_after'         => $balanceAfter,
                'status'                => 'completed'
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception("Erreur lors de la transaction de dépôt.");
            }

            return ['success' => true, 'reference' => $ref, 'fee' => $fee, 'amount' => $amount, 'balance_after' => $balanceAfter];

        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function withdraw(int $clientId, float $amount): array
    {
        $this->db->transStart();

        try {
            $client = $this->clientModel->find($clientId);
            if (!$client) throw new \Exception("Client introuvable.");

            $operationTypeId = $this->getOperationTypeId('WITHDRAWAL');
            $fee = $this->feeCalculator->calculateFee($operationTypeId, $amount);
            
            $totalAmount = $amount + $fee; // Le client doit payer le retrait + les frais
            $balanceBefore = (float) $client['balance'];

            if ($balanceBefore < $totalAmount) {
                throw new \Exception("Solde insuffisant pour couvrir le montant et les frais de $fee Ar.");
            }

            $balanceAfter = $balanceBefore - $totalAmount;

            // Update balance
            $this->clientModel->update($clientId, ['balance' => $balanceAfter]);

            // Save transaction
            $ref = 'WTD-' . strtoupper(uniqid());
            $this->transactionModel->insert([
                'transaction_reference' => $ref,
                'operation_type_id'     => $operationTypeId,
                'sender_client_id'      => $clientId,
                'receiver_client_id'    => null, // Pas de destinataire, c'est un retrait
                'amount'                => $amount,
                'fee_amount'            => $fee,
                'total_amount'          => $totalAmount,
                'balance_before'        => $balanceBefore,
                'balance_after'         => $balanceAfter,
                'status'                => 'completed'
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception("Erreur lors de la transaction de retrait.");
            }

            return ['success' => true, 'reference' => $ref, 'fee' => $fee, 'total' => $totalAmount, 'balance_after' => $balanceAfter];

        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function transfer(int $senderId, string $receiverPhoneNumber, float $amount): array
    {
        $this->db->transStart();

        try {
            $sender = $this->clientModel->find($senderId);
            if (!$sender) throw new \Exception("Expéditeur introuvable.");

            $receiver = $this->clientModel->where('phone_number', $receiverPhoneNumber)->first();
            if (!$receiver) throw new \Exception("Le numéro du destinataire n'existe pas.");

            if ($sender['id'] === $receiver['id']) {
                throw new \Exception("Vous ne pouvez pas effectuer un transfert vers vous-même.");
            }

            $operationTypeId = $this->getOperationTypeId('TRANSFER');
            $fee = $this->feeCalculator->calculateFee($operationTypeId, $amount);
            
            $totalAmount = $amount + $fee; // L'expéditeur paie le transfert + les frais
            $senderBalanceBefore = (float) $sender['balance'];

            if ($senderBalanceBefore < $totalAmount) {
                throw new \Exception("Solde insuffisant pour couvrir le montant et les frais de $fee Ar.");
            }

            $senderBalanceAfter = $senderBalanceBefore - $totalAmount;
            $receiverBalanceBefore = (float) $receiver['balance'];
            $receiverBalanceAfter = $receiverBalanceBefore + $amount;

            // Update balances
            $this->clientModel->update($senderId, ['balance' => $senderBalanceAfter]);
            $this->clientModel->update($receiver['id'], ['balance' => $receiverBalanceAfter]);

            // Save transaction (perspective de l'expéditeur pour le suivi)
            $ref = 'TRF-' . strtoupper(uniqid());
            $this->transactionModel->insert([
                'transaction_reference' => $ref,
                'operation_type_id'     => $operationTypeId,
                'sender_client_id'      => $senderId,
                'receiver_client_id'    => $receiver['id'],
                'amount'                => $amount,
                'fee_amount'            => $fee,
                'total_amount'          => $totalAmount,
                'balance_before'        => $senderBalanceBefore,
                'balance_after'         => $senderBalanceAfter,
                'status'                => 'completed'
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception("Erreur lors du transfert.");
            }

            return ['success' => true, 'reference' => $ref, 'fee' => $fee, 'total' => $totalAmount, 'balance_after' => $senderBalanceAfter];

        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
