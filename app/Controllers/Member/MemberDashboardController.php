<?php

namespace App\Controllers\Member;

use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\MemberModel;
use App\Models\LoanModel;
use App\Models\FineModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class MemberDashboardController extends Controller
{
    protected MemberModel   $memberModel;
    protected LoanModel     $loanModel;
    protected FineModel     $fineModel;
    protected BookModel     $bookModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->memberModel   = new MemberModel();
        $this->loanModel     = new LoanModel();
        $this->fineModel     = new FineModel();
        $this->bookModel     = new BookModel();
        $this->categoryModel = new CategoryModel();
    }

    private function getMember(): array|null
    {
        return $this->memberModel->where('user_id', auth()->id())->first();
    }

    public function index()
    {
        return view('member/dashboard', [
            'member'    => $this->getMember(),
            'activeNav' => 'dashboard',
        ]);
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

        // Ambil semua peminjaman aktif member ini
        $loans = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.return_date', null)
            ->where('loans.deleted_at', null)
            ->orderBy('loans.loan_date', 'DESC')
            ->findAll();

        // Hitung statistik
        $sedangDipinjam = count($loans);
        $terlambat      = 0;

        foreach ($loans as &$loan) {
            $dueDate       = Time::parse($loan['due_date']);
            $loan['is_late']     = $now->isAfter($dueDate);
            $loan['is_due_today'] = $now->toDateString() === $dueDate->toDateString();
            if ($loan['is_late']) $terlambat++;
        }
        unset($loan);

        // Total semua peminjaman (termasuk yang sudah kembali)
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

        // Ambil semua peminjaman yang sudah dikembalikan
        $returns = $this->loanModel
            ->select('loans.*, books.title, books.author, books.year, fines.fine_amount, fines.amount_paid')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->where('loans.member_id', $member['id'])
            ->where('loans.deleted_at', null)
            ->where('loans.return_date IS NOT NULL', null, false)
            ->orderBy('loans.return_date', 'DESC')
            ->findAll();

        // Hitung statistik
        $totalKembali = count($returns);
        $tepatWaktu   = 0;
        $terlambat    = 0;

        foreach ($returns as &$ret) {
            $dueDate    = Time::parse($ret['due_date']);
            $returnDate = Time::parse($ret['return_date']);
            $isLate     = $returnDate->isAfter($dueDate);
            $ret['is_late']    = $isLate;
            $ret['days_late']  = $isLate
                ? abs($returnDate->difference($dueDate)->getDays())
                : 0;
            if ($isLate) $terlambat++; else $tepatWaktu++;
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
        return view('member/kunjungan', [
            'member'    => $this->getMember(),
            'activeNav' => 'kunjungan',
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
}