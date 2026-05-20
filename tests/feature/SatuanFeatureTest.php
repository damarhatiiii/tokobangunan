<?php

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\MasterDataTestCase;

/**
 * Whitebox: Satuan controller (redirect index, CRUD redirect).
 *
 * @internal
 */
final class SatuanFeatureTest extends MasterDataTestCase
{
    use FeatureTestTrait;

    public function testIndexRedirectsToCategories(): void
    {
        $result = $this->sessionAsOperator()->get('satuan');

        $result->assertRedirect();
        $this->assertStringContainsString('categories', strtolower($result->getRedirectUrl() ?? ''));
    }

    public function testStoreRedirectsToCategoriesOnSuccess(): void
    {
        $result = $this->sessionAsOperator()
            ->post('satuan/store', ['nama_satuan' => 'liter']);

        $result->assertRedirect();
        $this->assertStringContainsString('categories', strtolower($result->getRedirectUrl() ?? ''));
        $this->assertSame('Satuan disimpan.', session('message'));
    }

    public function testUpdateRedirectsToCategoriesOnSuccess(): void
    {
        $result = $this->sessionAsOperator()
            ->post('satuan/update/1', ['nama_satuan' => 'buah']);

        $result->assertRedirect();
        $this->assertStringContainsString('categories', strtolower($result->getRedirectUrl() ?? ''));
        $this->assertSame('Satuan diperbarui.', session('message'));
    }
}
