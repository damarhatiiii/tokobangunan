<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table         = 'barang_masuk';
    protected $primaryKey    = 'id_masuk';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'tanggal', 'id_barang', 'id_supplier', 'id_pengguna', 'harga_beli', 'jumlah',
    ];

    protected bool $allowEmptyInserts = false;

    protected array $casts = [
        'id_masuk'    => 'int',
        'id_barang'   => 'int',
        'id_supplier' => 'int',
        'id_pengguna' => 'int',
        'jumlah'      => 'int',
    ];

    protected $validationRules = [
        'tanggal'     => 'required|valid_date',
        'id_barang'   => 'required|is_natural_no_zero',
        'id_supplier' => 'required|is_natural_no_zero',
        'id_pengguna' => 'required|is_natural_no_zero',
        'harga_beli'  => 'required|decimal',
        'jumlah'      => 'required|integer|greater_than[0]',
    ];

    public function listingWithRelations()
    {
        return $this->select(
            'barang_masuk.*, barang.nama_barang AS product_name, supplier.nama_supplier AS nama_supplier'
        )
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left')
            ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left')
            ->orderBy('barang_masuk.tanggal', 'DESC')
            ->orderBy('barang_masuk.id_masuk', 'DESC');
    }

    /** Satu baris transaksi masuk lengkap untuk cetak bukti penerimaan. */
    public function findReceiptDetail(int $id): ?array
    {
        $row = $this->select(
            'barang_masuk.*, barang.nama_barang AS product_name, satuan.nama_satuan AS nama_satuan, supplier.nama_supplier AS nama_supplier, pengguna.nama_pengguna AS nama_petugas'
        )
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'inner')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left')
            ->join('pengguna', 'pengguna.id_pengguna = barang_masuk.id_pengguna', 'left')
            ->where('barang_masuk.id_masuk', $id)
            ->first();

        return $row ?: null;
    }
}
