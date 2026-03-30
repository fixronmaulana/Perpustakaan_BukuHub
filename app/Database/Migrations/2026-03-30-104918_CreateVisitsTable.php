<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitsTable extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'member_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => false],
        'visit_date'  => ['type' => 'DATETIME', 'null' => false],
        'method'      => ['type' => 'ENUM', 'constraint' => ['scan', 'manual'], 'default' => 'manual'],
        'notes'       => ['type' => 'TEXT', 'null' => true],
        'created_at'  => ['type' => 'DATETIME', 'null' => true],
        'updated_at'  => ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addPrimaryKey('id');
    $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('visits');
}

public function down()
{
    $this->forge->dropTable('visits');
}
}
