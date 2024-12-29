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
                'constraint' => ['admin', 'supervisor', 'teamLeader', 'user'],
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('access_level');

        // Insert predefined roles
        $db = \Config\Database::connect();
        $builder = $db->table('access_level');

        $builder->insertBatch([
            ['id' => 1, 'roles' => 'admin'],
            ['id' => 2, 'roles' => 'supervisor'],
            ['id' => 3, 'roles' => 'teamLeader'],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('access_level');
    }
}
