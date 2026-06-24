<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('kategori')->insertBatch([
            ['nama_kategori' => 'Semen'],
            ['nama_kategori' => 'Besi'],
            ['nama_kategori' => 'Cat'],
        ]);

        $this->db->table('satuan')->insertBatch([
            ['nama_satuan' => 'PCS'],
            ['nama_satuan' => 'zak'],
            ['nama_satuan' => 'batang'],
        ]);
    }
}
