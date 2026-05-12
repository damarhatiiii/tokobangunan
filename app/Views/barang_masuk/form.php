<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-2 text-2xl font-semibold text-neutral-900 dark:text-white">Tambah barang masuk</h1>
<p class="mb-6 text-sm text-neutral-500 dark:text-neutral-400">Stok barang bertambah otomatis sesuai jumlah.</p>
<form method="post" action="<?= base_url('barang-masuk/store') ?>" class="max-w-xl space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <div>
        <label class="block text-sm font-medium" for="id_supplier">Supplier</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="id_supplier" id="id_supplier" required>
            <option value="">— Pilih supplier —</option>
            <?php foreach ($suppliers as $s): ?>
                <option value="<?= (int) $s['id_supplier'] ?>" <?= (string) old('id_supplier') === (string) $s['id_supplier'] ? 'selected' : '' ?>><?= esc($s['nama_supplier']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="id_barang">Barang</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="id_barang" id="id_barang" required>
            <?php foreach ($products as $p): ?>
                <option value="<?= (int) $p['id_barang'] ?>" <?= (string) old('id_barang') === (string) $p['id_barang'] ? 'selected' : '' ?>><?= esc($p['nama_barang']) ?> — stok: <?= (int) $p['stok'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium" for="jumlah">Jumlah</label>
            <input type="number" min="1" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="jumlah" id="jumlah" value="<?= esc(old('jumlah', '1')) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium" for="harga_beli">Harga beli (per satuan)</label>
            <input type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="harga_beli" id="harga_beli" value="<?= esc(old('harga_beli')) ?>" required>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium" for="tanggal">Tanggal</label>
        <input type="date" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="tanggal" id="tanggal" value="<?= esc(old('tanggal', date('Y-m-d'))) ?>" required>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('barang-masuk') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
