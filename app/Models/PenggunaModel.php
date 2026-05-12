<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table         = 'pengguna';
    protected $primaryKey    = 'id_pengguna';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'username', 'password', 'nama_pengguna', 'role', 'status_aktif',
    ];

    protected bool $allowEmptyInserts = false;

    protected array $casts = [
        'id_pengguna'   => 'int',
        'status_aktif'  => 'int',
    ];

    protected $validationRules = [
        'username'       => 'required|min_length[3]|max_length[50]',
        'password'       => 'permit_empty|min_length[6]',
        'nama_pengguna'  => 'required|min_length[2]|max_length[100]',
        'role'           => 'required|in_list[admin,petugas]',
        'status_aktif'   => 'permit_empty|in_list[0,1]',
    ];

    public function findByUsername(string $username): ?array
    {
        $row = $this->where('username', $username)->first();

        return $row ?: null;
    }

    /** Hash baru sebelum simpan untuk field password plaintext. */
    public function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $plain, array $user): bool
    {
        return password_verify($plain, (string) $user['password']);
    }
}
