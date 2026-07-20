<?php

namespace App\Controllers\Operator;

use CodeIgniter\Controller;
use App\Services\SettlementService;

class SettlementController extends Controller
{
    public function index()
    {
        $settlementService = new SettlementService();
        $settlements = $settlementService->generateSettlements();
        
        // On récupère aussi les opérateurs pour l'affichage
        $db = \Config\Database::connect();
        $operators = $db->table('operators')->where('is_main_operator', 0)->get()->getResultArray();
        $operatorNames = array_column($operators, 'name', 'id');

        return view('operator/settlements/index', [
            'settlements' => $settlements,
            'operatorNames' => $operatorNames
        ]);
    }

    public function markAsSent($id)
    {
        $settlementService = new SettlementService();
        $success = $settlementService->markAsSettled((int)$id);

        if ($success) {
            return redirect()->to('operator/settlements')->with('success', 'Le reversement a été marqué comme envoyé avec succès.');
        }

        return redirect()->to('operator/settlements')->with('error', 'Impossible de marquer ce reversement comme envoyé.');
    }
}
