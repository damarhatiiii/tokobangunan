<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'id_kategori';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['nama_kategori'];

    protected array $casts = [
        'id_kategori' => 'int',
    ];

    protected $validationRules = [
        'nama_kategori' => 'required|min_length[2]|max_length[100]',
    ];

    public function search(string $keyword)
    {
        return $this->like('nama_kategori', $keyword);
    }
}
