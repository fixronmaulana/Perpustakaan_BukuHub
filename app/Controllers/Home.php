<?php

namespace App\Controllers;

use App\Models\BookModel;

class Home extends BaseController
{
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel;
    }

    public function index(): string
    {
        return view('home/home', ['activeNav' => 'beranda']);
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

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });
        } else {
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->paginate($itemPerPage, 'books');
        }

        $data = [
            'books'       => $books,
            'pager'       => $this->bookModel->pager,
            'currentPage' => $this->request->getVar('page_books') ?? 1,
            'itemPerPage' => $itemPerPage,
            'search'      => $this->request->getGet('search'),
            'activeNav'   => 'koleksi',
        ];

        return view('home/book', $data);
    }

    public function layanan(): string
    {
        return view('home/layanan', ['activeNav' => 'layanan']);
    }
    public function leaderboard(): string
    {
        // Nanti $data diisi dari model gamifikasi
        // contoh: $data['leaderboard'] = $this->gamifikasiModel->getLeaderboardBulanan();
        return view('home/leaderboard', ['activeNav' => 'leaderboard']);
    }
    public function kontak(): string
    {
        return view('home/kontak', ['activeNav' => 'kontak']);
    }

    // Nanti: handle POST form kontak
    // public function kontakKirim(): RedirectResponse
    // {
    //     // validasi + simpan / kirim email
    //     return redirect()->to('kontak')->with('sukses', 'Pesan berhasil dikirim!');
    // }
}