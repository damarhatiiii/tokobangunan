<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\CategoryModel;
use App\Models\SatuanModel;

class Products extends BaseController
{
    protected BarangModel $model;

    public function __construct()
    {
        $this->model = new BarangModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->model->withRelations()->orderBy('barang.nama_barang', 'ASC');
        if ($q !== '') {
            $b = $b->search($q);
        }
        $rows = $b->paginate(10);
        $rows = $this->model->attachLastHargaBeli($this->model->attachLastHargaJual($rows));

        return view('products/index', [
            'title' => 'Data Barang',
            'q'     => $q,
            'rows'  => $rows,
            'pager' => $this->model->pager,
        ]);
    }

    public function show(int $id)
    {
        $rows = $this->model->withRelations()->where('barang.id_barang', $id)->findAll(1);
        if ($rows === []) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $row = $this->model->attachLastHargaBeli($this->model->attachLastHargaJual($rows))[0];

        return view('products/show', [
            'title' => 'Detail Barang',
            'row'   => $row,
        ]);
    }

    public function create()
    {
        return view('products/form', [
            'title'      => 'Tambah Barang',
            'row'        => null,
            'categories' => (new CategoryModel())->orderBy('nama_kategori', 'ASC')->findAll(),
            'satuan'     => (new SatuanModel())->orderBy('nama_satuan', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_satuan'   => $this->request->getPost('id_satuan'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => (int) $this->request->getPost('stok') ?: 0,
        ];

        if (! $this->model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/products')->with('message', 'Barang disimpan.');
    }

    public function edit(int $id)
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('products/form', [
            'title'      => 'Edit Barang',
            'row'        => $row,
            'categories' => (new CategoryModel())->orderBy('nama_kategori', 'ASC')->findAll(),
            'satuan'     => (new SatuanModel())->orderBy('nama_satuan', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_satuan'   => $this->request->getPost('id_satuan'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => (int) $this->request->getPost('stok'),
        ];

        if (! $this->model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to('/products')->with('message', 'Barang diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $this->model->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/products')->with('error', 'Gagal menghapus: barang masih punya riwayat transaksi.');
        }

        return redirect()->to('/products')->with('message', 'Barang dihapus.');
    }
}
