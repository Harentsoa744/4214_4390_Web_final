<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Services\TransactionService;
use CodeIgniter\Controller;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function deposit()
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find(session()->get('client_id'));
        return view('client/deposit', ['client' => $client]);
    }

    public function processDeposit()
    {
        $amount = (float) $this->request->getPost('amount');
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur à zéro.');
        }

        $clientId = session()->get('client_id');
        $result = $this->transactionService->deposit($clientId, $amount);

        if ($result['success']) {
            return redirect()->to('/client/dashboard')->with('success', "Dépôt de {$amount} Ar réussi. Frais : {$result['fee']} Ar. Nouveau solde : {$result['balance_after']} Ar. Réf: {$result['reference']}");
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    public function withdraw()
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find(session()->get('client_id'));
        return view('client/withdraw', ['client' => $client]);
    }

    public function processWithdraw()
    {
        $amount = (float) $this->request->getPost('amount');
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur à zéro.');
        }

        $clientId = session()->get('client_id');
        $result = $this->transactionService->withdraw($clientId, $amount);

        if ($result['success']) {
            return redirect()->to('/client/dashboard')->with('success', "Retrait de {$amount} Ar réussi. Frais : {$result['fee']} Ar. Coût total : {$result['total']} Ar. Nouveau solde : {$result['balance_after']} Ar. Réf: {$result['reference']}");
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    public function transfer()
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find(session()->get('client_id'));
        return view('client/transfer', ['client' => $client]);
    }

    public function processTransfer()
    {
        $amount = (float) $this->request->getPost('amount');
        $receiverPhones = $this->request->getPost('receiver_phone_number'); // Peut être un tableau ou string
        $includeWithdrawalFee = (bool) $this->request->getPost('include_withdrawal_fee');

        if (!is_array($receiverPhones)) {
            $receiverPhones = [$receiverPhones];
        }

        // Clean empty values
        $receiverPhones = array_filter(array_map('trim', $receiverPhones));

        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur à zéro.');
        }
        if (empty($receiverPhones)) {
            return redirect()->back()->with('error', 'Veuillez renseigner au moins un destinataire.');
        }

        $clientId = session()->get('client_id');
        
        $multipleTransferService = new \App\Services\MultipleTransferService();
        $result = $multipleTransferService->executeMultipleTransfer($clientId, $amount, $receiverPhones, $includeWithdrawalFee);

        if ($result['success']) {
            $msg = "Transfert de " . number_format($amount, 2) . " Ar vers " . count($receiverPhones) . " destinataire(s) réussi. ";
            $msg .= "Coût total débité : " . number_format($result['total_debited'], 2) . " Ar. ";
            $msg .= "Nouveau solde : " . number_format($result['balance_after'], 2) . " Ar.";
            return redirect()->to('/client/dashboard')->with('success', $msg);
        } else {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }
    }
}
