<?php

namespace App\Services;

use App\Models\PhonePrefixModel;
use App\Models\OperatorModel;

class OperatorResolverService
{
    protected PhonePrefixModel $phonePrefixModel;
    protected OperatorModel $operatorModel;

    public function __construct()
    {
        $this->phonePrefixModel = new PhonePrefixModel();
        $this->operatorModel = new OperatorModel();
    }

    /**
     * Identifie l'opérateur associé à un numéro de téléphone
     * 
     * @param string $phoneNumber
     * @return array|null Les données de l'opérateur ou null si non trouvé
     */
    public function resolveOperator(string $phoneNumber): ?array
    {
        // Supposons que les préfixes fassent 3 chiffres pour la démo (ex: 032, 033, 034)
        // Dans la vraie vie, on pourrait chercher des préfixes de tailles variables
        $prefixStr = substr($phoneNumber, 0, 3);
        
        $prefixData = $this->phonePrefixModel
            ->where('prefix', $prefixStr)
            ->where('is_active', 1)
            ->first();

        if (!$prefixData || empty($prefixData['operator_id'])) {
            return null;
        }

        return $this->operatorModel->find($prefixData['operator_id']);
    }

    /**
     * Vérifie si le transfert entre l'expéditeur et le destinataire est interne
     */
    public function isInternalTransfer(string $senderPhone, string $receiverPhone): bool
    {
        $senderOperator = $this->resolveOperator($senderPhone);
        $receiverOperator = $this->resolveOperator($receiverPhone);

        if (!$senderOperator || !$receiverOperator) {
            throw new \Exception("Opérateur introuvable pour l'un des numéros.");
        }

        return $senderOperator['id'] === $receiverOperator['id'];
    }

    /**
     * Récupère l'opérateur principal
     */
    public function getMainOperator(): ?array
    {
        return $this->operatorModel->where('is_main_operator', 1)->first();
    }
}
