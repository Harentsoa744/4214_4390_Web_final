<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionsTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        $this->forge->dropTable('commissions');
    }
}
