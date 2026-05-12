<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $hash = static fn (string $p): string => password_hash($p, PASSWORD_DEFAULT);

        $this->db->table('pengguna')->insertBatch([
            [
                'username'      => 'admin',
                'password'      => $hash('admin123'),
                'nama_pengguna' => 'Administrator',
                'role'          => 'admin',
                'status_aktif'  => 1,
            ],
            [
                'username'      => 'petugas',
                'password'      => $hash('petugas123'),
                'nama_pengguna' => 'Petugas Gudang',
                'role'          => 'petugas',
                'status_aktif'  => 1,
            ],
        ]);
    }
}
