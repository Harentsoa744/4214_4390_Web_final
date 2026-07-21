<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateV2Tables extends Migration
{
    public function up()
    {

    // 1. operators
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
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
        $this->forge->addKey('id', true);
        $this->forge->createTable('operators');

        // 2. phone_prefixes
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'prefix'     => ['type' => 'VARCHAR', 'constraint' => '10', 'unique' => true],
            'is_active'  => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('phone_prefixes');

        // 3. operation_types
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'       => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => '100'],
            'is_active'  => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('operation_types');

        // 4. fee_brackets
        $this->forge->addField([
            'id'                => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'operation_type_id' => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true],
            'min_amount'        => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'max_amount'        => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'fee_amount'        => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('operation_type_id', 'operation_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('fee_brackets');

        // 5. clients
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'phone_number' => ['type' => 'VARCHAR', 'constraint' => '20', 'unique' => true],
            'balance'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'status'       => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'active'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clients');

        // 6. transactions
        $this->forge->addField([
            'id'                    => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'transaction_reference' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'operation_type_id'     => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true],
            'sender_client_id'      => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'receiver_client_id'    => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'amount'                => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'fee_amount'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'total_amount'          => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'balance_before'        => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'balance_after'         => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'status'                => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'completed'],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('operation_type_id', 'operation_types', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('sender_client_id', 'clients', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('receiver_client_id', 'clients', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('transactions');

        // 1. Modifier la table 'operators'
        // $this->forge->addColumn('operators', [
        //     'name' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => 100,
        //         'null'       => true,
        //     ],
        //     'code' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => 50,
        //         'null'       => true,
        //     ],
        //     'is_main_operator' => [
        //         'type'       => 'BOOLEAN',
        //         'default'    => 0,
        //     ],
        //     'is_active' => [
        //         'type'       => 'BOOLEAN',
        //         'default'    => 1,
        //     ],
        //     'updated_at' => [
        //         'type'       => 'DATETIME',
        //         'null'       => true,
        //     ],
        // ]);

        // Rendre username et password_hash nullable dans operators n'est pas supporté nativement par Forge pour SQLite de manière simple, 
        // on fournira des valeurs par défaut dans le modèle ou seeder pour les opérateurs externes.

        // 2. Modifier la table 'phone_prefixes'
        $this->forge->addColumn('phone_prefixes', [
            'operator_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
        ]);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'operator_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'commission_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 5.00,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('operator_id', 'operators', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('commissions');
        
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


    //     id INTEGER PRIMARY KEY AUTOINCREMENT,
    // client_id INTEGER NOT NULL,
    // total_amount DECIMAL(15, 2) DEFAULT 0.00,
    // created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    // updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    // FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
        //     'epargne_percentage',
        // 'total_amount',
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'client_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'epargne_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
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
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('epargne');

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
        $this->forge->dropTable('commissions');
        $this->forge->dropTable('transactions');
        $this->forge->dropTable('clients');
        $this->forge->dropTable('fee_brackets');
        $this->forge->dropTable('operation_types');
        $this->forge->dropTable('phone_prefixes');
        $this->forge->dropTable('operators');
        // Note: SQLite ne supporte pas facilement le DROP COLUMN, dans un environnement de dev SQLite on peut recréer la DB ou laisser tel quel.
    }
}
