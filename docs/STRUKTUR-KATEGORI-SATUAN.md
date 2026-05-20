# Struktur Modul Kategori & Satuan

Modul master data untuk mengelola **kategori barang** dan **satuan** dalam satu halaman (`/categories`).

## Diagram alur

```mermaid
flowchart TB
    subgraph UI
        NAV[Sidebar / Topbar<br/>Kategori & Satuan]
        IDX[categories/index.php<br/>2 kolom: Kategori | Satuan]
        FKC[categories/form.php]
        FKS[satuan/form.php]
    end

    subgraph Controller
        CAT[Categories.php]
        SAT[Satuan.php]
    end

    subgraph Model
        MK[CategoryModel → kategori]
        MS[SatuanModel → satuan]
    end

    subgraph DB
        TK[(kategori)]
        TS[(satuan)]
    end

    NAV --> IDX
    IDX --> CAT
    CAT --> MK
    CAT --> MS
    MK --> TK
    MS --> TS
    IDX -->|Tambah/Edit kategori| FKC --> CAT
    IDX -->|Tambah/Edit satuan| FKS --> SAT
    SAT --> MS
    SAT -->|GET /satuan| CAT
```

## Struktur berkas

```
app/
├── Controllers/
│   ├── Categories.php      # index gabungan + CRUD kategori
│   └── Satuan.php          # CRUD satuan; index → redirect /categories
├── Models/
│   ├── CategoryModel.php   # tabel kategori, search(), validasi
│   └── SatuanModel.php     # tabel satuan, search(), asMap()
├── Views/
│   └── categories/
│       ├── index.php       # halaman gabungan (qk / qs untuk cari)
│       └── form.php
│   └── satuan/
│       └── form.php
└── Config/
    └── Routes.php          # categories/*, satuan/*

tests/
├── _support/
│   ├── MasterDataTestCase.php
│   └── Database/
│       ├── Migrations/2026-05-19-100000_KategoriSatuanTables.php
│       └── Seeds/MasterDataSeeder.php
├── unit/
│   ├── CategoryModelTest.php
│   └── SatuanModelTest.php
└── feature/
    ├── CategoriesFeatureTest.php
    └── SatuanFeatureTest.php
```

## Rute

| Method | URI | Controller | Keterangan |
|--------|-----|------------|------------|
| GET | `/categories` | `Categories::index` | Halaman gabungan |
| GET/POST | `/categories/create`, `store`, `edit/(:num)`, `update/(:num)`, `delete/(:num)` | `Categories` | CRUD kategori |
| GET | `/satuan` | `Satuan::index` | Redirect ke `/categories` |
| GET/POST | `/satuan/create`, `store`, `edit/(:num)`, `update/(:num)`, `delete/(:num)` | `Satuan` | CRUD satuan |

Semua rute di atas memakai filter `auth` + `roles:admin,petugas`.

## Parameter halaman gabungan

| Parameter | Fungsi |
|-----------|--------|
| `qk` | Filter daftar kategori (`CategoryModel::search`) |
| `qs` | Filter daftar satuan (`SatuanModel::search`) |

Kedua parameter dapat dikombinasikan; form cari menyimpan parameter lawan via `<input type="hidden">`.

## Skema database

**kategori**

| Kolom | Tipe |
|-------|------|
| id_kategori | INT PK AI |
| nama_kategori | VARCHAR(100) |

**satuan**

| Kolom | Tipe |
|-------|------|
| id_satuan | INT PK AI |
| nama_satuan | VARCHAR(50) |

Tabel `barang` mereferensi keduanya via FK (`id_kategori`, `id_satuan`).

## Logika penting (whitebox)

1. **Categories::index** — memuat dua query terpisah; `qk`/`qs` kosong = semua data (`findAll`).
2. **Satuan::index** — hanya `redirect()->to('/categories')`.
3. **Setelah simpan/update/hapus satuan** — redirect ke `/categories` (bukan `/satuan`).
4. **Hapus** — dibungkus `try/catch`; gagal jika masih dipakai barang (pesan flash error).
