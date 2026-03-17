<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class LoginController extends BaseController
{
    public function loginAction()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        // Validasi input kosong
        if (empty($username) || empty($password)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['No. Identitas dan Password wajib diisi.']);
        }

        // Attempt login dengan username
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        $result = auth('session')->attempt($credentials, $remember);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No. Identitas atau Password salah.');
        }

        // Redirect sesuai group
        $user = auth()->user();

        if ($user->inGroup('member')) {
            return redirect()->to('/member/dashboard');
        }

        return redirect()->to('/admin/dashboard');
    }
}