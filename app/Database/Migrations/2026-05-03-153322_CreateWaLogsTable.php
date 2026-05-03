<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWaLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'loan_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'member_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'book_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'type' => [
                'type'    => "ENUM('before_due','overdue')",
                'default' => 'before_due',
            ],
            'status' => [
                'type'    => "ENUM('sent','failed','skipped')",
                'default' => 'sent',
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'note' => [
                // untuk menyimpan pesan error jika gagal
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('wa_logs');
    }

    public function down()
    {
        $this->forge->dropTable('wa_logs', true);
    }
}