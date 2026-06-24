<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\SatuanModel;

class Categories extends BaseController
{
    protected CategoryModel $model;
    protected SatuanModel $satuanModel;

    public function __construct()
    {
        $this->model       = new CategoryModel();
        $this->satuanModel = new SatuanModel();
    }

    public function index()
    {
        $qk = (string) $this->request->getGet('qk');
        $qs = (string) $this->request->getGet('qs');

        $kategori = $this->model->orderBy('nama_kategori', 'ASC');
        if ($qk !== '') {
            $kategori = $kategori->search($qk);
        }

        $satuan = $this->satuanModel->orderBy('nama_satuan', 'ASC');
        if ($qs !== '') {
            $satuan = $satuan->search($qs);
        }

        return view('categories/index', [
            'title'    => 'Kategori & Satuan',
            'qk'       => $qk,
            'qs'       => $qs,
            'kategori' => $kategori->findAll(),
            'satuan'   => $satuan->findAll(),
        ]);
    }

    public function create()
    {
        return view('categories/form', [
            'title' => 'Tambah Kategori',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $data = ['nama_kategori' => $this->request->getPost('nama_kategori')];

        if (! $this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/categories')->with('message', 'Kategori disimpan.');
    }

    public function edit(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('categories/form', [
            'title' => 'Edit Kategori',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = ['nama_kategori' => $this->request->getPost('nama_kategori')];

        if (! $this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/categories')->with('message', 'Kategori diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $this->model->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/categories')->with('error', 'Tidak dapat menghapus: masih dipakai barang.');
        }

        return redirect()->to('/categories')->with('message', 'Kategori dihapus.');
    }
}
