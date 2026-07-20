<?php

namespace App\Services;

use App\Models\OperationTypeModel;
use App\Models\CommissionModel;

class TransferCostCalculatorService
{
    protected FeeCalculatorService $feeCalculator;
    protected OperatorResolverService $operatorResolver;
    protected CommissionModel $commissionModel;
    protected OperationTypeModel $operationTypeModel;

    public function __construct()
    {
        $this->feeCalculator = new FeeCalculatorService();
        $this->operatorResolver = new OperatorResolverService();
        $this->commissionModel = new CommissionModel();
        $this->operationTypeModel = new OperationTypeModel();
    }

    /**
     * Calcule tous les coûts associés à un transfert
     * 
     * @param string $senderPhone Le numéro de l'expéditeur
     * @param string $receiverPhone Le numéro du destinataire
     * @param float $amount Le montant envoyé au bénéficiaire
     * @param bool $includeWithdrawalFee Si true, calcule et ajoute les frais de retrait
     * @return array [ 'transfer_fee', 'commission_amount', 'withdrawal_fee', 'total_debited', 'transfer_type', 'destination_operator_id' ]
     */
    public function calculateCosts(string $senderPhone, string $receiverPhone, float $amount, bool $includeWithdrawalFee = false): array
    {
        $senderOperator = $this->operatorResolver->resolveOperator($senderPhone);
        $receiverOperator = $this->operatorResolver->resolveOperator($receiverPhone);

        if (!$senderOperator || !$receiverOperator) {
            throw new \Exception("Impossible d'identifier l'opérateur pour le calcul des frais.");
        }

        $transferType = ($senderOperator['id'] === $receiverOperator['id']) ? 'INTERNAL' : 'INTER_OPERATOR';

        // 1. Frais de transfert de base
        $transferOpType = $this->operationTypeModel->where('code', 'TRANSFER')->first();
        $transferFee = $this->feeCalculator->calculateFee((int)$transferOpType['id'], $amount);

        // 2. Commission inter-opérateur
        $commissionAmount = 0.00;
        
        // La commission s'applique si l'opérateur destinataire n'est pas le même que l'expéditeur principal
        if ($transferType === 'INTER_OPERATOR') {
            $commissionConfig = $this->commissionModel
                ->where('operator_id', $receiverOperator['id'])
                ->where('is_active', 1)
                ->first();
            
            if ($commissionConfig) {
                $percentage = (float) $commissionConfig['commission_percentage'];
                $commissionAmount = $amount * ($percentage / 100);
            }
        }

        // 3. Frais de retrait (si inclus)
        $withdrawalFee = 0.00;
        // Les frais de retrait ne s'appliquent qu'aux transferts internes
        if ($includeWithdrawalFee && $transferType === 'INTERNAL') {
            $withdrawalOpType = $this->operationTypeModel->where('code', 'WITHDRAWAL')->first();
            $withdrawalFee = $this->feeCalculator->calculateFee((int)$withdrawalOpType['id'], $amount);
        }

        // 4. Calcul du total débité à l'expéditeur
        $totalDebited = $amount + $transferFee + $commissionAmount + $withdrawalFee;

        return [
            'amount' => $amount,
            'transfer_fee' => $transferFee,
            'commission_amount' => $commissionAmount,
            'withdrawal_fee' => $withdrawalFee,
            'total_debited' => $totalDebited,
            'transfer_type' => $transferType,
            'destination_operator_id' => $receiverOperator['id']
        ];
    }
}
