<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Masuk',
        ]);
    }

    public function attempt()
    {
        $rules = [
            'username' => 'required|min_length[2]|max_length[50]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $model = new PenggunaModel();
        $user  = $model->findByUsername($username);

        if (! $user || ! (bool) ($user['status_aktif'] ?? 1) || ! $model->verifyPassword($password, $user)) {
            return redirect()->back()->withInput()->with('error', 'Username atau kata sandi salah, atau akun tidak aktif.');
        }

        session()->set([
            'user_id'    => $user['id_pengguna'],
            'user_name'  => $user['nama_pengguna'],
            'user_role'  => $user['role'],
            'username'   => $user['username'],
        ]);

        return redirect()->to('/dashboard')->with('message', 'Selamat datang, ' . $user['nama_pengguna'] . '.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Anda telah keluar.');
    }
}
