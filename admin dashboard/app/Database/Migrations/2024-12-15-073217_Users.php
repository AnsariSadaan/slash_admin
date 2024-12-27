<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                "null" => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
                "null" => false
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                "null" => false
            ],
            'roles' => [
                'type' => 'ENUM',
                'constraint' => ['user', 'admin', 'supervisor', 'teamleader'],
                'null' => false,
                'default' => 'user' // Optional: Set a default role
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
