<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePointTables extends Migration
{
    public function up()
    {
        // ── point_settings ───────────────────────────────────
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'activity_type' => ['type' => 'ENUM', 'constraint' => ['visit', 'loan', 'return_ontime', 'return_late'], 'null' => false],
            'label'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'points'        => ['type' => 'INT', 'default' => 0, 'null' => false],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('activity_type');
        $this->forge->createTable('point_settings');

        // ── point_transactions ───────────────────────────────
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'member_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'activity_type'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'points'         => ['type' => 'INT', 'null' => false],
            'description'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'reference_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('point_transactions');

        // ── leaderboard_snapshots ────────────────────────────
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'member_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'month'        => ['type' => 'TINYINT', 'null' => false],
            'year'         => ['type' => 'SMALLINT', 'null' => false],
            'total_points' => ['type' => 'INT', 'default' => 0, 'null' => false],
            'rank'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('leaderboard_snapshots');
    }

    public function down()
    {
        $this->forge->dropTable('leaderboard_snapshots');
        $this->forge->dropTable('point_transactions');
        $this->forge->dropTable('point_settings');
    }
}