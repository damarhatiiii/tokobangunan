<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Database\Seeds\DatabaseSeeder;

abstract class AppTestCase extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh     = true;
    protected $migrate     = true;
    protected $namespace   = 'App'; // Scan App\Database\Migrations for full schema
    protected $seed        = DatabaseSeeder::class; // Seeds UserSeeder & DemoSeeder

    protected function setUp(): void
    {
        if (! extension_loaded('sqlite3')) {
            $this->markTestSkipped('Ekstensi PHP sqlite3 belum aktif. Aktifkan di php.ini.');
        }

        parent::setUp();

        $filters = config('Filters');
        $filters->globals['before'] = array_values(array_filter(
            $filters->globals['before'],
            static fn ($f) => $f !== 'csrf',
        ));
    }

    protected function sessionAsAdmin(): self
    {
        return $this->withSession([
            'user_id'    => 1,
            'user_name'  => 'Administrator',
            'user_role'  => 'admin',
            'username'   => 'admin',
        ]);
    }

    protected function sessionAsOperator(): self
    {
        return $this->withSession([
            'user_id'    => 2,
            'user_name'  => 'Petugas Gudang',
            'user_role'  => 'petugas',
            'username'   => 'petugas',
        ]);
    }
}
