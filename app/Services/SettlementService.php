<?php

namespace App\Services;

use App\Models\SettlementModel;
use App\Models\OperatorModel;

class SettlementService
{
    protected SettlementModel $settlementModel;
    protected OperatorModel $operatorModel;
    protected $db;

    public function __construct()
    {
        $this->settlementModel = new SettlementModel();
        $this->operatorModel = new OperatorModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Génère ou met à jour les montants dus à chaque opérateur externe.
     * Cette méthode agrège les transactions INTER_OPERATOR et calcule les totaux.
     */
    public function generateSettlements(): array
    {
        // Dans une version plus poussée, on ferait ça par période (mois, semaine).
        // Ici, on va simplement calculer le total depuis toujours pour chaque opérateur externe
        // et mettre à jour/créer une ligne de "Settlement" global par opérateur.

        $mainOperator = $this->operatorModel->where('is_main_operator', 1)->first();
        if (!$mainOperator) return [];

        $externalOperators = $this->operatorModel->where('is_main_operator', 0)->findAll();
        
        $results = [];

        foreach ($externalOperators as $extOp) {
            // Agréger les transactions envoyées vers extOp
            $query = $this->db->table('transactions')
                ->select('COUNT(id) as tx_count, SUM(amount) as total_amount, SUM(commission_amount) as total_commission')
                ->where('destination_operator_id', $extOp['id'])
                ->where('transfer_type', 'INTER_OPERATOR')
                ->where('status', 'completed')
                ->get()
                ->getRowArray();
            
            $txCount = (int) ($query['tx_count'] ?? 0);
            $totalAmount = (float) ($query['total_amount'] ?? 0);
            $totalCommission = (float) ($query['total_commission'] ?? 0);
            
            // Le montant à reverser = Le montant total envoyé + la commission due à l'opérateur externe
            // (La commission inter-opérateur est versée à l'opérateur destinataire)
            $amountToSettle = $totalAmount + $totalCommission;

            // Vérifier si on a déjà un settlement en cours
            $existing = $this->settlementModel->where('destination_operator_id', $extOp['id'])->first();

            if ($existing) {
                // Mettre à jour
                $this->settlementModel->update($existing['id'], [
                    'total_transfer_amount' => $totalAmount,
                    'total_commission' => $totalCommission,
                    'amount_to_settle' => $amountToSettle,
                    'period_end' => date('Y-m-d H:i:s'), // On étend la période
                ]);
                $results[] = $this->settlementModel->find($existing['id']);
            } else if ($txCount > 0) {
                // Créer
                $this->settlementModel->insert([
                    'destination_operator_id' => $extOp['id'],
                    'period_start' => date('Y-m-d H:i:s', strtotime('-1 month')), // dummy start
                    'period_end' => date('Y-m-d H:i:s'),
                    'total_transfer_amount' => $totalAmount,
                    'total_commission' => $totalCommission,
                    'amount_to_settle' => $amountToSettle,
                    'amount_settled' => 0,
                    'status' => 'PENDING'
                ]);
                $results[] = $this->settlementModel->find($this->settlementModel->getInsertID());
            }
        }

        return $results;
    }

    /**
     * Marque un reversement comme effectué
     */
    public function markAsSettled(int $settlementId): bool
    {
        $settlement = $this->settlementModel->find($settlementId);
        if (!$settlement) return false;

        $amountToSettle = $settlement['amount_to_settle'];

        $this->settlementModel->update($settlementId, [
            'status' => 'SETTLED',
            'amount_settled' => $amountToSettle,
            'settled_at' => date('Y-m-d H:i:s'),
            'reference' => 'SETTLE-' . strtoupper(uniqid())
        ]);

        return true;
    }
}
