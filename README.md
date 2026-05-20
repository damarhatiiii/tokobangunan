# Toko Bangunan — Sistem Inventori

Aplikasi inventori toko bangunan berbasis **CodeIgniter 4** (PHP 8.1+): barang, stok, supplier, transaksi masuk/keluar, laporan, dan master data.

## Instalasi

1. `composer install`
2. Salin `env` ke `.env`, sesuaikan `app.baseURL` dan koneksi database
3. Migrasi & seed: `php spark migrate` lalu `php spark db:seed DatabaseSeeder`
4. Arahkan virtual host ke folder `public/`

## Struktur modul Kategori & Satuan

Master **kategori** dan **satuan** dikelola dalam **satu halaman** (`/categories`). Dokumentasi lengkap (diagram, rute, parameter, skema DB):

→ **[docs/STRUKTUR-KATEGORI-SATUAN.md](docs/STRUKTUR-KATEGORI-SATUAN.md)**

Ringkasan:

| Lapisan | Berkas utama |
|---------|----------------|
| Controller | `app/Controllers/Categories.php`, `app/Controllers/Satuan.php` |
| Model | `app/Models/CategoryModel.php`, `app/Models/SatuanModel.php` |
| View | `app/Views/categories/index.php` (gabungan), `form.php`, `app/Views/satuan/form.php` |

## Pengujian whitebox

Pengujian **whitebox** memeriksa logika internal (model, controller, redirect, filter auth) tanpa UI manual. Database uji memakai **SQLite in-memory** (`app/Config/Database.php` → grup `tests`).

### Prasyarat PHPUnit

Aktifkan ekstensi **sqlite3** di `php.ini` (XAMPP: hapus `;` pada baris `extension=sqlite3`), lalu restart Apache/PHP.

```bash
php -m | findstr sqlite3
```

### Menjalankan tes

```bash
composer install
php -d extension=sqlite3 vendor\bin\phpunit
```

Hanya modul kategori & satuan:

```bash
php -d extension=sqlite3 vendor\bin\phpunit tests/unit/CategoryModelTest.php tests/unit/SatuanModelTest.php tests/feature/CategoriesFeatureTest.php tests/feature/SatuanFeatureTest.php
```

> Jika `sqlite3` sudah aktif permanen di `php.ini`, flag `-d extension=sqlite3` tidak diperlukan.

### Matriks whitebox

| ID | Kelas uji | Metode | Yang diuji (jalur internal) |
|----|-----------|--------|-----------------------------|
| W-KM-01 | `CategoryModelTest` | `testSearchFiltersByNamaKategori` | `CategoryModel::search()` + `like('nama_kategori')` |
| W-KM-02 | `CategoryModelTest` | `testInsertRejectsNamaTerlaluPendek` | Aturan validasi `min_length[2]` |
| W-KM-03 | `CategoryModelTest` | `testInsertValidDataPersists` | `insert()` + `find()` |
| W-SM-01 | `SatuanModelTest` | `testSearchFiltersByNamaSatuan` | `SatuanModel::search()` |
| W-SM-02 | `SatuanModelTest` | `testAsMapReturnsIdToNama` | `SatuanModel::asMap()` untuk dropdown |
| W-SM-03 | `SatuanModelTest` | `testInsertRejectsEmptyNama` | Validasi `required` |
| W-CF-01 | `CategoriesFeatureTest` | `testIndexRequiresLogin` | `AuthFilter` → redirect `/login` |
| W-CF-02 | `CategoriesFeatureTest` | `testIndexRendersCombinedPage` | `Categories::index` mengirim `kategori` + `satuan` ke view |
| W-CF-03 | `CategoriesFeatureTest` | `testIndexFiltersKategoriWithQk` | Cabang `qk !== ''` → `search($qk)` |
| W-CF-04 | `CategoriesFeatureTest` | `testIndexFiltersSatuanWithQs` | Cabang `qs !== ''` → `search($qs)` |
| W-CF-05 | `CategoriesFeatureTest` | `testStoreRedirectsToCategoriesOnSuccess` | `Categories::store` → redirect + flash `message` |
| W-SF-01 | `SatuanFeatureTest` | `testIndexRedirectsToCategories` | `Satuan::index` → `redirect('/categories')` |
| W-SF-02 | `SatuanFeatureTest` | `testStoreRedirectsToCategoriesOnSuccess` | `Satuan::store` → `/categories` |
| W-SF-03 | `SatuanFeatureTest` | `testUpdateRedirectsToCategoriesOnSuccess` | `Satuan::update` → `/categories` |

### Dukungan tes

| Berkas | Peran |
|--------|--------|
| `tests/_support/MasterDataTestCase.php` | Migrasi, seed, session petugas |
| `tests/_support/Database/Migrations/2026-05-19-100000_KategoriSatuanTables.php` | Tabel `kategori`, `satuan` di SQLite uji |
| `tests/_support/Database/Seeds/MasterDataSeeder.php` | Data contoh (Semen, Besi, PCS, zak, …) |

Panduan umum PHPUnit CI4: [tests/README.md](tests/README.md).

## Persyaratan server

- PHP 8.1+, ekstensi `intl`, `mbstring`, `json`
- MySQL/MariaDB (produksi); SQLite3 untuk PHPUnit

## Lisensi

MIT (CodeIgniter 4 App Starter).
