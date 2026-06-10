<?php

namespace App\Models;

use CodeIgniter\Model;

class PointTransactionModel extends Model
{
    protected $table         = 'point_transactions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'member_id',
        'activity_type',
        'points',
        'description',
        'reference_id',
        'reference_type',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Total poin member bulan ini
    public function getTotalPoinBulanIni(int $memberId): int
    {
        $result = $this->selectSum('points')
            ->where('member_id', $memberId)
            ->where('MONTH(created_at)', (int) date('n'))
            ->where('YEAR(created_at)',  (int) date('Y'))
            ->first();
        return (int) ($result['points'] ?? 0);
    }

    // Total poin member bulan & tahun tertentu
    public function getTotalPoin(int $memberId, int $bulan, int $tahun): int
    {
        $result = $this->selectSum('points')
            ->where('member_id', $memberId)
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)',  $tahun)
            ->first();
        return (int) ($result['points'] ?? 0);
    }

    // Total poin all-time member
    public function getTotalPoinAllTime(int $memberId): int
    {
        $result = $this->selectSum('points')
            ->where('member_id', $memberId)
            ->first();
        return (int) ($result['points'] ?? 0);
    }

    // Riwayat poin 
    public function getRiwayat(int $memberId, int $limit = 20)
    {
        return $this->where('member_id', $memberId)
            ->orderBy('created_at', 'DESC')
            ->paginate($limit, 'poin');
    }
}