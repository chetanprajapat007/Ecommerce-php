<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCitiesTable extends Migration
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
            'state_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addForeignKey('state_id', 'states', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cities');
        
        // Insert sample cities
        $cities = [
            ['state_id' => 1, 'name' => 'Mumbai', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 1, 'name' => 'Pune', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 1, 'name' => 'Nagpur', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 2, 'name' => 'Ahmedabad', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 2, 'name' => 'Surat', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 3, 'name' => 'Bangalore', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 3, 'name' => 'Mysore', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 4, 'name' => 'Chennai', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 4, 'name' => 'Coimbatore', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['state_id' => 5, 'name' => 'New Delhi', 'status' => 'active', 'created_by' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];
        
        $this->db->table('cities')->insertBatch($cities);
    }

    public function down()
    {
        $this->forge->dropTable('cities');
    }
}

