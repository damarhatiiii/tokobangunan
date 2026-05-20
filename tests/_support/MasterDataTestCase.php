<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\MasterDataSeeder;

/**
 * Basis pengujian whitebox modul Kategori & Satuan.
 */
abstract class MasterDataTestCase extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh     = true;
    protected $migrate     = true;
    protected $namespace   = 'Tests\Support';
    protected $seed        = MasterDataSeeder::class;

    protected function setUp(): void
    {
        if (! extension_loaded('sqlite3')) {
            $this->markTestSkipped('Ekstensi PHP sqlite3 belum aktif. Aktifkan di php.ini (XAMPP: extension=sqlite3).');
        }

        parent::setUp();

        $filters = config('Filters');
        $filters->globals['before'] = array_values(array_filter(
            $filters->globals['before'],
            static fn ($f) => $f !== 'csrf',
        ));
    }

    /** Session petugas (role yang diizinkan di rute master data). */
    protected function sessionAsOperator(): self
    {
        return $this->withSession([
            'user_id'   => 1,
            'user_role' => 'petugas',
            'user_name' => 'Tester',
        ]);
    }
}
