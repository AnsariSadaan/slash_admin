<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AccessLevel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'roles' => [
                'type' => 'ENUM',
                'constraint' => ['admin','agent', 'teamLeader', 'superVisor'],
                'null' => false,
                'default' => 'admin' // Optional: Set a default role
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('access_level');
    }

    public function down()
    {
        $this->forge->dropTable('access_level');
    }
}
