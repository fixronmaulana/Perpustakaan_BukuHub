<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filter MemberFilter
 * Pastikan yang akses /member/* hanya user dengan group 'member'
 * Taruh di: app/Filters/MemberFilter.php
 */
class MemberFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Belum login → redirect ke login
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // Sudah login tapi bukan group member → tolak
        if (!auth()->user()->inGroup('member')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak ada aksi after
    }
}