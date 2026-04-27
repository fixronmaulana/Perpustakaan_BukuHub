<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\PointTransactionModel;
use App\Models\LeaderboardSnapshotModel;

class LeaderboardController extends BaseController
{
    protected MemberModel              $memberModel;
    protected PointTransactionModel    $pointModel;
    protected LeaderboardSnapshotModel $leaderboardModel;

    public function __construct()
    {
        $this->memberModel      = new MemberModel();
        $this->pointModel       = new PointTransactionModel();
        $this->leaderboardModel = new LeaderboardSnapshotModel();
    }

    public function index()
    {
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $bulan = (int) ($this->request->getGet('bulan') ?? $bulanIni);
        $tahun = (int) ($this->request->getGet('tahun') ?? $tahunIni);

        if ($bulan === $bulanIni && $tahun === $tahunIni) {
            $leaderboard = $this->_getLeaderboardRealtime($bulan, $tahun);
        } else {
            if (!$this->leaderboardModel->sudahAda($bulan, $tahun)) {
                $this->leaderboardModel->buatSnapshot($bulan, $tahun);
            }
            $leaderboard = $this->leaderboardModel->getLeaderboard($bulan, $tahun);
            $leaderboard = $this->_tambahBreakdown($leaderboard, $bulan, $tahun);
        }

        $daftarBulan = [];
        for ($i = 0; $i < 6; $i++) {
            $ts = mktime(0, 0, 0, $bulanIni - $i, 1, $tahunIni);
            $daftarBulan[] = [
                'bulan' => (int) date('n', $ts),
                'tahun' => (int) date('Y', $ts),
                'label' => date('F Y', $ts),
            ];
        }

        return view('leaderboard/index', [
            'leaderboard'  => $leaderboard,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'bulanIni'     => $bulanIni,
            'tahunIni'     => $tahunIni,
            'daftarBulan'  => $daftarBulan,
        ]);
    }

    // ── Realtime dengan breakdown per aktivitas ───────────
    private function _getLeaderboardRealtime(int $bulan, int $tahun): array
    {
        $members = $this->memberModel->where('deleted_at', null)->findAll();
        $db      = \Config\Database::connect();
        $data    = [];

        foreach ($members as $m) {
            $bd = $db->table('point_transactions')
                ->select("
                    SUM(CASE WHEN activity_type = 'visit'         THEN points ELSE 0 END) as poin_kunjungan,
                    SUM(CASE WHEN activity_type = 'loan'          THEN points ELSE 0 END) as poin_peminjaman,
                    SUM(CASE WHEN activity_type = 'return_ontime' THEN points ELSE 0 END) as poin_tepat,
                    SUM(CASE WHEN activity_type = 'return_late'   THEN points ELSE 0 END) as poin_terlambat,
                    SUM(CASE WHEN activity_type = 'quiz'          THEN points ELSE 0 END) as poin_kuis,
                    SUM(points) as total_points
                ")
                ->where('member_id', $m['id'])
                ->where('MONTH(created_at)', $bulan)
                ->where('YEAR(created_at)',  $tahun)
                ->get()->getRowArray();

            $data[] = [
                'member_id'       => $m['id'],
                'first_name'      => $m['first_name'],
                'last_name'       => $m['last_name'] ?? '',
                'no_identitas'    => $m['no_identitas'],
                'tipe_anggota'    => $m['tipe_anggota'],
                'foto_profil'     => $m['foto_profil'] ?? null,
                'poin_kunjungan'  => (int) ($bd['poin_kunjungan']  ?? 0),
                'poin_peminjaman' => (int) ($bd['poin_peminjaman'] ?? 0),
                'poin_tepat'      => (int) ($bd['poin_tepat']      ?? 0),
                'poin_terlambat'  => (int) ($bd['poin_terlambat']  ?? 0),
                'poin_kuis'       => (int) ($bd['poin_kuis']       ?? 0),
                'total_points'    => (int) ($bd['total_points']    ?? 0),
            ];
        }

        usort($data, fn($a, $b) => $b['total_points'] <=> $a['total_points']);
        return $data;
    }

    // ── Tambah breakdown ke snapshot bulan lalu ───────────
    private function _tambahBreakdown(array $leaderboard, int $bulan, int $tahun): array
    {
        $db = \Config\Database::connect();
        foreach ($leaderboard as &$row) {
            $bd = $db->table('point_transactions')
                ->select("
                    SUM(CASE WHEN activity_type = 'visit'         THEN points ELSE 0 END) as poin_kunjungan,
                    SUM(CASE WHEN activity_type = 'loan'          THEN points ELSE 0 END) as poin_peminjaman,
                    SUM(CASE WHEN activity_type = 'return_ontime' THEN points ELSE 0 END) as poin_tepat,
                    SUM(CASE WHEN activity_type = 'return_late'   THEN points ELSE 0 END) as poin_terlambat,
                    SUM(CASE WHEN activity_type = 'quiz'          THEN points ELSE 0 END) as poin_kuis
                ")
                ->where('member_id', $row['member_id'])
                ->where('MONTH(created_at)', $bulan)
                ->where('YEAR(created_at)',  $tahun)
                ->get()->getRowArray();
            $row['poin_kunjungan']  = (int) ($bd['poin_kunjungan']  ?? 0);
            $row['poin_peminjaman'] = (int) ($bd['poin_peminjaman'] ?? 0);
            $row['poin_tepat']      = (int) ($bd['poin_tepat']      ?? 0);
            $row['poin_terlambat']  = (int) ($bd['poin_terlambat']  ?? 0);
            $row['poin_kuis']       = (int) ($bd['poin_kuis']       ?? 0);
        }
        unset($row);
        return $leaderboard;
    }
}