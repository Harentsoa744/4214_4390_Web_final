<?php

namespace App\Controllers\Operator;

use CodeIgniter\Controller;
use App\Models\OperatorCommissionModel;
use App\Models\OperatorModel;

class CommissionController extends Controller
{
    public function index()
    {
        $commissionModel = new OperatorCommissionModel();
        $operatorModel = new OperatorModel();

        $mainOperator = $operatorModel->where('is_main_operator', 1)->first();
        $externalOperators = $operatorModel->where('is_main_operator', 0)->findAll();
        
        $commissions = $commissionModel->findAll();

        return view('operator/commissions/index', [
            'mainOperator' => $mainOperator,
            'externalOperators' => $externalOperators,
            'commissions' => $commissions
        ]);
    }

    public function store()
    {
        $commissionModel = new OperatorCommissionModel();
        
        $sourceId = $this->request->getPost('source_operator_id');
        $destId = $this->request->getPost('destination_operator_id');
        $percentage = $this->request->getPost('commission_percentage');

        // Check if exists
        $existing = $commissionModel->where('source_operator_id', $sourceId)
                                    ->where('destination_operator_id', $destId)
                                    ->first();

        if ($existing) {
            $commissionModel->update($existing['id'], ['commission_percentage' => $percentage]);
        } else {
            $commissionModel->insert([
                'source_operator_id' => $sourceId,
                'destination_operator_id' => $destId,
                'commission_percentage' => $percentage,
                'is_active' => 1
            ]);
        }

        return redirect()->to('operator/commissions')->with('success', 'Commission configurée avec succès.');
    }
}
