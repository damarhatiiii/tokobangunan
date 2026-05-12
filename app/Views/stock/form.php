<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white">Input mutasi stok</h1>
<form method="post" action="<?= base_url('stock-movements/store') ?>" class="max-w-lg space-y-4 rounded-md border border-neutral-200 bg-white p-6  dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <div>
        <label class="block text-sm font-medium" for="product_id">Produk</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="product_id" id="product_id" required>
            <?php foreach ($products as $p): ?>
                <option value="<?= (int) $p['id'] ?>"><?= esc($p['sku']) ?> — <?= esc($p['name']) ?> (stok: <?= (int) $p['stock_qty'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="movement_type">Jenis</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="movement_type" id="movement_type" required>
            <option value="in">Masuk</option>
            <option value="out">Keluar</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="quantity">Jumlah</label>
        <input type="number" min="1" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="quantity" id="quantity" value="<?= esc(old('quantity', '1')) ?>" required>
    </div>
    <div>
        <label class="block text-sm font-medium" for="reference">Referensi / No. dokumen</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="reference" id="reference" value="<?= esc(old('reference')) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium" for="note">Catatan</label>
        <textarea class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="note" id="note" rows="2"><?= esc(old('note')) ?></textarea>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('stock-movements') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
