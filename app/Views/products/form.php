<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white"><?= esc((isset($row) && $row) ? 'Edit barang' : 'Tambah barang') ?></h1>
<form method="post" action="<?= (isset($row) && $row) ? base_url('products/update/' . $row['id_barang']) : base_url('products/store') ?>" class="max-w-2xl space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <?php $r = (isset($row) && is_array($row)) ? $row : []; ?>
    <div>
        <label class="block text-sm font-medium" for="nama_barang">Nama barang</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nama_barang" id="nama_barang" value="<?= esc(old('nama_barang', $r['nama_barang'] ?? '')) ?>" required>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium" for="id_kategori">Kategori</label>
            <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="id_kategori" id="id_kategori" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id_kategori'] ?>" <?= (string) old('id_kategori', $r['id_kategori'] ?? '') === (string) $c['id_kategori'] ? 'selected' : '' ?>><?= esc($c['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium" for="id_satuan">Satuan</label>
            <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="id_satuan" id="id_satuan" required>
                <?php foreach ($satuan as $s): ?>
                    <option value="<?= (int) $s['id_satuan'] ?>" <?= (string) old('id_satuan', $r['id_satuan'] ?? '') === (string) $s['id_satuan'] ? 'selected' : '' ?>><?= esc($s['nama_satuan']) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-neutral-500"><a href="<?= base_url('categories') ?>" class="underline">Kelola kategori &amp; satuan</a></p>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium" for="stok">Stok awal</label>
        <input type="number" min="0" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="stok" id="stok" value="<?= esc(old('stok', isset($r['stok']) ? (string) $r['stok'] : '0')) ?>">
        <p class="mt-1 text-xs text-neutral-500">Sesuaikan stok via transaksi <strong>Masuk</strong> / <strong>Keluar</strong> agar konsisten dengan histori.</p>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('products') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
