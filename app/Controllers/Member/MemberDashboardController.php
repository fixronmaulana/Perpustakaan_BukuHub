<?php

namespace App\Controllers\Member;

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
    protected MemberModel $memberModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel;
        $this->loanModel   = new LoanModel;
        $this->fineModel   = new FineModel;
    }

    /**
     * Ambil data member yang sedang login
     */
    private function getMember(): array|null
    {
        $userId = auth()->id();
        return $this->memberModel->where('user_id', $userId)->first();
    }

    public function index()
    {
        $member = $this->getMember();

        // Nanti: ambil data asli dari DB
        // $loans   = $this->loanModel->where('member_id', $member['id'])->findAll();
        // dst...

        return view('member/dashboard', [
            'member'    => $member,
            'activeNav' => 'dashboard',
        ]);
    }

    public function peminjaman()
    {
        $member = $this->getMember();

        return view('member/peminjaman', [
            'member'    => $member,
            'activeNav' => 'peminjaman',
        ]);
    }

    public function pengembalian()
    {
        $member = $this->getMember();

        return view('member/pengembalian', [
            'member'    => $member,
            'activeNav' => 'pengembalian',
        ]);
    }

    public function denda()
    {
        $member = $this->getMember();

        return view('member/denda', [
            'member'    => $member,
            'activeNav' => 'denda',
        ]);
    }

    public function poin()
    {
        $member = $this->getMember();

        return view('member/poin', [
            'member'    => $member,
            'activeNav' => 'poin',
        ]);
    }

    public function profil()
    {
        $member = $this->getMember();

        return view('member/profil', [
            'member'    => $member,
            'activeNav' => 'profil',
        ]);
    }
}