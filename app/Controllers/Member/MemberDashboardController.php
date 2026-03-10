<?php

namespace App\Controllers\Member;

use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\MemberModel;
use App\Models\LoanModel;
use App\Models\FineModel;
use CodeIgniter\Controller;

/**
 * MemberDashboardController
 * Taruh di: app/Controllers/Member/MemberDashboardController.php
 */
class MemberDashboardController extends Controller
{
    protected MemberModel   $memberModel;
    protected LoanModel     $loanModel;
    protected FineModel     $fineModel;
    protected BookModel     $bookModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->memberModel   = new MemberModel;
        $this->loanModel     = new LoanModel;
        $this->fineModel     = new FineModel;
        $this->bookModel     = new BookModel;
        $this->categoryModel = new CategoryModel;
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
        return view('member/peminjaman', [
            'member'    => $this->getMember(),
            'activeNav' => 'peminjaman',
        ]);
    }

    public function pengembalian()
    {
        return view('member/pengembalian', [
            'member'    => $this->getMember(),
            'activeNav' => 'pengembalian',
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

    // ─────────────────────────────────────────────
    // DAFTAR BUKU — data real dari database
    // ─────────────────────────────────────────────
    public function daftarbuku()
    {
        $itemPerPage = 12; // 12 kartu per halaman (grid 4 kolom × 3 baris)
        $search      = $this->request->getGet('search');
        $categoryId  = $this->request->getGet('category');

        $query = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, categories.id as category_id_val')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT');

        if ($search) {
            $query->groupStart()
                  ->like('books.title',     $search, insensitiveSearch: true)
                  ->orLike('books.author',  $search, insensitiveSearch: true)
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

    public function profil()
    {
        return view('member/profil', [
            'member'    => $this->getMember(),
            'activeNav' => 'profil',
        ]);
    }
}