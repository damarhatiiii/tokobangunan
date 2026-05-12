<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanModel extends Model
{
    protected $table         = 'satuan';
    protected $primaryKey    = 'id_satuan';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['nama_satuan'];

    protected array $casts = [
        'id_satuan' => 'int',
    ];

    protected $validationRules = [
        'nama_satuan' => 'required|min_length[1]|max_length[50]',
    ];

    public function search(string $keyword)
    {
        return $this->like('nama_satuan', $keyword);
    }

    /** @return array<int,string> id => nama */
    public function asMap(): array
    {
        $out = [];
        foreach ($this->findAll() as $r) {
            $out[(int) $r['id_satuan']] = $r['nama_satuan'];
        }

        return $out;
    }
}
