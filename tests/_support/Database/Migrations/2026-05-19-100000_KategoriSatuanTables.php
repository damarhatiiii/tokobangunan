<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Skema minimal kategori & satuan untuk pengujian whitebox.
 */
class KategoriSatuanTables extends Migration
{
    protected $DBGroup = 'tests';

    public function up(): void
    {
        $this->forge->addField([
            'id_kategori'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_kategori' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id_kategori', true);
        $this->forge->createTable('kategori');

        $this->forge->addField([
            'id_satuan'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_satuan' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id_satuan', true);
        $this->forge->createTable('satuan');
    }

    public function down(): void
    {
        $this->forge->dropTable('satuan', true);
        $this->forge->dropTable('kategori', true);
    }
}
