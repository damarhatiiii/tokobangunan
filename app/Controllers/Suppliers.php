<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    protected SupplierModel $model;

    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->model->orderBy('nama_supplier', 'ASC');
        if ($q !== '') {
            $b = $b->search($q);
        }

        return view('suppliers/index', [
            'title' => 'Supplier',
            'q'     => $q,
            'rows'  => $b->paginate(10),
            'pager' => $this->model->pager,
        ]);
    }

    public function create()
    {
        return view('suppliers/form', [
            'title' => 'Tambah Supplier',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'nomor_telepon' => $this->request->getPost('nomor_telepon') ?: null,
            'alamat'        => $this->request->getPost('alamat') ?: null,
        ];

        if (! $this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/suppliers')->with('message', 'Supplier disimpan.');
    }

    public function edit(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('suppliers/form', [
            'title' => 'Edit Supplier',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'nomor_telepon' => $this->request->getPost('nomor_telepon') ?: null,
            'alamat'        => $this->request->getPost('alamat') ?: null,
        ];

        if (! $this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/suppliers')->with('message', 'Supplier diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $this->model->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/suppliers')->with('error', 'Tidak dapat menghapus: supplier masih punya riwayat barang masuk.');
        }

        return redirect()->to('/suppliers')->with('message', 'Supplier dihapus.');
    }
}
