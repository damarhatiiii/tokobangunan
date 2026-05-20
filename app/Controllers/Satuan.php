<?php

namespace App\Controllers;

use App\Models\SatuanModel;

class Satuan extends BaseController
{
    protected SatuanModel $model;

    public function __construct()
    {
        $this->model = new SatuanModel();
    }

    public function index()
    {
        return redirect()->to('/categories');
    }

    public function create()
    {
        return view('satuan/form', [
            'title' => 'Tambah Satuan',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $data = ['nama_satuan' => $this->request->getPost('nama_satuan')];

        if (! $this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/categories')->with('message', 'Satuan disimpan.');
    }

    public function edit(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('satuan/form', [
            'title' => 'Edit Satuan',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = ['nama_satuan' => $this->request->getPost('nama_satuan')];

        if (! $this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/categories')->with('message', 'Satuan diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $this->model->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/categories')->with('error', 'Satuan masih digunakan barang.');
        }

        return redirect()->to('/categories')->with('message', 'Satuan dihapus.');
    }
}
