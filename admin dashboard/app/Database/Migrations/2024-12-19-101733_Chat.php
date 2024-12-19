<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Chat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'c_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'c_resource_id' => [
                'type' => 'INT',
            ],
            'c_user_id' => [
                'type' => 'INT',
            ],
            'c_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('chat');
    }

    public function down()
    {
        $this->forge->dropTable('chat');
    }
}
