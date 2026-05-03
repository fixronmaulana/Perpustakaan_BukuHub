<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWaTemplatesTable extends Migration
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
            'type' => [
                'type'    => "ENUM('before_due','overdue')",
                'default' => 'before_due',
            ],
            'template_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'message_template' => [
                'type' => 'TEXT',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('wa_templates');

        // Isi 2 template default
        $this->db->table('wa_templates')->insertBatch([
            [
                'type'             => 'before_due',
                'template_name'    => 'Reminder H-1 Jatuh Tempo',
                'message_template' => "Halo *{nama}*! 👋\n\nIni pengingat bahwa peminjaman buku Anda akan jatuh tempo *besok*.\n\n📚 *Buku:* {judul_buku}\n📅 *Tanggal Pinjam:* {tgl_pinjam}\n⏰ *Jatuh Tempo:* {tgl_jatuh_tempo}\n\nMohon kembalikan buku tepat waktu agar tidak dikenakan denda.\n\nTerima kasih! 🙏\n_Perpustakaan SMK Al-Munawwir_",
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'type'             => 'overdue',
                'template_name'    => 'Notifikasi H+1 Terlambat',
                'message_template' => "Halo *{nama}*! ⚠️\n\nBuku yang Anda pinjam sudah *melewati batas pengembalian*.\n\n📚 *Buku:* {judul_buku}\n📅 *Tanggal Pinjam:* {tgl_pinjam}\n❌ *Jatuh Tempo:* {tgl_jatuh_tempo}\n🕐 *Terlambat:* {hari_terlambat} hari\n\nSegera kembalikan untuk menghindari denda lebih lanjut.\n\nTerima kasih! 🙏\n_Perpustakaan SMK Al-Munawwir_",
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('wa_templates', true);
    }
}