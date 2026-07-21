<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // ==========================================
        // 1. OPÉRATEURS (Principal + Externe)  
        // ==========================================
        
        // 1a. Opérateur principal (admin / OP_A)
        $mainOperator = $db->table('operators')->where('username', 'admin')->get()->getRow();
        if (!$mainOperator) {
            $db->table('operators')->insert([
                'username'         => 'admin',
                'password_hash'    => password_hash('admin123', PASSWORD_BCRYPT),
                'name'             => 'OPERATEUR_A',
                'code'             => 'OP_A',
                'is_main_operator' => 1,
                'is_active'        => 1,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
            $mainOperator = $db->table('operators')->where('username', 'admin')->get()->getRow();
        } else {
            // Mettre à jour si l'admin existe déjà mais sans la config v2
            $db->table('operators')->where('id', $mainOperator->id)->update([
                'name'             => 'OPERATEUR_A',
                'code'             => 'OP_A',
                'is_main_operator' => 1,
                'is_active'        => 1,
                'updated_at'       => $now,
            ]);
        }

        // 1b. Opérateurs externes (OP_B & OP_C)
        $externalOperators = [
            [
                'username'         => 'op_b_dummy_' . uniqid(),
                'password_hash'    => password_hash('dummy', PASSWORD_BCRYPT),
                'name'             => 'OPERATEUR_B',
                'code'             => 'OP_B',
                'is_main_operator' => 0,
                'is_active'        => 1,
                'created_at'       => $now,
            ],
            [
                'username'         => 'op_c_dummy_' . uniqid(),
                'password_hash'    => password_hash('dummy', PASSWORD_BCRYPT),
                'name'             => 'OPERATEUR_C',
                'code'             => 'OP_C',
                'is_main_operator' => 0,
                'is_active'        => 1,
                'created_at'       => $now,
            ],
        ];

        foreach ($externalOperators as $op) {
            $exists = $db->table('operators')->where('code', $op['code'])->countAllResults();
            if ($exists === 0) {
                $db->table('operators')->insert($op);
            }
        }

        $opB = $db->table('operators')->where('code', 'OP_B')->get()->getRow();
        $opC = $db->table('operators')->where('code', 'OP_C')->get()->getRow();

        // ==========================================
        // 2. PRÉFIXES TÉLÉPHONIQUES & ASSOCIATIONS
        // ==========================================
        $prefixMapping = [
            '033' => $mainOperator->id,
            '037' => $mainOperator->id,
            '032' => $opB->id ?? null,
            '031' => $opB->id ?? null,
            '034' => $opC->id ?? null,
            '035' => $opC->id ?? null,
            '038' => $mainOperator->id,
        ];

        foreach ($prefixMapping as $prefix => $opId) {
            $exists = $db->table('phone_prefixes')->where('prefix', $prefix)->get()->getRow();
            if ($exists) {
                $db->table('phone_prefixes')->where('prefix', $prefix)->update([
                    'operator_id' => $opId,
                    'updated_at'  => $now,
                ]);
            } else {
                $db->table('phone_prefixes')->insert([
                    'prefix'      => $prefix,
                    'operator_id' => $opId,
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // ==========================================
        // 3. TYPES D'OPÉRATIONS
        // ==========================================
        $operationTypes = [
            ['code' => 'DEPOSIT',    'name' => 'Dépôt'],
            ['code' => 'WITHDRAWAL', 'name' => 'Retrait'],
            ['code' => 'TRANSFER',   'name' => 'Transfert'],
        ];

        foreach ($operationTypes as $type) {
            $exists = $db->table('operation_types')->where('code', $type['code'])->get()->getRow();
            if (!$exists) {
                $db->table('operation_types')->insert([
                    'code'       => $type['code'],
                    'name'       => $type['name'],
                    'is_active'  => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $depositId    = $db->table('operation_types')->where('code', 'DEPOSIT')->get()->getRow()->id;
        $withdrawalId = $db->table('operation_types')->where('code', 'WITHDRAWAL')->get()->getRow()->id;
        $transferId   = $db->table('operation_types')->where('code', 'TRANSFER')->get()->getRow()->id;

        // ==========================================
        // 4. TRANCHES DE FRAIS (FEE BRACKETS)
        // ==========================================
        if ($db->table('fee_brackets')->countAllResults() === 0) {
            $feeBrackets = [
                // DEPOSIT
                ['operation_type_id' => $depositId, 'min_amount' => 0, 'max_amount' => 999999999, 'fee_amount' => 0, 'created_at' => $now, 'updated_at' => $now],

                // WITHDRAWAL
                ['operation_type_id' => $withdrawalId, 'min_amount' => 100, 'max_amount' => 1000, 'fee_amount' => 50, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 50, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 100, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 200, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 400, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 50001, 'max_amount' => 100000, 'fee_amount' => 800, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 1500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 1500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 2500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $withdrawalId, 'min_amount' => 1000001, 'max_amount' => 999999999, 'fee_amount' => 3000, 'created_at' => $now, 'updated_at' => $now],

                // TRANSFER
                ['operation_type_id' => $transferId, 'min_amount' => 100, 'max_amount' => 1000, 'fee_amount' => 50, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 50, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 100, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 200, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 400, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 5001, 'max_amount' => 100000, 'fee_amount' => 800, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 1500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 1500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 2500, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 1000001, 'max_amount' => 2000000, 'fee_amount' => 3000, 'created_at' => $now, 'updated_at' => $now],
                ['operation_type_id' => $transferId, 'min_amount' => 2000001, 'max_amount' => 999999999, 'fee_amount' => 5000, 'created_at' => $now, 'updated_at' => $now],
            ];
            $db->table('fee_brackets')->insertBatch($feeBrackets);
        }

        // ==========================================
        // 5. COMMISSIONS
        // ==========================================
        
        // 5a. Structure inter-opérateurs (operator_commissions)
        if ($opB && $opC) {
            $operatorCommissions = [
                [
                    'source_operator_id'      => $mainOperator->id,
                    'destination_operator_id' => $opB->id,
                    'commission_percentage'   => 2.00,
                    'is_active'               => 1,
                    'created_at'              => $now,
                ],
                [
                    'source_operator_id'      => $mainOperator->id,
                    'destination_operator_id' => $opC->id,
                    'commission_percentage'   => 3.00,
                    'is_active'               => 1,
                    'created_at'              => $now,
                ],
            ];

            foreach ($operatorCommissions as $comm) {
                $exists = $db->table('operator_commissions')
                    ->where('source_operator_id', $comm['source_operator_id'])
                    ->where('destination_operator_id', $comm['destination_operator_id'])
                    ->countAllResults();

                if ($exists === 0) {
                    $db->table('operator_commissions')->insert($comm);
                }
            }
        }

        // 5b. Structure globale par opérateur (commissions)
        $nonMainOperators = $db->table('operators')->where('is_main_operator', 0)->get()->getResultArray();
        $commissionsData = [];

        foreach ($nonMainOperators as $op) {
            $exists = $db->table('commissions')->where('operator_id', $op['id'])->countAllResults();
            if ($exists === 0) {
                $commissionsData[] = [
                    'operator_id'           => $op['id'],
                    'commission_percentage' => 5.00,
                    'is_active'             => 1,
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ];
            }
        }

        if (!empty($commissionsData)) {
            $db->table('commissions')->insertBatch($commissionsData);
        }

        // ==========================================
        // 6. CLIENTS DE TEST
        // ==========================================
        $clients = [
            ['phone_number' => '0340000001', 'balance' => 50000.00, 'status' => 'active'],
            ['phone_number' => '0320000002', 'balance' => 150000.00, 'status' => 'active'],
            ['phone_number' => '0330000003', 'balance' => 0.00, 'status' => 'active'],
        ];

        foreach ($clients as $client) {
            $exists = $db->table('clients')->where('phone_number', $client['phone_number'])->get()->getRow();
            if (!$exists) {
                $client['created_at'] = $now;
                $client['updated_at'] = $now;
                $db->table('clients')->insert($client);
            }
        }

        // ==========================================
        // 7. NETTOYAGE / MISES À JOUR EXISTANTS
        // ==========================================
        if ($db->tableExists('transactions')) {
            $db->table('transactions')->where('transfer_type IS NULL')->update([
                'transfer_type' => 'INTERNAL',
            ]);
        }
    }
}