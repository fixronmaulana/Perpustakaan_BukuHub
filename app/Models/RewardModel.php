<?php

namespace App\Models;

use CodeIgniter\Model;

class RewardModel extends Model
{
    protected $table         = 'rewards';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'rank', 'bulan', 'tahun',
        'nama_hadiah', 'deskripsi', 'foto', 'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil hadiah aktif bulan & tahun tertentu, digroup per rank
    public function getHadiahBulan(int $bulan, int $tahun): array
    {
        $rows   = $this->where('bulan', $bulan)
                       ->where('tahun', $tahun)
                       ->where('is_active', 1)
                       ->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['rank']] = $row;
        }
        return $result; // [1 => [...], 2 => [...], 3 => [...]]
    }
}