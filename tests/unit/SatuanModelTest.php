<?php

use App\Models\SatuanModel;
use Tests\Support\MasterDataTestCase;

/**
 * Whitebox: SatuanModel (search, asMap, validasi).
 *
 * @internal
 */
final class SatuanModelTest extends MasterDataTestCase
{
    public function testSearchFiltersByNamaSatuan(): void
    {
        $model = new SatuanModel();
        $rows  = $model->orderBy('nama_satuan', 'ASC')->search('za')->findAll();

        $this->assertCount(1, $rows);
        $this->assertSame('zak', $rows[0]['nama_satuan']);
    }

    public function testAsMapReturnsIdToNama(): void
    {
        $map = (new SatuanModel())->asMap();

        $this->assertNotEmpty($map);
        $this->assertContains('PCS', $map);
        $this->assertSame('string', gettype(reset($map)));
    }

    public function testInsertRejectsEmptyNama(): void
    {
        $model = new SatuanModel();
        $ok    = $model->insert(['nama_satuan' => '']);

        $this->assertFalse($ok);
        $this->assertArrayHasKey('nama_satuan', $model->errors());
    }
}
