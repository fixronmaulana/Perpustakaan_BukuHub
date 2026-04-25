<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRewardsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'rank'        => ['type' => 'TINYINT', 'null' => false, 'comment' => '1, 2, atau 3'],
            'bulan'       => ['type' => 'TINYINT', 'null' => false],
            'tahun'       => ['type' => 'SMALLINT', 'null' => false],
            'nama_hadiah' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'deskripsi'   => ['type' => 'TEXT', 'null' => true],
            'foto'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active'   => ['type' => 'TINYINT', 'default' => 1, 'null' => false],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('rewards');
    }

    public function down()
    {
        $this->forge->dropTable('rewards');
    }
}