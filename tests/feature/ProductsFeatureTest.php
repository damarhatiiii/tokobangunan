<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\AppTestCase;

/**
 * Blackbox: Products controller (listing, creating, searching, access control)
 *
 * @internal
 */
final class ProductsFeatureTest extends AppTestCase
{
    use FeatureTestTrait;

    public function testIndexRequiresAuth(): void
    {
        $result = $this->get('products');

        $result->assertRedirect();
        $this->assertStringContainsString('login', strtolower($result->response()->getHeaderLine('Location')));
    }

    public function testIndexRendersSuccessfullyForAuthenticatedUser(): void
    {
        $result = $this->sessionAsOperator()->get('products');

        $result->assertOK();
        $result->assertSee('Data Barang');
        $result->assertSee('Semen Portland 50kg');
        $result->assertSee('Pipa PVC 3 inch');
    }

    public function testSearchFiltersProductListing(): void
    {
        $result = $this->sessionAsOperator()->get('products?q=Semen');

        $result->assertOK();
        $result->assertSee('Semen Portland 50kg');
        $result->assertDontSee('Pipa PVC 3 inch');
    }

    public function testStoreSavesNewProductAndRedirects(): void
    {
        $result = $this->sessionAsOperator()->post('products/store', [
            'nama_barang' => 'Kayu Kaso 4x6',
            'id_kategori' => 1,
            'id_satuan'   => 3,
            'stok'        => 10,
        ]);

        $result->assertRedirect();
        $this->assertStringContainsString('products', strtolower($result->getRedirectUrl() ?? ''));
        $result->assertSessionHas('message', 'Barang disimpan.');

        // Check DB to make sure it was stored
        $this->seeInDatabase('barang', [
            'nama_barang' => 'Kayu Kaso 4x6',
            'id_kategori' => 1,
            'id_satuan'   => 3,
            'stok'        => 10,
        ]);
    }

    public function testStoreValidationFailsForInvalidInputs(): void
    {
        $result = $this->sessionAsOperator()->post('products/store', [
            'nama_barang' => '', // invalid
            'id_kategori' => 0,  // invalid
            'id_satuan'   => 0,  // invalid
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
        $errors = session('errors');
        $this->assertArrayHasKey('nama_barang', $errors);
        $this->assertArrayHasKey('id_kategori', $errors);
        $this->assertArrayHasKey('id_satuan', $errors);
    }

    public function testDeleteProductSuccessfully(): void
    {
        // Add a temporary product to delete
        $model = new \App\Models\BarangModel();
        $id = $model->insert([
            'nama_barang' => 'Barang Untuk Dihapus',
            'id_kategori' => 1,
            'id_satuan'   => 1,
            'stok'        => 0,
        ], true);

        $result = $this->sessionAsOperator()->get("products/delete/{$id}");

        $result->assertRedirect();
        $result->assertSessionHas('message', 'Barang dihapus.');

        $this->dontSeeInDatabase('barang', [
            'id_barang' => $id,
        ]);
    }
}
