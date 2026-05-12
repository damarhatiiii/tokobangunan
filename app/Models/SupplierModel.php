<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table         = 'supplier';
    protected $primaryKey    = 'id_supplier';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['nama_supplier', 'alamat', 'nomor_telepon'];

    protected array $casts = [
        'id_supplier' => 'int',
    ];

    protected $validationRules = [
        'nama_supplier' => 'required|min_length[2]|max_length[100]',
    ];

    public function search(string $keyword)
    {
        return $this->groupStart()
            ->like('nama_supplier', $keyword)
            ->orLike('nomor_telepon', $keyword)
            ->groupEnd();
    }
}
