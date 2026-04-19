<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PointSettingsSeeder extends Seeder
{
    public function run()
    {
        // Cek jika sudah ada data, skip
        if ($this->db->table('point_settings')->countAll() > 0) {
            echo "PointSettingsSeeder: data sudah ada, skip.\n";
            return;
        }

        $this->db->table('point_settings')->insertBatch([
            [
                'activity_type' => 'visit',
                'label'         => 'Kunjungan Perpustakaan',
                'points'        => 5,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'activity_type' => 'loan',
                'label'         => 'Peminjaman Buku',
                'points'        => 10,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'activity_type' => 'return_ontime',
                'label'         => 'Pengembalian Tepat Waktu',
                'points'        => 15,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'activity_type' => 'return_late',
                'label'         => 'Pengembalian Terlambat',
                'points'        => -10,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ]);

        echo "PointSettingsSeeder: berhasil.\n";
    }
}