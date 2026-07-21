<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Obtenir les IDs des opérateurs qui ne sont pas l'opérateur principal
        $query = $db->table('operators')->where('is_main_operator', 0)->get();
        $operators = $query->getResultArray();

        $data = [];
        $now = date('Y-m-d H:i:s');

        foreach ($operators as $operator) {
            $data[] = [
                'operator_id'           => $operator['id'],
                'commission_percentage' => 5.00,
                'is_active'             => 1,
                'created_at'            => $now,
                'updated_at'            => $now,
            ];
        }

        if (!empty($data)) {
            $this->db->table('commissions')->insertBatch($data);
        }
    }
}
