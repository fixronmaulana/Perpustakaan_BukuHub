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
        }

        // Dropdown 6 bulan ke belakang
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

    private function _getLeaderboardRealtime(int $bulan, int $tahun): array
    {
        $members = $this->memberModel->where('deleted_at', null)->findAll();
        $data    = [];

        foreach ($members as $m) {
            $total  = $this->pointModel->getTotalPoin($m['id'], $bulan, $tahun);
            $data[] = [
                'member_id'    => $m['id'],
                'first_name'   => $m['first_name'],
                'last_name'    => $m['last_name'] ?? '',
                'no_identitas' => $m['no_identitas'],
                'tipe_anggota' => $m['tipe_anggota'],
                'foto_profil'  => $m['foto_profil'] ?? null,
                'total_points' => $total,
            ];
        }

        usort($data, fn($a, $b) => $b['total_points'] <=> $a['total_points']);
        return $data;
    }
}