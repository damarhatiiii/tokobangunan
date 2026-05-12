<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table         = 'barang_keluar';
    protected $primaryKey    = 'id_keluar';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'tanggal', 'id_barang', 'id_pengguna', 'harga_jual', 'jumlah',
    ];

    protected bool $allowEmptyInserts = false;

    protected array $casts = [
        'id_keluar'   => 'int',
        'id_barang'   => 'int',
        'id_pengguna' => 'int',
        'jumlah'      => 'int',
    ];

    protected $validationRules = [
        'tanggal'    => 'required|valid_date',
        'id_barang'  => 'required|is_natural_no_zero',
        'id_pengguna' => 'required|is_natural_no_zero',
        'harga_jual' => 'required|decimal',
        'jumlah'     => 'required|integer|greater_than[0]',
    ];

    public function withRelations()
    {
        return $this->select(
            'barang_keluar.*, barang.nama_barang AS product_name'
        )
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left')
            ->orderBy('barang_keluar.tanggal', 'DESC')
            ->orderBy('barang_keluar.id_keluar', 'DESC');
    }

    /** Satu baris transaksi keluar lengkap untuk cetak struk. */
    public function findReceiptDetail(int $id): ?array
    {
        $row = $this->select(
            'barang_keluar.*, barang.nama_barang AS product_name, satuan.nama_satuan AS nama_satuan, pengguna.nama_pengguna AS nama_kasir'
        )
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'inner')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->join('pengguna', 'pengguna.id_pengguna = barang_keluar.id_pengguna', 'left')
            ->where('barang_keluar.id_keluar', $id)
            ->first();

        return $row ?: null;
    }
}
