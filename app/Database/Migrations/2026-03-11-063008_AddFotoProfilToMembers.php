<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoProfilToMembers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('members', [
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('members', 'foto_profil');
    }
}