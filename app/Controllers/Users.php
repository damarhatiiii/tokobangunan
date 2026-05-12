<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class Users extends BaseController
{
    protected PenggunaModel $model;

    public function __construct()
    {
        $this->model = new PenggunaModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->model->orderBy('nama_pengguna', 'ASC');
        if ($q !== '') {
            $b = $b->groupStart()
                ->like('nama_pengguna', $q)
                ->orLike('username', $q)
                ->groupEnd();
        }

        return view('users/index', [
            'title' => 'Manajemen Pengguna',
            'q'     => $q,
            'rows'  => $b->paginate(12),
            'pager' => $this->model->pager,
        ]);
    }

    public function create()
    {
        return view('users/form', [
            'title' => 'Tambah Pengguna',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_pengguna' => 'required|min_length[2]|max_length[100]',
            'username'      => 'required|min_length[3]|max_length[50]|is_unique[pengguna.username]',
            'password'      => 'required|min_length[6]',
            'role'          => 'required|in_list[admin,petugas]',
            'status_aktif'  => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'username'      => $this->request->getPost('username'),
            'password'      => $this->model->hashPassword((string) $this->request->getPost('password')),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'role'          => $this->request->getPost('role'),
            'status_aktif'  => (int) $this->request->getPost('status_aktif'),
        ]);

        return redirect()->to('/users')->with('message', 'Pengguna ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('users/form', [
            'title' => 'Edit Pengguna',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'nama_pengguna' => 'required|min_length[2]|max_length[100]',
            'username'      => "required|min_length[3]|max_length[50]|is_unique[pengguna.username,id_pengguna,{$id}]",
            'role'          => 'required|in_list[admin,petugas]',
            'status_aktif'  => 'required|in_list[0,1]',
        ];

        $pass = (string) $this->request->getPost('password');
        if ($pass !== '') {
            $rules['password'] = 'min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'      => $this->request->getPost('username'),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'role'          => $this->request->getPost('role'),
            'status_aktif'  => (int) $this->request->getPost('status_aktif'),
        ];

        if ($pass !== '') {
            $data['password'] = $this->model->hashPassword($pass);
        }

        $this->model->update($id, $data);

        return redirect()->to('/users')->with('message', 'Pengguna diperbarui.');
    }

    public function delete(int $id)
    {
        $sessionId = (int) session()->get('user_id');
        if ($id === $sessionId) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menghapus akun yang sedang aktif.');
        }

        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->model->delete($id);

        return redirect()->to('/users')->with('message', 'Pengguna dihapus.');
    }
}
