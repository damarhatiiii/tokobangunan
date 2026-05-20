<?php

use App\Models\CategoryModel;
use Tests\Support\MasterDataTestCase;

/**
 * Whitebox: CategoryModel (search, validasi, CRUD).
 *
 * @internal
 */
final class CategoryModelTest extends MasterDataTestCase
{
    public function testSearchFiltersByNamaKategori(): void
    {
        $model = new CategoryModel();
        $rows  = $model->orderBy('nama_kategori', 'ASC')->search('Sem')->findAll();

        $this->assertCount(1, $rows);
        $this->assertSame('Semen', $rows[0]['nama_kategori']);
    }

    public function testInsertRejectsNamaTerlaluPendek(): void
    {
        $model = new CategoryModel();
        $ok    = $model->insert(['nama_kategori' => 'A']);

        $this->assertFalse($ok);
        $this->assertArrayHasKey('nama_kategori', $model->errors());
    }

    public function testInsertValidDataPersists(): void
    {
        $model = new CategoryModel();
        $id    = $model->insert(['nama_kategori' => 'Kayu'], true);

        $this->assertIsInt($id);
        $row = $model->find($id);
        $this->assertSame('Kayu', $row['nama_kategori']);
    }
}
