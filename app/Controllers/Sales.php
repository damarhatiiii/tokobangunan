<?php

namespace App\Controllers;

use App\Libraries\InvoiceStrukCalculator;
use App\Models\BarangKeluarModel;
use App\Models\BarangModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;

class Sales extends BaseController
{
    protected BarangKeluarModel $keluar;
    protected BarangModel $barang;

    public function __construct()
    {
        $this->keluar = new BarangKeluarModel();
        $this->barang = new BarangModel();
    }

    public function index()
    {
        $q = (string) $this->request->getGet('q');
        $b = $this->keluar->withRelations();
        if ($q !== '') {
            $b->groupStart()->like('barang.nama_barang', $q)->groupEnd();
        }

        return view('sales/index', [
            'title' => 'Barang Keluar / Penjualan',
            'q'     => $q,
            'rows'  => $b->paginate(12),
            'pager' => $this->keluar->pager,
        ]);
    }

    public function create()
    {
        $products = $this->barang->withRelations()->orderBy('barang.nama_barang', 'ASC')->findAll();
        $products = $this->barang->attachLastHargaJual($products);
        $products = $this->barang->attachLastHargaBeli($products);
        $products = $this->barang->attachSuggestedHargaJual($products);

        return view('sales/form', [
            'title'    => 'Barang Keluar',
            'products' => $products,
        ]);
    }

    public function store()
    {
        $idBarang  = (int) $this->request->getPost('id_barang');
        $jumlah    = (int) $this->request->getPost('jumlah');
        $tanggal   = (string) $this->request->getPost('tanggal');
        $hargaJualRaw = $this->request->getPost('harga_jual');
        $hargaJual    = InvoiceStrukCalculator::normalizeMoneyInput($hargaJualRaw);

        $rules = [
            'id_barang'  => 'required|is_natural_no_zero',
            'tanggal'    => 'required|valid_date',
            'jumlah'     => 'required|integer|greater_than[0]',
            'harga_jual' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $row = $this->barang->find($idBarang);
        if (! $row) {
            return redirect()->back()->withInput()->with('error', 'Barang tidak ditemukan.');
        }

        if ($jumlah < 1) {
            return redirect()->back()->withInput()->with('error', 'Jumlah minimal 1.');
        }

        $current = (int) $row['stok'];
        if ($current < $jumlah) {
            return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi.');
        }

        if ((float) $hargaJual <= 0) {
            return redirect()->back()->withInput()->with('error', 'Harga jual harus lebih dari 0.');
        }

        $userId = (int) session()->get('user_id');
        if ($userId < 1) {
            return redirect()->to('/logout');
        }

        $db = Database::connect();
        $db->transStart();

        $this->barang->update($idBarang, ['stok' => $current - $jumlah]);

        $this->keluar->insert([
            'tanggal'     => $tanggal,
            'id_barang'   => $idBarang,
            'id_pengguna' => $userId,
            'harga_jual'  => $hargaJual,
            'jumlah'      => $jumlah,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan barang keluar.');
        }

        return redirect()->to('/sales')->with('message', 'Transaksi barang keluar tercatat.');
    }

    public function receipt(int $id)
    {
        $row = $this->keluar->findReceiptDetail($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound();
        }

        $subtotal = InvoiceStrukCalculator::lineSubtotal((string) $row['harga_jual'], (int) $row['jumlah']);

        return view('sales/receipt', [
            'title'          => 'Struk #' . $id,
            'row'            => $row,
            'subtotal'       => $subtotal,
            'subtotal_print' => number_format((float) $subtotal, 0, ',', '.'),
            'harga_print'    => number_format((float) InvoiceStrukCalculator::normalizeMoneyInput($row['harga_jual']), 0, ',', '.'),
        ]);
    }
}
