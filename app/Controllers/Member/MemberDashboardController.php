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
    protected QuizAttemptModel  $attemptModel;

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
        $this->attemptModel  = new QuizAttemptModel();
    }

    public function index()
    {
        $member = $this->getMember();
        $now    = Time::now();

        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $visits = $this->visitModel
            ->where('member_id', $member['id'])
            ->findAll();

        $kunjunganBulanIni = count(array_filter($visits, function($v) use ($bulanIni, $tahunIni) {
            $d = \CodeIgniter\I18n\Time::parse($v['visit_date']);
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
            ->select('loans.*, books.title, books.author, books.year')
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

        // Tambah info kuis untuk 5 pengembalian terakhir di dashboard
        $pengembalianTerakhir = array_slice($semuaKembali, 0, 5);
        foreach ($pengembalianTerakhir as &$ret) {
            $quiz = $this->quizModel
                ->where('book_id', $ret['book_id'])
                ->where('is_active', 1)
                ->first();
            if (!$quiz) {
                $ret['quiz_info']  = null;
                $ret['sudah_kuis'] = false;
                $ret['max_habis']  = false;
            } else {
                $attempts = $this->attemptModel
                    ->where('quiz_id', $quiz['id'])
                    ->where('member_id', $member['id'])
                    ->countAllResults();
                $ret['quiz_info']  = $quiz;
                $ret['sudah_kuis'] = $attempts > 0;
                $ret['max_habis']  = $attempts >= $quiz['max_attempts'];
            }
        }
        unset($ret);

        // Hitung kuis yang belum dikerjakan dari pengembalian terakhir
        $kuisBelumDikerjakan = count(array_filter($pengembalianTerakhir, function($ret) {
            return $ret['quiz_info'] !== null && !$ret['max_habis'] && !$ret['sudah_kuis'];
        }));

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

    // ── Pengembalian — ditambah info kuis per buku ───────────
    public function pengembalian()
    {
        $member = $this->getMember();

        $returns = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year, books.id as book_id_val, fines.fine_amount, fines.amount_paid')
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

            // ── Cek kuis untuk buku ini ──────────────────────
            $quiz = $this->quizModel
                ->where('book_id', $ret['book_id'])
                ->where('is_active', 1)
                ->first();

            if (!$quiz) {
                $ret['quiz_info']  = null;
                $ret['sudah_kuis'] = false;
                $ret['max_habis']  = false;
            } else {
                $attempts = $this->attemptModel
                    ->where('quiz_id', $quiz['id'])
                    ->where('member_id', $member['id'])
                    ->countAllResults();

                $ret['quiz_info']  = $quiz;
                $ret['sudah_kuis'] = $attempts > 0;
                $ret['max_habis']  = $attempts >= $quiz['max_attempts'];
            }
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

    // ── Halaman kerjakan kuis ────────────────────────────────
    public function kuis($quizId = null)
    {
        $member = $this->getMember();

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

        // Cek batas percobaan
        $attempts = $this->attemptModel
            ->where('quiz_id', $quizId)
            ->where('member_id', $member['id'])
            ->countAllResults();

        if ($attempts >= $quiz['max_attempts']) {
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
        ]);
    }

    // ── Submit jawaban kuis ──────────────────────────────────
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

        $poin  = 0;
        $benar = 0;
        $salah = 0;
        $total = count($questions);

        foreach ($questions as $q) {
            $jawabanMember = $jawaban[$q['id']] ?? null;
            if ($jawabanMember === $q['correct_answer']) {
                $benar++;
                $poin += $q['points'];
            } else {
                $salah++;
            }
        }

        $skor = $total > 0 ? round($benar / $total * 100) : 0;

        // Simpan attempt
        $this->attemptModel->insert([
            'quiz_id'     => $quizId,
            'member_id'   => $member['id'],
            'score'       => $poin,
            'total'       => $total,
            'started_at'  => Time::now()->subSeconds($durasiDetik)->toDateTimeString(),
            'finished_at' => Time::now()->toDateTimeString(),
        ]);

        // Kembalikan JSON jika AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'poin'  => $poin,
                'benar' => $benar,
                'salah' => $salah,
                'total' => $total,
                'skor'  => $skor,
            ]);
        }

        // Fallback jika bukan AJAX
        session()->setFlashdata(['msg' => "Kuis selesai! Kamu mendapat {$poin} poin."]);
        return redirect()->to('member/pengembalian');
    }

    public function leaderboard()
    {
        return view('member/leaderboard', [
            'member'    => $this->getMember(),
            'activeNav' => 'leaderboard',
        ]);
    }

    public function poin()
    {
        return view('member/poin', [
            'member'    => $this->getMember(),
            'activeNav' => 'poin',
        ]);
    }

    public function kunjungan()
    {
        $member    = $this->getMember();
        $bulanIni  = (int) date('n');
        $tahunIni  = (int) date('Y');

        $visits = (new VisitModel())
            ->where('member_id', $member['id'])
            ->orderBy('visit_date', 'DESC')
            ->findAll();

        $kunjunganBulanIni = count(array_filter($visits, function($v) use ($bulanIni, $tahunIni) {
            $d = \CodeIgniter\I18n\Time::parse($v['visit_date']);
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

        if ($categoryId) {
            $query->where('books.category_id', $categoryId);
        }

        $books      = $query->paginate($itemPerPage, 'books');
        $pager      = $this->bookModel->pager;
        $categories = $this->categoryModel->findAll();

        return view('member/daftarbuku', [
            'member'      => $this->getMember(),
            'activeNav'   => 'daftarbuku',
            'books'       => $books,
            'pager'       => $pager,
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