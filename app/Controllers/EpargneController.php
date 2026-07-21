<?php

namespace App\Controllers\Operator;

use App\Models\EpargneModel;
use CodeIgniter\Controller;

class EpargneController extends Controller
{
    public function index()
    {
        $EpargneModel = new EpargneModel();
        
        // On récupère la première commission configurée (s'il y en a une), car elle est unique pour tous les autres opérateurs
        $epargne = $EpargneModel->find(session()->get('client_id'))->first();
        
        return view('client/EpargneConf', ['epargne_percentage' => $epargne ? $epargne['epargne_percentage'] : 0]);
    }

    // public function store()
    // {
    //     $commissionModel = new CommissionModel();
    //     $db = \Config\Database::connect();
        
    //     $percentage = $this->request->getPost('commission_percentage');
    //     $isActive = $this->request->getPost('is_active') ? 1 : 0;

    //     if (!is_numeric($percentage) || $percentage < 0) {
    //         return redirect()->back()->with('error', 'Le pourcentage doit être un nombre positif.');
    //     }

    //     $externalOperators = $db->table('operators')->where('is_main_operator', 0)->get()->getResultArray();

    //     foreach ($externalOperators as $op) {
    //         $existing = $commissionModel->where('operator_id', $op['id'])->first();
    //         if ($existing) {
    //             $commissionModel->update($existing['id'], [
    //                 'commission_percentage' => $percentage,
    //                 'is_active' => $isActive
    //             ]);
    //         } else {
    //             $commissionModel->insert([
    //                 'operator_id' => $op['id'],
    //                 'commission_percentage' => $percentage,
    //                 'is_active' => $isActive
    //             ]);
    //         }
    //     }

    //     return redirect()->to('operator/commissions')->with('success', 'Commission globale configurée avec succès pour tous les opérateurs externes.');
    // }
}
