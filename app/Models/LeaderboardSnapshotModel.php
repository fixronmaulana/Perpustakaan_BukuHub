<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaderboardSnapshotModel extends Model
{
    protected $table         = 'leaderboard_snapshots';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['member_id', 'month', 'year', 'total_points', 'rank'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil leaderboard bulan & tahun tertentu dari snapshot
    public function getLeaderboard(int $bulan, int $tahun): array
    {
        return $this->select('leaderboard_snapshots.*, members.first_name, members.last_name,
                              members.tipe_anggota, members.foto_profil, members.no_identitas')
            ->join('members', 'leaderboard_snapshots.member_id = members.id', 'LEFT')
            ->where('month', $bulan)
            ->where('year',  $tahun)
            ->orderBy('rank', 'ASC')
            ->findAll();
    }

    // Buat snapshot bulan tertentu dari point_transactions
    public function buatSnapshot(int $bulan, int $tahun): void
    {
        $memberModel      = new MemberModel();
        $transactionModel = new PointTransactionModel();

        // Hapus snapshot lama kalau ada
        $this->where('month', $bulan)->where('year', $tahun)->delete();

        $members = $memberModel->where('deleted_at', null)->findAll();
        $data    = [];

        foreach ($members as $member) {
            $total    = $transactionModel->getTotalPoin($member['id'], $bulan, $tahun);
            $data[]   = [
                'member_id'    => $member['id'],
                'month'        => $bulan,
                'year'         => $tahun,
                'total_points' => $total,
                'rank'         => 0,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        // Urutkan dan assign rank
        usort($data, fn($a, $b) => $b['total_points'] <=> $a['total_points']);
        foreach ($data as $i => &$row) {
            $row['rank'] = $i + 1;
        }
        unset($row);

        if (!empty($data)) {
            $this->db->table('leaderboard_snapshots')->insertBatch($data);
        }
    }

    // Ambil rank member bulan tertentu
    public function getRankMember(int $memberId, int $bulan, int $tahun): int
    {
        $row = $this->where('member_id', $memberId)
            ->where('month', $bulan)
            ->where('year',  $tahun)
            ->first();
        return $row ? (int) $row['rank'] : 0;
    }

    // Cek apakah snapshot bulan ini sudah ada
    public function sudahAda(int $bulan, int $tahun): bool
    {
        return $this->where('month', $bulan)->where('year', $tahun)->countAllResults() > 0;
    }
}