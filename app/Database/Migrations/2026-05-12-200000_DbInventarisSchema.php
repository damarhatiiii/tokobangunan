<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Skema mengikuti dump db_inventaris.sql (nama tabel & kolom Indonesia).
 */
class DbInventarisSchema extends Migration
{
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

        $this->forge->addField([
            'id_supplier'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_supplier'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'alamat'         => ['type' => 'TEXT', 'null' => true],
            'nomor_telepon'  => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addKey('id_supplier', true);
        $this->forge->createTable('supplier');

        $this->forge->addField([
            'id_pengguna'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'password'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_pengguna'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'role'           => ['type' => 'ENUM', 'constraint' => ['admin', 'petugas'], 'default' => 'petugas'],
            'status_aktif'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id_pengguna', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('pengguna');

        $this->forge->addField([
            'id_barang'     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_barang'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'id_kategori'   => ['type' => 'INT', 'unsigned' => true],
            'id_satuan'     => ['type' => 'INT', 'unsigned' => true],
            'stok'          => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id_barang', true);
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('id_satuan', 'satuan', 'id_satuan', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('barang');

        $this->forge->addField([
            'id_masuk'     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tanggal'      => ['type' => 'DATE'],
            'id_barang'    => ['type' => 'INT', 'unsigned' => true],
            'id_supplier'  => ['type' => 'INT', 'unsigned' => true],
            'id_pengguna'  => ['type' => 'INT', 'unsigned' => true],
            'harga_beli'   => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'jumlah'       => ['type' => 'INT'],
        ]);
        $this->forge->addKey('id_masuk', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('id_barang', 'barang', 'id_barang', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id_supplier', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id_pengguna', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('barang_masuk');

        $this->forge->addField([
            'id_keluar'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tanggal'      => ['type' => 'DATE'],
            'id_barang'    => ['type' => 'INT', 'unsigned' => true],
            'id_pengguna'  => ['type' => 'INT', 'unsigned' => true],
            'harga_jual'   => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'jumlah'       => ['type' => 'INT'],
        ]);
        $this->forge->addKey('id_keluar', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('id_barang', 'barang', 'id_barang', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id_pengguna', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('barang_keluar');
    }

    public function down(): void
    {
        $this->forge->dropTable('barang_keluar', true);
        $this->forge->dropTable('barang_masuk', true);
        $this->forge->dropTable('barang', true);
        $this->forge->dropTable('pengguna', true);
        $this->forge->dropTable('supplier', true);
        $this->forge->dropTable('satuan', true);
        $this->forge->dropTable('kategori', true);
    }
}
