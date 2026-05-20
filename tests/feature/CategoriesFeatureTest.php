<?php

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\MasterDataTestCase;

/**
 * Whitebox: Categories controller (halaman gabungan, CRUD, auth).
 *
 * @internal
 */
final class CategoriesFeatureTest extends MasterDataTestCase
{
    use FeatureTestTrait;

    public function testIndexRequiresLogin(): void
    {
        $result = $this->get('categories');

        $result->assertRedirect();
        $this->assertStringContainsString('login', strtolower($result->response()->getHeaderLine('Location')));
    }

    public function testIndexRendersCombinedPage(): void
    {
        $result = $this->sessionAsOperator()->get('categories');

        $result->assertOK();
        $result->assertSee('Kategori');
        $result->assertSee('Satuan');
        $result->assertSee('Semen');
        $result->assertSee('zak');
    }

    public function testIndexFiltersKategoriWithQk(): void
    {
        $result = $this->sessionAsOperator()->get('categories?qk=Sem');

        $result->assertOK();
        $result->assertSee('Semen');
        $result->assertDontSee('>Besi<', 'html');
    }

    public function testIndexFiltersSatuanWithQs(): void
    {
        $result = $this->sessionAsOperator()->get('categories?qs=PCS');

        $result->assertOK();
        $result->assertSee('PCS');
        $result->assertDontSee('>batang<', 'html');
    }

    public function testStoreRedirectsToCategoriesOnSuccess(): void
    {
        $result = $this->sessionAsOperator()
            ->post('categories/store', ['nama_kategori' => 'Pipa']);

        $result->assertRedirect();
        $this->assertStringContainsString('categories', strtolower($result->getRedirectUrl() ?? ''));
        $this->assertSame('Kategori disimpan.', session('message'));
    }
}
