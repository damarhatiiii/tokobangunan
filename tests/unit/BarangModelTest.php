<?php

namespace Tests\Unit;

use App\Models\BarangModel;
use Tests\Support\AppTestCase;

/**
 * Whitebox: BarangModel (validation, search, attachSuggestedHargaJual)
 *
 * @internal
 */
final class BarangModelTest extends AppTestCase
{
    public function testInsertValidationFailsWithoutRequiredFields(): void
    {
        $model = new BarangModel();
        
        // Missing nama_barang, id_kategori, id_satuan
        $ok = $model->insert([
            'stok' => 10
        ]);

        $this->assertFalse($ok);
        $errors = $model->errors();
        $this->assertArrayHasKey('nama_barang', $errors);
        $this->assertArrayHasKey('id_kategori', $errors);
        $this->assertArrayHasKey('id_satuan', $errors);
    }

    public function testInsertValidationFailsWithTooShortNamaBarang(): void
    {
        $model = new BarangModel();
        
        $ok = $model->insert([
            'nama_barang' => 'A', // min_length[2]
            'id_kategori' => 1,
            'id_satuan'   => 1
        ]);

        $this->assertFalse($ok);
        $this->assertArrayHasKey('nama_barang', $model->errors());
    }

    public function testInsertValidDataPersistsSuccessfully(): void
    {
        $model = new BarangModel();
        
        $id = $model->insert([
            'nama_barang' => 'Semen Tiga Roda',
            'id_kategori' => 1,
            'id_satuan'   => 2,
            'stok'        => 50
        ], true);

        $this->assertIsInt($id);
        $row = $model->find($id);
        $this->assertSame('Semen Tiga Roda', $row['nama_barang']);
        $this->assertSame(50, $row['stok']);
    }

    public function testSearchFiltersByName(): void
    {
        $model = new BarangModel();
        $rows  = $model->search('Portland')->findAll();

        $this->assertCount(1, $rows);
        $this->assertSame('Semen Portland 50kg', $rows[0]['nama_barang']);
    }

    public function testAttachSuggestedHargaJualPrefersHargaJualIfAvailable(): void
    {
        $model = new BarangModel();

        $rows = [
            [
                'id_barang' => 1,
                'referensi_harga_jual' => 72000,
                'referensi_harga_beli' => 65000,
            ]
        ];

        $results = $model->attachSuggestedHargaJual($rows);
        $this->assertSame('72000.00', $results[0]['default_harga_jual']);
    }

    public function testAttachSuggestedHargaJualCalculatesMarkupFromHargaBeliIfNoHargaJual(): void
    {
        $model = new BarangModel();

        $rows = [
            [
                'id_barang' => 1,
                'referensi_harga_jual' => null,
                'referensi_harga_beli' => 10000,
            ]
        ];

        // Default markup is 1.15 -> 10000 * 1.15 = 11500
        $results = $model->attachSuggestedHargaJual($rows);
        $this->assertSame('11500.00', $results[0]['default_harga_jual']);
    }

    public function testAttachSuggestedHargaJualReturnsEmptyStringIfBothNull(): void
    {
        $model = new BarangModel();

        $rows = [
            [
                'id_barang' => 1,
                'referensi_harga_jual' => null,
                'referensi_harga_beli' => null,
            ]
        ];

        $results = $model->attachSuggestedHargaJual($rows);
        $this->assertSame('', $results[0]['default_harga_jual']);
    }
}
