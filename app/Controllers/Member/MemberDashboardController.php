<?php

namespace App\Controllers\Member;

use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\MemberModel;
use App\Models\LoanModel;
use App\Models\FineModel;
use App\Models\VisitModel;
use App\Models\QuizModel;
use App\Models\QuizQuestionModel;
use App\Models\QuizAttemptModel;
use App\Models\PointTransactionModel;
use App\Models\LeaderboardSnapshotModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class MemberDashboardController extends Controller
{
    protected MemberModel       $memberModel;
    protected LoanModel         $loanModel;
    protected FineModel         $fineModel;
    protected BookModel         $bookModel;
    protected CategoryModel     $categoryModel;
    protected VisitModel        $visitModel;
    protected QuizModel         $quizModel;
    protected QuizQuestionModel $questionModel;
    protected QuizAttemptModel       $attemptModel;
    protected PointTransactionModel  $pointModel;
    protected LeaderboardSnapshotModel $leaderboardModel;

    public function __construct()
    {
        $this->memberModel   = new MemberModel();
        $this->loanModel     = new LoanModel();
        $this->fineModel     = new FineModel();
        $this->bookModel     = new BookModel();
        $this->categoryModel = new CategoryModel();
        $this->visitModel    = new VisitModel();
        $this->quizModel     = new QuizModel();
        $this->questionModel = new QuizQuestionModel();
        $this->attemptModel      = new QuizAttemptModel();
        $this->pointModel        = new PointTransactionModel();
        $this->leaderboardModel  = new LeaderboardSnapshotModel();
        helper(['point']);
    }

    // ── Helper: cek info kuis per transaksi ──────────────────
    private function getKuisInfo(array $ret, int $memberId, float $batasJam = 24): array
    {
        $quiz = $this->quizModel
            ->where('book_id', $ret['book_id'])
            ->where('is_active', 1)
            ->first();

        if (!$quiz) {
            return [
                'quiz_info'    => null,
                'sudah_kuis'   => false,
                'max_habis'    => false,
                'kuis_expired' => false,
            ];
        }

        $attemptsLoan = $this->attemptModel
            ->where('quiz_id', $quiz['id'])
            ->where('member_id', $memberId)
            ->where('loan_id', $ret['id'])
            ->countAllResults();

        // Hitung selisih jam sejak pengembalian
        $returnTimestamp = Time::parse($ret['return_date'])->getTimestamp();
        $nowTimestamp    = Time::now()->getTimestamp();
        $selisihJam      = abs($nowTimestamp - $returnTimestamp) / 3600;
        $masihAktif      = $selisihJam <= $batasJam;

        // Expired: lewat 24 jam DAN belum pernah dikerjakan sama sekali
        // Kalau sudah dikerjakan (sudah_kuis = true), tetap tampil Selesai
        $sudahDikerjakan = $attemptsLoan > 0;
        $percobaanHabis  = $attemptsLoan >= $quiz['max_attempts'];

        // Expired: lewat 24 jam DAN percobaan belum habis
        // Berlaku untuk semua kondisi — belum maupun sudah dikerjakan tapi masih bisa ulangi
        // Yang tidak expired: percobaan sudah habis (tampil Selesai)
        $expired = !$masihAktif && !$percobaanHabis;

        return [
            'quiz_info'    => $quiz,
            'sudah_kuis'   => $sudahDikerjakan,
            'max_habis'    => $percobaanHabis,
            'kuis_expired' => $expired,
        ];
    }

    public function index()
    {
        $member = $this->getMember();
        $now    = Time::now();

        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $visits = $this->visitModel->where('member_id', $member['id'])->findAll();

        $kunjunganBulanIni = count(array_filter($visits, function($v) use ($bulanIni, $tahunIni) {
            $d = Time::parse($v['visit_date']);
            return (int)$d->format('n') === $bulanIni && (int)$d->format('Y') === $tahunIni;
        }));

        $semuaPinjaman = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->orderBy('loans.loan_date', 'DESC')
            ->findAll();

        $sedangDipinjam = count($semuaPinjaman);
        $terlambat      = 0;
        foreach ($semuaPinjaman as $loan) {
            if ($now->isAfter(Time::parse($loan['due_date']))) $terlambat++;
        }
        $pinjamanAktif = array_slice($semuaPinjaman, 0, 5);

        $semuaKembali = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year, books.id as book_id')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.deleted_at', null)
            ->where('loans.return_date IS NOT NULL', null, false)
            ->orderBy('loans.return_date', 'DESC')
            ->findAll();

        $totalKembali = count($semuaKembali);
        foreach ($semuaKembali as &$ret) {
            $ret['is_late'] = Time::parse($ret['return_date'])
                ->isAfter(Time::parse($ret['due_date']));
        }
        unset($ret);

        // 5 terbaru + info kuis
        $pengembalianTerakhir = array_slice($semuaKembali, 0, 5);
        foreach ($pengembalianTerakhir as &$ret) {
            $kuisInfo = $this->getKuisInfo($ret, $member['id']);
            $ret      = array_merge($ret, $kuisInfo);
        }
        unset($ret);

        // Hitung kuis yang masih bisa dikerjakan (belum expired, belum selesai)
        $kuisBelumDikerjakan = count(array_filter($pengembalianTerakhir, function($ret) {
            return $ret['quiz_info'] !== null
                && !$ret['kuis_expired']
                && !$ret['max_habis']
                && !$ret['sudah_kuis'];
        }));

        // Poin real dari database
        $totalPoinBulanIni = $this->pointModel->getTotalPoinBulanIni($member['id']);
        $rankBulanIni      = $this->_hitungRankRealtime($member['id'], $bulanIni, $tahunIni);

        // Riwayat poin 5 terbaru untuk dashboard
        $riwayatPoin = $this->pointModel
            ->where('member_id', $member['id'])
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return view('member/dashboard', [
            'member'               => $member,
            'activeNav'            => 'dashboard',
            'sedangDipinjam'       => $sedangDipinjam,
            'terlambat'            => $terlambat,
            'totalKembali'         => $totalKembali,
            'pinjamanAktif'        => $pinjamanAktif,
            'pengembalianTerakhir' => $pengembalianTerakhir,
            'peringatan'           => $terlambat,
            'kunjunganBulanIni'    => $kunjunganBulanIni,
            'kuisBelumDikerjakan'  => $kuisBelumDikerjakan,
            'totalPoinBulanIni'    => $totalPoinBulanIni,
            'rankBulanIni'         => $rankBulanIni,
            'riwayatPoin'          => $riwayatPoin,
        ]);
    }

    private function getMember(): array|null
    {
        return $this->memberModel->where('user_id', auth()->id())->first();
    }

    public function kartu()
    {
        return view('member/kartu', [
            'member'    => $this->getMember(),
            'activeNav' => 'kartu',
        ]);
    }

    public function peminjaman()
    {
        $member = $this->getMember();
        $now    = Time::now();

        $loans = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->orderBy('loans.loan_date', 'DESC')
            ->findAll();

        $sedangDipinjam = count($loans);
        $terlambat      = 0;
        foreach ($loans as &$loan) {
            $dueDate              = Time::parse($loan['due_date']);
            $loan['is_late']      = $now->isAfter($dueDate);
            $loan['is_due_today'] = $now->toDateString() === $dueDate->toDateString();
            if ($loan['is_late']) $terlambat++;
        }
        unset($loan);

        $totalPeminjaman = $this->loanModel
            ->where('member_id', $member['id'])
            ->where('deleted_at', null)
            ->countAllResults();

        return view('member/peminjaman', [
            'member'          => $member,
            'activeNav'       => 'peminjaman',
            'loans'           => $loans,
            'sedangDipinjam'  => $sedangDipinjam,
            'totalPeminjaman' => $totalPeminjaman,
            'terlambat'       => $terlambat,
        ]);
    }

    public function pengembalian()
    {
        $member = $this->getMember();
        $now    = Time::now();

        $returns = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year, books.id as book_id, fines.fine_amount, fines.amount_paid')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.deleted_at', null)
            ->where('loans.return_date IS NOT NULL', null, false)
            ->orderBy('loans.return_date', 'DESC')
            ->findAll();

        $totalKembali = count($returns);
        $tepatWaktu   = 0;
        $terlambat    = 0;

        foreach ($returns as &$ret) {
            $dueDate    = Time::parse($ret['due_date']);
            $returnDate = Time::parse($ret['return_date']);
            $isLate     = $returnDate->isAfter($dueDate);

            $ret['is_late']   = $isLate;
            $ret['days_late'] = $isLate
                ? abs($returnDate->difference($dueDate)->getDays())
                : 0;

            if ($isLate) $terlambat++; else $tepatWaktu++;

            // Gunakan helper getKuisInfo
            $kuisInfo = $this->getKuisInfo($ret, $member['id']);
            $ret      = array_merge($ret, $kuisInfo);
        }
        unset($ret);

        return view('member/pengembalian', [
            'member'       => $member,
            'activeNav'    => 'pengembalian',
            'returns'      => $returns,
            'totalKembali' => $totalKembali,
            'tepatWaktu'   => $tepatWaktu,
            'terlambat'    => $terlambat,
        ]);
    }

    public function kuis($quizId = null)
    {
        $member = $this->getMember();
        $loanId = (int) $this->request->getGet('loan_id');

        $quiz = $this->quizModel
            ->select('quizzes.*, books.title as book_title')
            ->join('books', 'quizzes.book_id = books.id', 'LEFT')
            ->where('quizzes.id', $quizId)
            ->where('quizzes.is_active', 1)
            ->first();

        if (empty($quiz)) {
            session()->setFlashdata(['msg' => 'Kuis tidak ditemukan atau tidak aktif.', 'error' => true]);
            return redirect()->to('member/pengembalian');
        }

        // Cek expired lewat loan
        if ($loanId) {
            $loan = $this->loanModel->find($loanId);
            if ($loan && !empty($loan['return_date'])) {
                $selisihJam = abs(Time::now()->getTimestamp() - Time::parse($loan['return_date'])->getTimestamp()) / 3600;
                if ($selisihJam > 24) {
                    session()->setFlashdata(['msg' => 'Waktu pengerjaan kuis sudah habis (lebih dari 24 jam sejak pengembalian).', 'error' => true]);
                    return redirect()->to('member/pengembalian');
                }
            }
        }

        $attemptsLoan = $loanId
            ? $this->attemptModel
                ->where('quiz_id', $quizId)
                ->where('member_id', $member['id'])
                ->where('loan_id', $loanId)
                ->countAllResults()
            : 0;

        if ($attemptsLoan >= $quiz['max_attempts']) {
            session()->setFlashdata(['msg' => 'Batas percobaan kuis sudah habis.', 'error' => true]);
            return redirect()->to('member/pengembalian');
        }

        $questions = $this->questionModel
            ->where('quiz_id', $quizId)
            ->orderBy('id', 'ASC')
            ->findAll();

        if (empty($questions)) {
            session()->setFlashdata(['msg' => 'Kuis ini belum memiliki soal.', 'error' => true]);
            return redirect()->to('member/pengembalian');
        }

        return view('member/kuis', [
            'member'    => $member,
            'activeNav' => 'pengembalian',
            'quiz'      => $quiz,
            'questions' => $questions,
            'loanId'    => $loanId,
        ]);
    }

    public function submitKuis($quizId = null)
    {
        $member = $this->getMember();

        $quiz = $this->quizModel->find($quizId);
        if (empty($quiz)) {
            return $this->response->setJSON(['error' => 'Kuis tidak ditemukan']);
        }

        $questions   = $this->questionModel->where('quiz_id', $quizId)->findAll();
        $jawaban     = $this->request->getPost('jawaban') ?? [];
        $durasiDetik = (int) $this->request->getPost('durasi_detik');
        $loanId      = (int) $this->request->getPost('loan_id');

        $benar = 0;
        $salah = 0;
        $total = count($questions);

        foreach ($questions as $q) {
            $jawabanMember = $jawaban[$q['id']] ?? null;
            if ($jawabanMember === $q['correct_answer']) {
                $benar++;
            } else {
                $salah++;
            }
        }

        $skor = $total > 0 ? round($benar / $total * 100) : 0;
        $poin = $skor;

        // ── Ambil total poin sebelum submit ──────────────────
        $totalSebelum = (int) ($this->pointModel
            ->selectSum('points')
            ->where('member_id', $member['id'])
            ->first()['points'] ?? 0);

        $this->attemptModel->insert([
            'quiz_id'     => $quizId,
            'member_id'   => $member['id'],
            'loan_id'     => $loanId ?: null,
            'score'       => $poin,
            'total'       => $total,
            'started_at'  => Time::now()->subSeconds($durasiDetik)->toDateTimeString(),
            'finished_at' => Time::now()->toDateTimeString(),
        ]);
        $attemptId = $this->attemptModel->getInsertID();

        // ── Catat poin kuis ──────────────────────────────────
        if ($poin > 0) {
            catat_poin(
                $member['id'],
                'quiz',
                $poin,
                'Kuis: ' . ($quiz['name'] ?? 'Kuis Buku') . ' — Skor ' . $skor . '%',
                $attemptId,
                'quiz_attempt'
            );
        }

        $totalSesudah = $totalSebelum + $poin;

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'poin'          => $poin,
                'benar'         => $benar,
                'salah'         => $salah,
                'total'         => $total,
                'skor'          => $skor,
                'total_sebelum' => $totalSebelum,
                'total_sesudah' => $totalSesudah,
            ]);
        }

        session()->setFlashdata(['msg' => "Kuis selesai! Kamu mendapat {$poin} poin."]);
        return redirect()->to('member/pengembalian');
    }
    public function leaderboard()
    {
        $member   = $this->getMember();
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        // Bulan yang dipilih (default bulan ini)
        $bulan = (int) ($this->request->getGet('bulan') ?? $bulanIni);
        $tahun = (int) ($this->request->getGet('tahun') ?? $tahunIni);

        if ($bulan === $bulanIni && $tahun === $tahunIni) {
            // Bulan berjalan — kalkulasi real-time
            $leaderboard = $this->_getLeaderboardRealtime($bulan, $tahun);
        } else {
            // Bulan lalu — pakai lazy snapshot
            if (!$this->leaderboardModel->sudahAda($bulan, $tahun)) {
                $this->leaderboardModel->buatSnapshot($bulan, $tahun);
            }
            $leaderboard = $this->leaderboardModel->getLeaderboard($bulan, $tahun);
        }

        // Rank member yang login di bulan yang dipilih
        $rankSaya = 0;
        foreach ($leaderboard as $i => $row) {
            if ($row['member_id'] == $member['id']) {
                $rankSaya = $i + 1;
                break;
            }
        }

        // Daftar bulan untuk dropdown (6 bulan ke belakang)
        $daftarBulan = [];
        for ($i = 0; $i < 6; $i++) {
            $ts = mktime(0, 0, 0, $bulanIni - $i, 1, $tahunIni);
            $daftarBulan[] = [
                'bulan' => (int) date('n', $ts),
                'tahun' => (int) date('Y', $ts),
                'label' => strftime('%B %Y', $ts),
            ];
        }

        return view('member/leaderboard', [
            'member'       => $member,
            'activeNav'    => 'leaderboard',
            'leaderboard'  => $leaderboard,
            'rankSaya'     => $rankSaya,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'daftarBulan'  => $daftarBulan,
            'bulanIni'     => $bulanIni,
            'tahunIni'     => $tahunIni,
        ]);
    }

    // ── Helper: hitung leaderboard real-time bulan ini ───────
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
            'last_name'       => $m['last_name'],
            'no_identitas'    => $m['no_identitas'],
            'tipe_anggota'    => $m['tipe_anggota'],
            'foto_profil'     => $m['foto_profil'],

            // 🔥 TAMBAHAN
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

    // ── Helper: hitung rank member real-time ─────────────────
    private function _hitungRankRealtime(int $memberId, int $bulan, int $tahun): int
    {
        $leaderboard = $this->_getLeaderboardRealtime($bulan, $tahun);
        foreach ($leaderboard as $i => $row) {
            if ($row['member_id'] == $memberId) return $i + 1;
        }
        return 0;
    }

    public function poin()
    {
        $member   = $this->getMember();
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        // Riwayat poin dengan pagination
        $riwayat = $this->pointModel
            ->where('member_id', $member['id'])
            ->orderBy('created_at', 'DESC')
            ->paginate(15, 'poin');

        $pager = $this->pointModel->pager;

        // Total poin bulan ini
        $totalBulanIni = $this->pointModel->getTotalPoinBulanIni($member['id']);

        // Total poin all time
        $totalAllTime = $this->pointModel->getTotalPoinAllTime($member['id']);

        // Rank bulan ini
        $rankBulanIni = $this->_hitungRankRealtime($member['id'], $bulanIni, $tahunIni);

        // Chart: poin per bulan (6 bulan terakhir)
        $db = \Config\Database::connect();
        $chartPoin = [];
        $namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        for ($i = 5; $i >= 0; $i--) {
            $ts    = mktime(0, 0, 0, $bulanIni - $i, 1, $tahunIni);
            $bln   = (int) date('n', $ts);
            $thn   = (int) date('Y', $ts);
            $total = $db->table('point_transactions')
                ->selectSum('points')
                ->where('member_id', $member['id'])
                ->where('MONTH(created_at)', $bln)
                ->where('YEAR(created_at)',  $thn)
                ->get()->getRow()->points ?? 0;
            $chartPoin[] = [
                'bulan' => $namaBulan[$bln] . ' ' . $thn,
                'total' => (int) $total,
            ];
        }

        // Breakdown poin per aktivitas (bulan ini)
        $poinPerAktivitas = [];
        $tipes = ['visit', 'loan', 'return_ontime', 'return_late', 'quiz'];
        foreach ($tipes as $tipe) {
            $hasil = $db->table('point_transactions')
                ->selectSum('points')
                ->where('member_id', $member['id'])
                ->where('activity_type', $tipe)
                ->where('MONTH(created_at)', $bulanIni)
                ->where('YEAR(created_at)',  $tahunIni)
                ->get()->getRow()->points ?? 0;
            $poinPerAktivitas[$tipe] = (int) $hasil;
        }

        return view('member/poin', [
            'member'           => $member,
            'activeNav'        => 'poin',
            'riwayat'          => $riwayat,
            'pager'            => $pager,
            'totalBulanIni'    => $totalBulanIni,
            'totalAllTime'     => $totalAllTime,
            'rankBulanIni'     => $rankBulanIni,
            'chartPoin'        => $chartPoin,
            'poinPerAktivitas' => $poinPerAktivitas,
        ]);
    }
    public function notifikasi()
    {
        $member = $this->getMember();

        $notifikasi = $this->pointModel
            ->where('member_id', $member['id'])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON($notifikasi);
    }
    public function kunjungan()
    {
        $member   = $this->getMember();
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $visits = (new VisitModel())
            ->where('member_id', $member['id'])
            ->orderBy('visit_date', 'DESC')
            ->findAll();

        $kunjunganBulanIni = count(array_filter($visits, function($v) use ($bulanIni, $tahunIni) {
            $d = Time::parse($v['visit_date']);
            return (int)$d->format('n') === $bulanIni && (int)$d->format('Y') === $tahunIni;
        }));

        return view('member/kunjungan', [
            'member'            => $member,
            'activeNav'         => 'kunjungan',
            'visits'            => $visits,
            'totalKunjungan'    => count($visits),
            'kunjunganBulanIni' => $kunjunganBulanIni,
        ]);
    }

    public function daftarbuku()
    {
        $itemPerPage = 12;
        $search      = $this->request->getGet('search');
        $categoryId  = $this->request->getGet('category');

        $query = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, categories.id as category_id_val')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT');

        if ($search) {
            $query->groupStart()
                ->like('books.title',       $search, insensitiveSearch: true)
                ->orLike('books.author',    $search, insensitiveSearch: true)
                ->orLike('books.publisher', $search, insensitiveSearch: true)
                ->groupEnd();
        }

        if ($categoryId) $query->where('books.category_id', $categoryId);

        $books      = $query->paginate($itemPerPage, 'books');
        $categories = $this->categoryModel->findAll();

        return view('member/daftarbuku', [
            'member'      => $this->getMember(),
            'activeNav'   => 'daftarbuku',
            'books'       => $books,
            'pager'       => $this->bookModel->pager,
            'categories'  => $categories,
            'search'      => $search,
            'categoryId'  => $categoryId,
            'currentPage' => $this->request->getGet('page_books') ?? 1,
            'itemPerPage' => $itemPerPage,
        ]);
    }

    public function detailBuku($slug = null)
    {
        $book = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->where('books.slug', $slug)
            ->first();

        if (empty($book)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak ditemukan');
        }

        return view('member/detail_buku', [
            'member'    => $this->getMember(),
            'activeNav' => 'daftarbuku',
            'book'      => $book,
        ]);
    }
}