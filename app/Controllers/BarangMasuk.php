<?php

namespace App\Controllers;

use App\Libraries\InvoiceStrukCalculator;
use App\Models\BarangMasukModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;

class BarangMasuk extends BaseController
{
    protected BarangMasukModel $masuk;
    protected BarangModel $barang;

    public function __construct()
    {
        $this->masuk = new BarangMasukModel();
        $this->barang = new BarangModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->masuk->listingWithRelations();
        if ($q !== '') {
            $b->groupStart()
                ->like('barang.nama_barang', $q)
                ->orLike('supplier.nama_supplier', $q)
                ->groupEnd();
        }

        return view('barang_masuk/index', [
            'title' => 'Barang Masuk',
            'q'     => $q,
            'rows'  => $b->paginate(15),
            'pager' => $this->masuk->pager,
        ]);
    }

    public function create()
    {
        return view('barang_masuk/form', [
            'title'     => 'Tambah Barang Masuk',
            'suppliers' => (new SupplierModel())->orderBy('nama_supplier', 'ASC')->findAll(),
            'products'  => $this->barang->withRelations()->orderBy('barang.nama_barang', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $idBarang   = (int) $this->request->getPost('id_barang');
        $idSupplier = (int) $this->request->getPost('id_supplier');
        $jumlah     = (int) $this->request->getPost('jumlah');
        $tanggal    = (string) $this->request->getPost('tanggal');
        $hargaBeliRaw = $this->request->getPost('harga_beli');
        $hargaBeli    = InvoiceStrukCalculator::normalizeMoneyInput($hargaBeliRaw);

        $rules = [
            'id_barang'   => 'required|is_natural_no_zero',
            'id_supplier' => 'required|is_natural_no_zero',
            'tanggal'     => 'required|valid_date',
            'jumlah'      => 'required|integer|greater_than[0]',
            'harga_beli'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($idSupplier < 1) {
            return redirect()->back()->withInput()->with('error', 'Supplier wajib dipilih.');
        }

        $product = $this->barang->find($idBarang);
        if (! $product) {
            return redirect()->back()->withInput()->with('error', 'Barang tidak ditemukan.');
        }

        if ($jumlah < 1) {
            return redirect()->back()->withInput()->with('error', 'Jumlah minimal 1.');
        }

        if ((float) $hargaBeli <= 0) {
            return redirect()->back()->withInput()->with('error', 'Harga beli harus lebih dari 0.');
        }

        $userId = (int) session()->get('user_id');
        if ($userId < 1) {
            return redirect()->to('/logout');
        }

        $db = Database::connect();
        $db->transStart();

        $newStock = (int) $product['stok'] + $jumlah;
        $this->barang->update($idBarang, ['stok' => $newStock]);

        $this->masuk->insert([
            'tanggal'      => $tanggal,
            'id_barang'    => $idBarang,
            'id_supplier'  => $idSupplier,
            'id_pengguna'  => $userId,
            'harga_beli'   => $hargaBeli,
            'jumlah'       => $jumlah,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan barang masuk.');
        }

        return redirect()->to('/barang-masuk')->with('message', 'Barang masuk tercatat.');
    }

    public function receipt(int $id)
    {
        $row = $this->masuk->findReceiptDetail($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound();
        }

        $subtotal = InvoiceStrukCalculator::lineSubtotal((string) $row['harga_beli'], (int) $row['jumlah']);

        return view('barang_masuk/receipt', [
            'title'          => 'Bukti penerimaan #' . $id,
            'row'            => $row,
            'subtotal'       => $subtotal,
            'subtotal_print' => number_format((float) $subtotal, 0, ',', '.'),
            'harga_print'    => number_format((float) InvoiceStrukCalculator::normalizeMoneyInput($row['harga_beli']), 0, ',', '.'),
        ]);
    }
}
