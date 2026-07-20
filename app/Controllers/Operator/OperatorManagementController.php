<?php

namespace App\Controllers\Operator;

use CodeIgniter\Controller;
use App\Models\OperatorModel;

class OperatorManagementController extends Controller
{
    public function index()
    {
        $operatorModel = new OperatorModel();
        // Ne pas afficher l'opérateur principal lui-même comme une cible de reversement,
        // Mais on l'affiche pour info
        $operators = $operatorModel->findAll();

        return view('operator/operators/index', [
            'operators' => $operators
        ]);
    }

    public function store()
    {
        $operatorModel = new OperatorModel();
        
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'is_main_operator' => 0,
            'is_active' => 1
        ];

        $operatorModel->insert($data);

        return redirect()->to('operator/operators')->with('success', 'Opérateur ajouté avec succès.');
    }

    public function toggle($id)
    {
        $operatorModel = new OperatorModel();
        $operator = $operatorModel->find($id);

        if ($operator && !$operator['is_main_operator']) {
            $operatorModel->update($id, ['is_active' => !$operator['is_active']]);
            return redirect()->to('operator/operators')->with('success', 'Statut mis à jour.');
        }

        return redirect()->to('operator/operators')->with('error', 'Impossible de modifier cet opérateur.');
    }
}
