<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('satuan')->insertBatch([
            ['nama_satuan' => 'PCS'],
            ['nama_satuan' => 'zak'],
            ['nama_satuan' => 'batang'],
            ['nama_satuan' => 'kaleng'],
        ]);

        $this->db->table('kategori')->insertBatch([
            ['nama_kategori' => 'Semen & Mortar'],
            ['nama_kategori' => 'Besi & Baja Ringan'],
            ['nama_kategori' => 'Cat & Pelapis'],
            ['nama_kategori' => 'Pipa & Sanitasi'],
        ]);

        $this->db->table('supplier')->insertBatch([
            ['nama_supplier' => 'PT Bangun Jaya', 'alamat' => 'Jakarta', 'nomor_telepon' => '021-5550101'],
            ['nama_supplier' => 'CV Sumber Material', 'alamat' => 'Bandung', 'nomor_telepon' => '022-5550202'],
        ]);

        $this->db->table('barang')->insertBatch([
            [
                'nama_barang' => 'Semen Portland 50kg', 'id_kategori' => 1, 'id_satuan' => 2, 'stok' => 0,
            ],
            [
                'nama_barang' => 'Besi Beton 10mm', 'id_kategori' => 2, 'id_satuan' => 3, 'stok' => 0,
            ],
            [
                'nama_barang' => 'Cat Tembok Interior 5L', 'id_kategori' => 3, 'id_satuan' => 4, 'stok' => 0,
            ],
            [
                'nama_barang' => 'Pipa PVC 3 inch', 'id_kategori' => 4, 'id_satuan' => 3, 'stok' => 0,
            ],
        ]);

        $userId = 1;
        $today  = date('Y-m-d');
        $hargaJual = [
            1 => 72000,
            2 => 95000,
            3 => 210000,
            4 => 110000,
        ];

        $this->db->table('barang_masuk')->insert([
            'tanggal'       => $today,
            'id_barang'     => 1,
            'id_supplier'   => 1,
            'id_pengguna'   => $userId,
            'harga_beli'    => 65000,
            'jumlah'        => 150,
        ]);
        $this->db->table('barang_masuk')->insert([
            'tanggal'       => $today,
            'id_barang'     => 2,
            'id_supplier'   => 1,
            'id_pengguna'   => $userId,
            'harga_beli'    => 85000,
            'jumlah'        => 50,
        ]);
        $this->db->table('barang_masuk')->insert([
            'tanggal'       => $today,
            'id_barang'     => 3,
            'id_supplier'   => 2,
            'id_pengguna'   => $userId,
            'harga_beli'    => 180000,
            'jumlah'        => 20,
        ]);
        $this->db->table('barang_masuk')->insert([
            'tanggal'       => $today,
            'id_barang'     => 4,
            'id_supplier'   => 2,
            'id_pengguna'   => $userId,
            'harga_beli'    => 95000,
            'jumlah'        => 30,
        ]);

        foreach ([1, 2, 3, 4] as $pid) {
            for ($i = 0; $i < 14; $i++) {
                $d = date('Y-m-d', strtotime("-{$i} days"));
                if ($pid === 3 && $i % 2 !== 0) {
                    continue;
                }
                $qty = random_int(1, 6);
                $this->db->table('barang_keluar')->insert([
                    'tanggal'       => $d,
                    'id_barang'     => $pid,
                    'id_pengguna'   => $userId,
                    'harga_jual'    => $hargaJual[$pid],
                    'jumlah'        => $qty,
                ]);
            }
        }

        foreach ([1, 2, 3, 4] as $bid) {
            $inSum = $this->db->table('barang_masuk')->selectSum('jumlah', 't')->where('id_barang', $bid)->get()->getRowArray();
            $outSum = $this->db->table('barang_keluar')->selectSum('jumlah', 't')->where('id_barang', $bid)->get()->getRowArray();
            $in     = (int) ($inSum['t'] ?? 0);
            $out    = (int) ($outSum['t'] ?? 0);
            $this->db->table('barang')->where('id_barang', $bid)->update(['stok' => max(0, $in - $out)]);
        }
    }
}
