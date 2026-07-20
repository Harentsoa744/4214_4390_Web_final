<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Operator
        $this->db->table('operators')->insert([
            'username'      => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        // 2. Phone Prefixes
        $prefixes = [
            ['prefix' => '032', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['prefix' => '033', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['prefix' => '034', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['prefix' => '037', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['prefix' => '038', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('phone_prefixes')->insertBatch($prefixes);

        // 3. Operation Types
        $operationTypes = [
            ['id' => 1, 'code' => 'DEPOSIT', 'name' => 'Dépôt', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'code' => 'WITHDRAWAL', 'name' => 'Retrait', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'code' => 'TRANSFER', 'name' => 'Transfert', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('operation_types')->insertBatch($operationTypes);

        // 4. Fee Brackets
        $feeBrackets = [
            // DEPOSIT
            ['operation_type_id' => 1, 'min_amount' => 0, 'max_amount' => 999999999, 'fee_amount' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            
            // WITHDRAWAL
            ['operation_type_id' => 2, 'min_amount' => 100, 'max_amount' => 1000, 'fee_amount' => 50, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 100, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 200, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 1000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 50001, 'max_amount' => 100000, 'fee_amount' => 2000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 4000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 8000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 12000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 2, 'min_amount' => 1000001, 'max_amount' => 999999999, 'fee_amount' => 15000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // TRANSFER
            ['operation_type_id' => 3, 'min_amount' => 100, 'max_amount' => 1000, 'fee_amount' => 50, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 50, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 100, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 200, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 400, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 50001, 'max_amount' => 100000, 'fee_amount' => 800, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 1500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 1500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 2500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 1000001, 'max_amount' => 2000000, 'fee_amount' => 3000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['operation_type_id' => 3, 'min_amount' => 2000001, 'max_amount' => 999999999, 'fee_amount' => 5000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('fee_brackets')->insertBatch($feeBrackets);

        // 5. Clients
        $clients = [
            ['phone_number' => '0340000001', 'balance' => 50000.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['phone_number' => '0320000002', 'balance' => 150000.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['phone_number' => '0330000003', 'balance' => 0.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('clients')->insertBatch($clients);
    }
}
