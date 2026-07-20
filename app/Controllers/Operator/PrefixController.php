<?php

namespace App\Controllers\Operator;

use App\Models\PhonePrefixModel;
use CodeIgniter\Controller;

class PrefixController extends Controller
{
    public function index()
    {
        $prefixModel = new PhonePrefixModel();
        $db = \Config\Database::connect();
        
        $prefixes = $db->table('phone_prefixes')
            ->select('phone_prefixes.*, operators.name as operator_name')
            ->join('operators', 'operators.id = phone_prefixes.operator_id', 'left')
            ->orderBy('phone_prefixes.prefix', 'ASC')
            ->get()->getResultArray();

        $operators = $db->table('operators')->where('is_active', 1)->get()->getResultArray();

        return view('operator/prefixes/index', [
            'prefixes' => $prefixes,
            'operators' => $operators
        ]);
    }

    public function store()
    {
        $prefixModel = new PhonePrefixModel();
        
        $data = [
            'prefix' => $this->request->getPost('prefix'),
            'operator_id' => $this->request->getPost('operator_id') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($prefixModel->save($data)) {
            return redirect()->to('/operator/prefixes')->with('success', 'Préfixe ajouté avec succès.');
        } else {
            return redirect()->back()->with('error', implode('<br>', $prefixModel->errors()));
        }
    }

    public function toggle($id)
    {
        $prefixModel = new PhonePrefixModel();
        $prefix = $prefixModel->find($id);
        
        if ($prefix) {
            $prefixModel->update($id, ['is_active' => !$prefix['is_active']]);
            return redirect()->to('/operator/prefixes')->with('success', 'Statut du préfixe modifié.');
        }
        return redirect()->to('/operator/prefixes')->with('error', 'Préfixe introuvable.');
    }

    public function delete($id)
    {
        $prefixModel = new PhonePrefixModel();
        
        // Vérification si utilisé par un client (à faire dans la vraie vie, ici simplifions)
        $db = \Config\Database::connect();
        $prefixData = $prefixModel->find($id);
        if ($prefixData) {
            $count = $db->table('clients')->like('phone_number', $prefixData['prefix'], 'after')->countAllResults();
            if ($count > 0) {
                return redirect()->to('/operator/prefixes')->with('error', 'Impossible de supprimer ce préfixe car il est utilisé par ' . $count . ' client(s).');
            }

            $prefixModel->delete($id);
            return redirect()->to('/operator/prefixes')->with('success', 'Préfixe supprimé.');
        }
        return redirect()->to('/operator/prefixes')->with('error', 'Préfixe introuvable.');
    }
}
