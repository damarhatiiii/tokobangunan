<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table         = 'barang';
    protected $primaryKey    = 'id_barang';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['nama_barang', 'id_kategori', 'id_satuan', 'stok'];

    protected array $casts = [
        'id_barang'   => 'int',
        'id_kategori' => 'int',
        'id_satuan'   => 'int',
        'stok'        => 'int',
    ];

    protected $validationRules = [
        'nama_barang' => 'required|min_length[2]|max_length[150]',
        'id_kategori' => 'required|is_natural_no_zero',
        'id_satuan'   => 'required|is_natural_no_zero',
        'stok'        => 'permit_empty|integer',
    ];

    public function withRelations()
    {
        return $this->select('barang.*, kategori.nama_kategori AS nama_kategori, satuan.nama_satuan AS nama_satuan')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
    }

    public function search(string $keyword)
    {
        return $this->like('barang.nama_barang', $keyword);
    }

    /** Harga referensi dari transaksi keluar terakhir (nullable). */
    public function attachLastHargaJual(array $rows): array
    {
        if ($rows === []) {
            return $rows;
        }
        $ids = array_values(array_unique(array_filter(array_map(static fn ($r) => (int) ($r['id_barang'] ?? 0), $rows))));
        if ($ids === []) {
            return $rows;
        }
        $db = db_connect();
        $ph = implode(',', array_fill(0, count($ids), '?'));

        $res = $db->query(
            "SELECT bk.id_barang, bk.harga_jual FROM barang_keluar bk
            INNER JOIN (
                SELECT id_barang, MAX(id_keluar) AS mx FROM barang_keluar WHERE id_barang IN ({$ph}) GROUP BY id_barang
            ) t ON t.mx = bk.id_keluar AND t.id_barang = bk.id_barang",
            $ids
        )->getResultArray();

        $map = [];
        foreach ($res as $x) {
            $map[(int) $x['id_barang']] = $x['harga_jual'];
        }

        foreach ($rows as &$r) {
            $id                           = (int) $r['id_barang'];
            $r['referensi_harga_jual']    = $map[$id] ?? null;
        }
        unset($r);

        return $rows;
    }

    /** Harga referensi dari pembelian terakhir (nullable). */
    public function attachLastHargaBeli(array $rows): array
    {
        if ($rows === []) {
            return $rows;
        }
        $ids = array_values(array_unique(array_filter(array_map(static fn ($r) => (int) ($r['id_barang'] ?? 0), $rows))));
        if ($ids === []) {
            return $rows;
        }
        $db = db_connect();
        $ph = implode(',', array_fill(0, count($ids), '?'));

        $res = $db->query(
            "SELECT bm.id_barang, bm.harga_beli FROM barang_masuk bm
            INNER JOIN (
                SELECT id_barang, MAX(id_masuk) AS mx FROM barang_masuk WHERE id_barang IN ({$ph}) GROUP BY id_barang
            ) t ON t.mx = bm.id_masuk AND t.id_barang = bm.id_barang",
            $ids
        )->getResultArray();

        $map = [];
        foreach ($res as $x) {
            $map[(int) $x['id_barang']] = $x['harga_beli'];
        }

        foreach ($rows as &$r) {
            $id                           = (int) $r['id_barang'];
            $r['referensi_harga_beli']    = $map[$id] ?? null;
        }
        unset($r);

        return $rows;
    }

    /**
     * Untuk form penjualan: isi default_harga_jual (string untuk input number).
     * Urutan: harga jual transaksi terakhir → estimasi dari harga beli terakhir × markup.
     *
     * @param list<array<string,mixed>> $rows
     * @return list<array<string,mixed>>
     */
    public function attachSuggestedHargaJual(array $rows, float $markupFromBeli = 1.15): array
    {
        foreach ($rows as &$r) {
            $r['default_harga_jual'] = '';
            $hj                      = $r['referensi_harga_jual'] ?? null;
            $hb                      = $r['referensi_harga_beli'] ?? null;

            if ($hj !== null && $hj !== '') {
                $r['default_harga_jual'] = number_format((float) $hj, 2, '.', '');
            } elseif ($hb !== null && $hb !== '') {
                $r['default_harga_jual'] = number_format(round((float) $hb * $markupFromBeli, 2), 2, '.', '');
            }
        }
        unset($r);

        return $rows;
    }
}
