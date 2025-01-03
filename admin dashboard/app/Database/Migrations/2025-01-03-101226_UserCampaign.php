<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserCampaign extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'campaign_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey(['user_id', 'campaign_id'], true); // Composite primary key

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('campaign_id', 'campaign', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('user_campaign');
    }

    public function down()
    {
        $this->forge->dropTable('user_campaign');
    }
}

