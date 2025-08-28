<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserAssignedLocationsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'city_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('state_id', 'states', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('city_id', 'cities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_assigned_locations');
    }

    public function down()
    {
        $this->forge->dropTable('user_assigned_locations');
    }
}

