<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateV2Tables extends Migration
{
    public function up()
    {
        // 1. Modifier la table 'operators'
        $this->forge->addColumn('operators', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'is_main_operator' => [
                'type'       => 'BOOLEAN',
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => 1,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        // Rendre username et password_hash nullable dans operators n'est pas supporté nativement par Forge pour SQLite de manière simple, 
        // on fournira des valeurs par défaut dans le modèle ou seeder pour les opérateurs externes.

        // 2. Modifier la table 'phone_prefixes'
        $this->forge->addColumn('phone_prefixes', [
            'operator_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
        ]);

        // 3. Modifier la table 'transactions'
        $this->forge->addColumn('transactions', [
            'batch_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
            'destination_operator_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
            'transfer_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'commission_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
        ]);

        // 4. Créer la table 'operator_commissions'
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'source_operator_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'destination_operator_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'commission_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => 1,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('operator_commissions');

        // 5. Créer la table 'settlements'
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'destination_operator_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'period_start' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'period_end' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'total_transfer_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'total_commission' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'amount_to_settle' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'amount_settled' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'PENDING',
            ],
            'settled_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settlements');

        // 6. Créer la table 'transfer_batches'
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'sender_client_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'total_fee' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'total_commission' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'include_withdrawal_fee' => [
                'type'       => 'BOOLEAN',
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'COMPLETED',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transfer_batches');
    }

    public function down()
    {
        $this->forge->dropTable('operator_commissions', true);
        $this->forge->dropTable('settlements', true);
        $this->forge->dropTable('transfer_batches', true);

        // Note: SQLite ne supporte pas facilement le DROP COLUMN, dans un environnement de dev SQLite on peut recréer la DB ou laisser tel quel.
    }
}
