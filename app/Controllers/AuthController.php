<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PhonePrefixModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if (session()->get('client_id')) {
            return redirect()->to('/client/dashboard');
        }

        // Fetch main operator prefixes
        $prefixModel = new PhonePrefixModel();
        $db = \Config\Database::connect();
        
        $mainOperatorPrefixes = $db->table('phone_prefixes')
            ->select('phone_prefixes.prefix')
            ->join('operators', 'operators.id = phone_prefixes.operator_id')
            ->where('operators.is_main_operator', 1)
            ->where('phone_prefixes.is_active', 1)
            ->get()
            ->getResultArray();

        return view('auth/client_login', ['prefixes' => $mainOperatorPrefixes]);
    }

    public function processLogin()
    {
        $prefixInput = $this->request->getPost('prefix');
        $numberInput = $this->request->getPost('phone_number');

        if (!$prefixInput || !$numberInput || !preg_match('/^[0-9]{7}$/', $numberInput)) {
            return redirect()->back()->with('error', 'Le numéro de téléphone (hors préfixe) doit contenir exactement 7 chiffres.');
        }

        $phoneNumber = $prefixInput . $numberInput;

        $db = \Config\Database::connect();
        $prefixModel = new PhonePrefixModel();
        $clientModel = new ClientModel();

        // Vérifier si le préfixe appartient à l'opérateur principal
        $validPrefix = $db->table('phone_prefixes')
            ->join('operators', 'operators.id = phone_prefixes.operator_id')
            ->where('phone_prefixes.prefix', $prefixInput)
            ->where('operators.is_main_operator', 1)
            ->where('phone_prefixes.is_active', 1)
            ->get()
            ->getRow();
        
        if (!$validPrefix) {
            return redirect()->back()->with('error', 'Ce préfixe n\'est pas autorisé pour la connexion.');
        }

        // Vérifier si le client existe
        $client = $clientModel->where('phone_number', $phoneNumber)->first();

        // S'il n'existe pas, on le crée avec un solde de 0
        if (!$client) {
            $clientId = $clientModel->insert([
                'phone_number' => $phoneNumber,
                'balance'      => 0.00,
                'status'       => 'active'
            ]);
            $client = $clientModel->find($clientId);
        } else if ($client['status'] !== 'active') {
            return redirect()->back()->with('error', 'Votre compte est suspendu.');
        }

        // Créer la session
        session()->set([
            'client_id'    => $client['id'],
            'phone_number' => $client['phone_number'],
            'logged_in'    => true
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Connexion réussie.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
