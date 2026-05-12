<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Detail barang</h1>
        <p class="mt-1 font-mono text-sm text-neutral-500 dark:text-neutral-400">#<?= (int) $row['id_barang'] ?></p>
    </div>
    <div class="flex gap-2">
        <?php $role = (string) (session()->get('user_role') ?? ''); ?>
        <?php if (in_array($role, ['admin', 'petugas'], true)): ?>
            <a href="<?= base_url('products/edit/' . $row['id_barang']) ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Edit</a>
        <?php endif; ?>
        <a href="<?= base_url('products') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Kembali</a>
    </div>
</div>

<div class="grid gap-4 lg:grid-cols-2">
    <div class="rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Informasi</h2>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Nama</dt><dd class="font-medium text-right"><?= esc($row['nama_barang']) ?></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Kategori</dt><dd class="text-right"><?= esc($row['nama_kategori'] ?? '') ?></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Satuan</dt><dd class="text-right"><?= esc($row['nama_satuan'] ?? '') ?></dd></div>
        </dl>
    </div>
    <div class="rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Stok &amp; referensi harga</h2>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Stok</dt><dd class="text-right font-semibold"><?= (int) $row['stok'] ?></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Ref. harga beli (masuk terakhir)</dt><dd class="text-right"><?= $row['referensi_harga_beli'] !== null ? 'Rp ' . number_format((float) $row['referensi_harga_beli'], 0, ',', '.') : '—' ?></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-neutral-500">Ref. harga jual (keluar terakhir)</dt><dd class="text-right"><?= $row['referensi_harga_jual'] !== null ? 'Rp ' . number_format((float) $row['referensi_harga_jual'], 0, ',', '.') : '—' ?></dd></div>
        </dl>
    </div>
</div>
<?= $this->endSection() ?>
