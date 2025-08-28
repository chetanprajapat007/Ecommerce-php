<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('states');
        
        // Insert sample states
        $states = [
            ['name' => 'Maharashtra', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Gujarat', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Karnataka', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Tamil Nadu', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Delhi', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];
        
        $this->db->table('states')->insertBatch($states);
    }

    public function down()
    {
        $this->forge->dropTable('states');
    }
}

