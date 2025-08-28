<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCallLogsTable extends Migration
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
            'lead_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'old_status' => [
                'type'       => 'ENUM',
                'constraint' => ['new', 'followup', 'na', 'dead', 'interested', 'win'],
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'ENUM',
                'constraint' => ['new', 'followup', 'na', 'dead', 'interested', 'win'],
            ],
            'remark' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'follow_up_date_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('lead_id', 'leads', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        // Add index for follow_up_date_time for efficient querying
        $this->forge->addKey('follow_up_date_time');
        
        $this->forge->createTable('call_logs');
    }

    public function down()
    {
        $this->forge->dropTable('call_logs');
    }
}

