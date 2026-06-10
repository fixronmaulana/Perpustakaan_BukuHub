<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\MemberModel;
use App\Models\PointTransactionModel;
use App\Models\PointSettingModel;
use App\Models\LeaderboardSnapshotModel;
use App\Models\RewardModel;

class Home extends BaseController
{
    protected BookModel             $bookModel;
    protected MemberModel           $memberModel;
    protected PointTransactionModel $pointModel;
    protected PointSettingModel     $pointSettingModel;
    protected LeaderboardSnapshotModel $leaderboardModel;
    protected RewardModel              $rewardModel;

    public function __construct()
    {
        $this->bookModel          = new BookModel;
        $this->memberModel        = new MemberModel;
        $this->pointModel         = new PointTransactionModel;
        $this->pointSettingModel  = new PointSettingModel;
        $this->leaderboardModel   = new LeaderboardSnapshotModel;
        $this->rewardModel        = new RewardModel;
    }

    public function index(): string
    {
        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->orderBy('books.id', 'DESC') // Mengurutkan dari yang terbaru dimasukkan
            ->limit(4) 
            ->findAll();

            // Menggunakan countAllResults() untuk menghitung semua baris di tabel books
            $totalBooks = $this->bookModel->countAllResults();

            // Menghitung anggota yang tidak dihapus (anggota aktif)
            $totalMembers = $this->memberModel->where('deleted_at', null)->countAllResults();

            return view('home/home', [
                'books'     => $books,
                'totalBooks'   => $totalBooks,
                'totalMembers' => $totalMembers,
                'activeNav' => 'beranda'
            ]);
    }

    public function book(): string
    {
        $itemPerPage = 20;

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->like('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->orLike('author', $keyword, insensitiveSearch: true)
                ->orLike('publisher', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'books');

            $books = array_filter($books, fn($b) => $b['deleted_at'] == null);
        } else {
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->paginate($itemPerPage, 'books');
        }

        return view('home/book', [
            'books'       => $books,
            'pager'       => $this->bookModel->pager,
            'currentPage' => $this->request->getVar('page_books') ?? 1,
            'itemPerPage' => $itemPerPage,
            'search'      => $this->request->getGet('search'),
            'activeNav'   => 'koleksi',
        ]);
    }

    public function bookShow(string $slug): string
{
    $book = $this->bookModel
        ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
        ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
        ->join('categories', 'books.category_id = categories.id', 'LEFT')
        ->join('racks', 'books.rack_id = racks.id', 'LEFT')
        ->where('books.slug', $slug)
        ->first();

    if (empty($book)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak ditemukan');
    }

    return view('home/book_show', [
        'book'      => $book,
        'activeNav' => 'koleksi',
    ]);
}

    public function layanan(): string
    {
        return view('home/layanan', ['activeNav' => 'layanan']);
    }

    public function leaderboard(): string
    {
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $bulan = (int) ($this->request->getGet('bulan') ?? $bulanIni);
        $tahun = (int) ($this->request->getGet('tahun') ?? $tahunIni);

        if ($bulan === $bulanIni && $tahun === $tahunIni) {
            // Bulan berjalan real-time
            $leaderboard = $this->_getLeaderboardRealtime($bulan, $tahun);
        } else {
            // Bulan lalu lazy snapshot
            if (!$this->leaderboardModel->sudahAda($bulan, $tahun)) {
                $this->leaderboardModel->buatSnapshot($bulan, $tahun);
            }
            $leaderboard = $this->leaderboardModel->getLeaderboard($bulan, $tahun);
        }

        $pointSettings = $this->pointSettingModel->getAllAsMap();

        // Dropdown 6 bulan
        $daftarBulan = [];
        for ($i = 0; $i < 6; $i++) {
            $ts = mktime(0, 0, 0, $bulanIni - $i, 1, $tahunIni);
            $daftarBulan[] = [
                'bulan' => (int) date('n', $ts),
                'tahun' => (int) date('Y', $ts),
                'label' => date('F Y', $ts),
            ];
        }

        $hadiah = $this->rewardModel->getHadiahBulan($bulan, $tahun);

        if ($bulan !== $bulanIni || $tahun !== $tahunIni) {
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
        }

        return view('home/leaderboard', [
            'activeNav'     => 'leaderboard',
            'leaderboard'   => $leaderboard,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'bulanIni'      => $bulanIni,
            'tahunIni'      => $tahunIni,
            'daftarBulan'   => $daftarBulan,
            'pointSettings' => $pointSettings,
            'hadiah'        => $hadiah,
        ]);
    }

    public function kontak(): string
    {
        return view('home/kontak', ['activeNav' => 'kontak']);
    }

    private function _getLeaderboardRealtime(int $bulan, int $tahun): array
    {
        $members = $this->memberModel->where('deleted_at', null)->findAll();
        $data    = [];
        $db      = \Config\Database::connect();

        foreach ($members as $m) {
            // Hitung poin per aktivitas dalam bulan ini
            $breakdown = $db->table('point_transactions')
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
                'poin_kunjungan'  => (int) ($breakdown['poin_kunjungan']  ?? 0),
                'poin_peminjaman' => (int) ($breakdown['poin_peminjaman'] ?? 0),
                'poin_tepat'      => (int) ($breakdown['poin_tepat']      ?? 0),
                'poin_terlambat'  => (int) ($breakdown['poin_terlambat']  ?? 0),
                'poin_kuis'       => (int) ($breakdown['poin_kuis']       ?? 0),
                'total_points'    => (int) ($breakdown['total_points']    ?? 0),
            ];
        }

        usort($data, fn($a, $b) => $b['total_points'] <=> $a['total_points']);
        return $data;
    }
}