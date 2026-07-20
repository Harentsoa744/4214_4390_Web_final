<?php

namespace App\Controllers\Operator;

use CodeIgniter\Controller;
use App\Models\OperationTypeModel;

class DashboardController extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $operationTypeModel = new OperationTypeModel();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $typeCode = $this->request->getGet('type_code');
        $destOperator = $this->request->getGet('dest_operator');

        // --- STATS INTERNES ---
        $internalBuilder = $db->table('transactions t')
            ->select('
                COUNT(t.id) as count_internal,
                SUM(t.amount) as volume_internal,
                SUM(CASE WHEN o.code = "DEPOSIT" THEN t.fee_amount ELSE 0 END) as fee_deposits,
                SUM(CASE WHEN o.code = "WITHDRAWAL" THEN t.fee_amount ELSE 0 END) as fee_withdrawals,
                SUM(CASE WHEN o.code = "TRANSFER" THEN t.fee_amount ELSE 0 END) as fee_transfers
            ')
            ->join('operation_types o', 'o.id = t.operation_type_id')
            ->where('t.transfer_type', 'INTERNAL');

        if ($startDate) $internalBuilder->where('t.created_at >=', $startDate . ' 00:00:00');
        if ($endDate) $internalBuilder->where('t.created_at <=', $endDate . ' 23:59:59');
        if ($typeCode) $internalBuilder->where('o.code', $typeCode);

        $internalStats = $internalBuilder->get()->getRowArray();
        
        $internalStats['total_revenue'] = 
            ($internalStats['fee_deposits'] ?? 0) + 
            ($internalStats['fee_withdrawals'] ?? 0) + 
            ($internalStats['fee_transfers'] ?? 0);

        // --- STATS INTER-OPÉRATEURS ---
        $externalBuilder = $db->table('transactions t')
            ->select('
                COUNT(t.id) as count_external,
                SUM(t.amount) as volume_external,
                SUM(t.fee_amount) as fee_external,
                SUM(t.commission_amount) as commission_external
            ')
            ->join('operation_types o', 'o.id = t.operation_type_id')
            ->where('t.transfer_type', 'INTER_OPERATOR');

        if ($startDate) $externalBuilder->where('t.created_at >=', $startDate . ' 00:00:00');
        if ($endDate) $externalBuilder->where('t.created_at <=', $endDate . ' 23:59:59');
        if ($typeCode) $externalBuilder->where('o.code', $typeCode);
        if ($destOperator) $externalBuilder->where('t.destination_operator_id', $destOperator);

        $externalStats = $externalBuilder->get()->getRowArray();
        
        // Les frais de transfert de base vont toujours à l'opérateur expéditeur (principal), 
        // tandis que la commission va à l'autre.
        // L'opérateur principal voit les frais qu'il gagne sur les envois vers les autres.
        $externalStats['total_revenue_for_main'] = $externalStats['fee_external'] ?? 0;
        $externalStats['total_commissions_generated'] = $externalStats['commission_external'] ?? 0; // dûs aux autres

        // --- COMMISSIONS PAR OPÉRATEUR EXTERNE ---
        $commissionsByOpBuilder = $db->table('transactions t')
            ->select('
                op.name as operator_name,
                SUM(t.commission_amount) as total_commission
            ')
            ->join('operators op', 'op.id = t.destination_operator_id')
            ->where('t.transfer_type', 'INTER_OPERATOR')
            ->where('op.is_main_operator', 0)
            ->groupBy('op.id');

        if ($startDate) $commissionsByOpBuilder->where('t.created_at >=', $startDate . ' 00:00:00');
        if ($endDate) $commissionsByOpBuilder->where('t.created_at <=', $endDate . ' 23:59:59');
        if ($destOperator) $commissionsByOpBuilder->where('t.destination_operator_id', $destOperator);

        $commissionsByOperator = $commissionsByOpBuilder->get()->getResultArray();

        // --- TRANSACTIONS DÉTAILLÉES (Gains) ---
        $gainsBuilder = $db->table('transactions t')
            ->select('
                t.*, 
                o.code as operation_code, 
                o.name as operation_name, 
                c_sender.phone_number as sender_phone, 
                c_receiver.phone_number as receiver_phone,
                op.name as dest_operator_name
            ')
            ->join('operation_types o', 'o.id = t.operation_type_id')
            ->join('clients c_sender', 'c_sender.id = t.sender_client_id', 'left')
            ->join('clients c_receiver', 'c_receiver.id = t.receiver_client_id', 'left')
            ->join('operators op', 'op.id = t.destination_operator_id', 'left')
            ->whereIn('o.code', ['WITHDRAWAL', 'TRANSFER'])
            ->where('(t.fee_amount > 0 OR t.commission_amount > 0)')
            ->orderBy('t.created_at', 'DESC');

        if ($startDate) $gainsBuilder->where('t.created_at >=', $startDate . ' 00:00:00');
        if ($endDate) $gainsBuilder->where('t.created_at <=', $endDate . ' 23:59:59');
        if ($typeCode) $gainsBuilder->where('o.code', $typeCode);
        if ($destOperator) $gainsBuilder->where('t.destination_operator_id', $destOperator);

        $feeTransactions = $gainsBuilder->get()->getResultArray();

        $operationTypes = $operationTypeModel->findAll();
        $operators = $db->table('operators')->where('is_main_operator', 0)->get()->getResultArray();

        return view('operator/dashboard', [
            'internalStats' => $internalStats,
            'externalStats' => $externalStats,
            'commissionsByOperator' => $commissionsByOperator,
            'feeTransactions' => $feeTransactions,
            'operationTypes' => $operationTypes,
            'operators' => $operators,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type_code' => $typeCode,
                'dest_operator' => $destOperator
            ]
        ]);
    }
}
