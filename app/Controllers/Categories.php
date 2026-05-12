<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->model->orderBy('nama_kategori', 'ASC');
        if ($q !== '') {
            $b = $b->search($q);
        }

        return view('categories/index', [
            'title' => 'Kategori Barang',
            'q'     => $q,
            'rows'  => $b->paginate(10),
            'pager' => $this->model->pager,
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
