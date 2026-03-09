<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToMembers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('members', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'uid',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('members', 'user_id');
    }
}