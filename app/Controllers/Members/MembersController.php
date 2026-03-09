<?php

namespace App\Controllers\Members;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\BookStockModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class MembersController extends ResourceController
{
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected BookStockModel $bookStockModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->memberModel    = new MemberModel;
        $this->bookModel      = new BookModel;
        $this->bookStockModel = new BookStockModel;
        $this->loanModel      = new LoanModel;
        $this->fineModel      = new FineModel;
        $this->userModel      = new UserModel;

        helper('upload');
    }

    public function index()
    {
        $itemPerPage = 20;

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $members = $this->memberModel
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'members');

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });
        } else {
            $members = $this->memberModel->paginate($itemPerPage, 'members');
        }

        $data = [
            'members'     => $members,
            'pager'       => $this->memberModel->pager,
            'currentPage' => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage' => $itemPerPage,
            'search'      => $this->request->getGet('search'),
        ];

        return view('members/index', $data);
    }

    public function show($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        $loans = $this->loanModel->where([
            'member_id'   => $member['id'],
            'return_date' => null,
        ])->findAll();

        $fines = $this->loanModel
            ->select('loans.id, fines.amount_paid, fines.fine_amount, fines.paid_at')
            ->join('fines', 'loans.id=fines.loan_id', 'LEFT')
            ->where('member_id', $member['id'])->findAll();

        $totakBooksLent = empty($loans) ? 0 : array_reduce(
            array_map(fn($loan) => $loan['quantity'], $loans),
            fn($carry, $item) => $carry + $item
        );

        $return = array_filter($loans, fn($loan) => $loan['return_date'] != null);

        $lateLoans = array_filter($loans, fn($loan) =>
            $loan['return_date'] == null && Time::now()->isAfter(Time::parse($loan['due_date']))
        );

        $totalFines = array_reduce(
            array_map(fn($fine) => $fine['fine_amount'], $fines),
            fn($carry, $item) => $carry + $item
        );

        $paidFines = array_reduce(
            array_map(fn($fine) => $fine['amount_paid'], $fines),
            fn($carry, $item) => $carry + $item
        );

        $unpaidFines = $totalFines - $paidFines;

        if (!file_exists(MEMBERS_QR_CODE_PATH . $member['qr_code']) || empty($member['qr_code'])) {
            $qrGenerator  = new QRGenerator();
            $qrCodeLabel  = $member['first_name'] . ($member['last_name'] ? ' ' . $member['last_name'] : '');
            $qrCode       = $qrGenerator->generateQRCode(
                $member['uid'],
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            $this->memberModel->update($member['id'], ['qr_code' => $qrCode]);
            $member = $this->memberModel->where('uid', $uid)->first();
        }

        $data = [
            'member'         => $member,
            'totalBooksLent' => $totakBooksLent,
            'loanCount'      => count($loans),
            'returnCount'    => count($return),
            'lateCount'      => count($lateLoans),
            'unpaidFines'    => $unpaidFines,
            'paidFines'      => $paidFines,
        ];

        return view('members/show', $data);
    }

    public function new()
    {
        return view('members/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function create()
    {
        if (!$this->validate([
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ])) {
            return view('members/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $firstName = $this->request->getVar('first_name');
        $lastName  = $this->request->getVar('last_name');
        $email     = $this->request->getVar('email');
        $phone     = $this->request->getVar('phone');
        $gender    = $this->request->getVar('gender');

        // ── Buat akun Shield untuk anggota ──────────────────────────
        $shieldUser = new User([
            'username' => $email,           // username = email
            'email'    => $email,
            'password' => $phone,           // password sementara = nomor telepon
        ]);

        $this->userModel->save($shieldUser);
        $shieldUser = $this->userModel->findById($this->userModel->getInsertID());
        $shieldUser->addGroup('member');    // tambah ke group member
        $shieldUser->activate();
        $userId = $shieldUser->id;
        // ────────────────────────────────────────────────────────────

        $uid = sha1($firstName . $email . $phone . rand(0, 1000) . md5($gender));

        $qrGenerator = new QRGenerator();
        $qrCodeLabel = $firstName . ($lastName ? ' ' . $lastName : '');
        $qrCode      = $qrGenerator->generateQRCode(
            data: $uid,
            labelText: $qrCodeLabel,
            dir: MEMBERS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->memberModel->save([
            'uid'           => $uid,
            'user_id'       => $userId,     // ← simpan link ke Shield user
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $email,
            'phone'         => $phone,
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $gender,
            'qr_code'       => $qrCode,
        ])) {
            // Rollback: hapus Shield user yang sudah dibuat
            $this->userModel->delete($userId, purge: true);

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('members/create', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        session()->setFlashdata(['msg' => 'Insert new member successful']);
        return redirect()->to('admin/members');
    }

    public function edit($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        return view('members/edit', [
            'member'     => $member,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        if (!$this->validate([
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ])) {
            return view('members/edit', [
                'member'     => $member,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        $firstName = $this->request->getVar('first_name');
        $email     = $this->request->getVar('email');
        $phone     = $this->request->getVar('phone');
        $gender    = $this->request->getVar('gender');
        $lastName  = $this->request->getVar('last_name');

        $isChanged = ($firstName != $member['first_name']
            || $email != $member['email']
            || $phone != $member['phone']);

        $newUid = $isChanged
            ? sha1($firstName . $email . $phone . rand(0, 1000) . md5($gender))
            : $member['uid'];

        if ($isChanged) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $firstName . ($lastName ? ' ' . $lastName : '');
            $qrCode      = $qrGenerator->generateQRCode(
                $newUid,
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );
            deleteMembersQRCode($member['qr_code']);

            // ── Update akun Shield jika email/phone berubah ──────────
            if (!empty($member['user_id'])) {
                $shieldUser = $this->userModel->findById($member['user_id']);
                if ($shieldUser) {
                    $shieldUser->fill([
                        'username' => $email,
                        'email'    => $email,
                        'password' => $phone,
                    ]);
                    $this->userModel->save($shieldUser);
                }
            }
            // ────────────────────────────────────────────────────────
        } else {
            $qrCode = $member['qr_code'];
        }

        if (!$this->memberModel->save([
            'id'            => $member['id'],
            'uid'           => $newUid,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $email,
            'phone'         => $phone,
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $gender,
            'qr_code'       => $qrCode,
        ])) {
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('members/edit', [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        session()->setFlashdata(['msg' => 'Update member successful']);
        return redirect()->to('admin/members');
    }

    public function delete($uid = null)
    {
        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        // ── Hapus akun Shield anggota sekaligus ──────────────────────
        if (!empty($member['user_id'])) {
            $this->userModel->delete($member['user_id'], purge: true);
        }
        // ────────────────────────────────────────────────────────────

        if (!$this->memberModel->delete($member['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete member', 'error' => true]);
            return redirect()->back();
        }

        deleteMembersQRCode($member['qr_code']);

        session()->setFlashdata(['msg' => 'Member deleted successfully']);
        return redirect()->to('admin/members');
    }
}