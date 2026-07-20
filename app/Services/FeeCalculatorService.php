<?php

namespace App\Services;

use App\Models\FeeBracketModel;

class FeeCalculatorService
{
    /**
     * Calcule les frais pour une opération donnée et un montant donné.
     *
     * @param int $operationTypeId L'ID du type d'opération (Dépôt, Retrait, Transfert)
     * @param float $amount Le montant de l'opération
     * @return float Le montant des frais applicables
     * @throws \Exception Si aucune tranche ne correspond
     */
    public function calculateFee(int $operationTypeId, float $amount): float
    {
        $feeBracketModel = new FeeBracketModel();
        
        $bracket = $feeBracketModel
            ->where('operation_type_id', $operationTypeId)
            ->where('min_amount <=', $amount)
            ->where('max_amount >=', $amount)
            ->first();
            
        if (!$bracket) {
            throw new \Exception("Aucune tranche de frais trouvée pour ce montant.");
        }

        return (float) $bracket['fee_amount'];
    }
}
