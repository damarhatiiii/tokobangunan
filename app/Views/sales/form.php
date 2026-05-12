<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white">Barang keluar</h1>
<form method="post" action="<?= base_url('sales/store') ?>" class="max-w-lg space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <?php
        $first = $products[0] ?? null;
        $defaultHarga = old('harga_jual', $first['default_harga_jual'] ?? '');
    ?>
    <p class="rounded-md border border-neutral-200 bg-stone-100 px-3 py-2 text-xs text-neutral-800 dark:border-neutral-600 dark:bg-neutral-800/80 dark:text-stone-300">Harga diisi otomatis dari <strong>penjualan terakhir</strong> barang itu; kalau belum pernah terjual, dari <strong>harga beli terakhir +15%</strong> (boleh diubah).</p>
    <div>
        <label class="block text-sm font-medium" for="id_barang">Barang</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="id_barang" id="id_barang" required>
            <?php foreach ($products as $p): ?>
                <option value="<?= (int) $p['id_barang'] ?>" data-default-harga="<?= esc($p['default_harga_jual'] ?? '', 'attr') ?>"><?= esc($p['nama_barang']) ?> — <?= esc($p['nama_satuan'] ?? '') ?> — stok: <?= (int) $p['stok'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="tanggal">Tanggal</label>
        <input type="date" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="tanggal" id="tanggal" value="<?= esc(old('tanggal', date('Y-m-d'))) ?>" required>
    </div>
    <div>
        <label class="block text-sm font-medium" for="jumlah">Jumlah</label>
        <input type="number" min="1" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="jumlah" id="jumlah" value="<?= esc(old('jumlah', '1')) ?>" required>
    </div>
    <div>
        <label class="block text-sm font-medium" for="harga_jual">Harga jual (per satuan)</label>
        <input type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="harga_jual" id="harga_jual" value="<?= esc($defaultHarga) ?>" required>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan transaksi</button>
        <a href="<?= base_url('sales') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<script>
(function () {
    var sel = document.getElementById('id_barang');
    var inp = document.getElementById('harga_jual');
    if (!sel || !inp) return;
    sel.addEventListener('change', function () {
        var opt = sel.selectedOptions[0];
        var v = opt && opt.getAttribute('data-default-harga');
        if (v !== null && v !== '') inp.value = v;
    });
})();
</script>
<?= $this->endSection() ?>
