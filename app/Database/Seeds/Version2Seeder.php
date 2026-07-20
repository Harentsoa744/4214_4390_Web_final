<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Version2Seeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Mettre à jour l'opérateur principal (admin existant)
        $db->table('operators')->where('username', 'admin')->update([
            'name' => 'OPERATEUR_A',
            'code' => 'OP_A',
            'is_main_operator' => 1,
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $mainOperator = $db->table('operators')->where('username', 'admin')->get()->getRow();
        if (!$mainOperator) {
            echo "Erreur: Opérateur principal introuvable.\n";
            return;
        }

        // 2. Ajouter les opérateurs externes
        $externalOperators = [
            [
                'username' => 'op_b_dummy_' . uniqid(),
                'password_hash' => password_hash('dummy', PASSWORD_BCRYPT),
                'name' => 'OPERATEUR_B',
                'code' => 'OP_B',
                'is_main_operator' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'op_c_dummy_' . uniqid(),
                'password_hash' => password_hash('dummy', PASSWORD_BCRYPT),
                'name' => 'OPERATEUR_C',
                'code' => 'OP_C',
                'is_main_operator' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($externalOperators as $op) {
            $exists = $db->table('operators')->where('code', $op['code'])->countAllResults();
            if ($exists == 0) {
                $db->table('operators')->insert($op);
            }
        }

        $opB = $db->table('operators')->where('code', 'OP_B')->get()->getRow();
        $opC = $db->table('operators')->where('code', 'OP_C')->get()->getRow();

        // 3. Affecter les préfixes existants aux opérateurs
        // OP_A: 033, 037
        // OP_B: 032, 031
        // OP_C: 034, 035

        $prefixMapping = [
            '033' => $mainOperator->id,
            '037' => $mainOperator->id,
            '032' => $opB->id,
            '031' => $opB->id,
            '034' => $opC->id,
            '035' => $opC->id,
        ];

        foreach ($prefixMapping as $prefix => $opId) {
            // Insérer si n'existe pas, ou mettre à jour si existe
            $exists = $db->table('phone_prefixes')->where('prefix', $prefix)->countAllResults();
            if ($exists > 0) {
                $db->table('phone_prefixes')->where('prefix', $prefix)->update(['operator_id' => $opId]);
            } else {
                $db->table('phone_prefixes')->insert([
                    'prefix' => $prefix,
                    'operator_id' => $opId,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 4. Définir les commissions initiales
        $commissions = [
            [
                'source_operator_id' => $mainOperator->id,
                'destination_operator_id' => $opB->id,
                'commission_percentage' => 2.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'source_operator_id' => $mainOperator->id,
                'destination_operator_id' => $opC->id,
                'commission_percentage' => 3.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($commissions as $comm) {
            $exists = $db->table('operator_commissions')
                ->where('source_operator_id', $comm['source_operator_id'])
                ->where('destination_operator_id', $comm['destination_operator_id'])
                ->countAllResults();
                
            if ($exists == 0) {
                $db->table('operator_commissions')->insert($comm);
            }
        }
        
        // Mettre à jour les transactions existantes avec le type INTERNAL et le main operator si NULL
        $db->table('transactions')->where('transfer_type IS NULL')->update([
            'transfer_type' => 'INTERNAL'
        ]);
    }
}
