<?php

namespace App\Controllers\Member;

use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\MemberModel;
use App\Models\LoanModel;
use App\Models\FineModel;
use CodeIgniter\Controller;

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
                  ->like('books.title',      $search, insensitiveSearch: true)
                  ->orLike('books.author',   $search, insensitiveSearch: true)
                  ->orLike('books.publisher',$search, insensitiveSearch: true)
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

    // ══════════════════════════════════════════════════════
    // PROFIL
    // ══════════════════════════════════════════════════════

    public function profil()
    {
        return view('member/profil', [
            'member'    => $this->getMember(),
            'activeNav' => 'profil',
            'success'   => session()->getFlashdata('success'),
            'error'     => session()->getFlashdata('error'),
            'errors'    => session()->getFlashdata('errors')    ?? [],
            'errors_pw' => session()->getFlashdata('errors_pw') ?? [],
            'tab_aktif' => session()->getFlashdata('tab_aktif') ?? 'profil',
        ]);
    }

    public function updateProfil()
    {
        $member = $this->getMember();
        if (! $member) {
            return redirect()->to(base_url('member/dashboard'));
        }

        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[100]',
            'last_name'     => 'permit_empty|max_length[100]',
            'phone'         => 'permit_empty|max_length[20]',
            'gender'        => 'permit_empty|in_list[Male,Female]',
            'foto_profil'   => [
                'label' => 'Foto Profil',
                'rules' => 'permit_empty|is_image[foto_profil]|max_size[foto_profil,2048]|mime_in[foto_profil,image/jpg,image/jpeg,image/png,image/webp]',
            ],
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('error', 'Periksa kembali data yang Anda isi.');
            return redirect()->to(base_url('member/profil'))->withInput();
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name')  ?? '',
            'phone'      => $this->request->getPost('phone')      ?? '',
            'gender'     => $this->request->getPost('gender')     ?? '',
        ];

        // Handle upload foto profil
        $foto = $this->request->getFile('foto_profil');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/foto_profil/';

            // Hapus foto lama jika ada
            if (! empty($member['foto_profil'])) {
                $fotoLama = $uploadPath . $member['foto_profil'];
                if (file_exists($fotoLama)) {
                    unlink($fotoLama);
                }
            }

            $namaFile = 'member_' . $member['id'] . '_' . time() . '.' . $foto->getExtension();
            $foto->move($uploadPath, $namaFile);
            $data['foto_profil'] = $namaFile;
        }

        $this->memberModel->update($member['id'], $data);

        session()->setFlashdata('success', 'Profil berhasil diperbarui.');
        return redirect()->to(base_url('member/profil'));
    }

    public function updatePassword()
    {
        $member = $this->getMember();
        if (! $member) {
            return redirect()->to(base_url('member/dashboard'));
        }

        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[8]',
            'konfirmasi'    => 'required|matches[password_baru]',
        ];

        $messages = [
            'password_baru' => ['min_length' => 'Password baru minimal 8 karakter.'],
            'konfirmasi'    => ['matches'    => 'Konfirmasi password tidak cocok.'],
        ];

        if (! $this->validate($rules, $messages)) {
            session()->setFlashdata('errors_pw', $this->validator->getErrors());
            session()->setFlashdata('error', 'Periksa kembali form password.');
            session()->setFlashdata('tab_aktif', 'password');
            return redirect()->to(base_url('member/profil'));
        }

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');

        // Verifikasi password lama lewat Shield
        $users      = auth()->getProvider();
        $userShield = $users->findById(auth()->id());
        $passwords  = service('passwords');

        if (! $passwords->verify($passwordLama, $userShield->password_hash)) {
            session()->setFlashdata('errors_pw', ['password_lama' => 'Password lama tidak sesuai.']);
            session()->setFlashdata('error', 'Password lama yang Anda masukkan salah.');
            session()->setFlashdata('tab_aktif', 'password');
            return redirect()->to(base_url('member/profil'));
        }

        // Simpan password baru ke Shield
        $userShield->fill(['password' => $passwordBaru]);
        $users->save($userShield);

        session()->setFlashdata('success', 'Password berhasil diubah.');
        return redirect()->to(base_url('member/profil'));
    }
}