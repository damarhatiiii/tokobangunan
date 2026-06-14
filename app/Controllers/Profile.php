<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class Profile extends BaseController
{
    public function index()
    {
        $model = new PenggunaModel();
        $user  = $model->find((int) session()->get('user_id'));
        if (! $user) {
            return redirect()->to('/logout');
        }

        return view('profile/index', [
            'title' => 'Profil',
            'user'  => $user,
        ]);
    }

    public function update()
    {
        $userId = (int) session()->get('user_id');
        $model  = new PenggunaModel();
        $user   = $model->find($userId);
        if (! $user) {
            return redirect()->to('/logout');
        }

        $nama     = (string) $this->request->getPost('nama_pengguna');
        $username = trim((string) $this->request->getPost('username'));
        $pass     = (string) $this->request->getPost('password');
        $pass2    = (string) $this->request->getPost('password_confirm');

        $rules = [
            'nama_pengguna' => 'required|min_length[2]|max_length[100]',
            'username'      => "required|min_length[3]|max_length[50]|is_unique[pengguna.username,id_pengguna,{$userId}]",
        ];

        if ($pass !== '' || $pass2 !== '') {
            $rules['password']          = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_pengguna' => $nama,
            'username'      => $username,
        ];

        if ($pass !== '') {
            $data['password'] = $model->hashPassword($pass);
        }

        if (! $model->update($userId, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
        }

        session()->set([
            'user_name' => $nama,
            'username'  => $username,
        ]);

        return redirect()->to('/profile')->with('message', 'Profil diperbarui.');
    }
}
