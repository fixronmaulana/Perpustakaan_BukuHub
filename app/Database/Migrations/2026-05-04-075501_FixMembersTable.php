<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixMembersTable extends Migration
{
    public function up()
    {
        // Hapus kolom yang tidak dipakai
        $this->forge->dropColumn('members', 'address');
        $this->forge->dropColumn('members', 'date_of_birth');

        // Tambah kolom yang kurang
        $this->forge->addColumn('members', [
            'no_identitas' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'after'      => 'email',
            ],
            'tipe_anggota' => [
                'type'       => 'ENUM',
                'constraint' => ['Murid', 'Guru', 'Staf'],
                'null'       => false,
                'default'    => 'Murid',
                'after'      => 'no_identitas',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('members', 'no_identitas');
        $this->forge->dropColumn('members', 'tipe_anggota');

        $this->forge->addColumn('members', [
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ]);
    }
}