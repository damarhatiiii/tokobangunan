<?php
$role   = (string) (session()->get('user_role') ?? '');
$canOps = in_array($role, ['admin', 'petugas'], true);
?>
<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Data barang</h1>
    <?php if ($canOps): ?>
        <a href="<?= base_url('products/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Tambah barang</a>
    <?php endif; ?>
</div>
<form method="get" class="mb-4 flex max-w-md gap-2">
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari nama barang…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
    <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Cari</button>
</form>
<div class="overflow-x-auto rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Satuan</th>
                <th class="px-4 py-3">Stok</th>
                <th class="px-4 py-3">Ref. beli</th>
                <th class="px-4 py-3">Ref. jual</th>
                <th class="px-4 py-3 w-44"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="px-4 py-3 font-mono text-xs"><?= (int) $row['id_barang'] ?></td>
                    <td class="px-4 py-3 font-medium"><?= esc($row['nama_barang']) ?></td>
                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400"><?= esc($row['nama_kategori'] ?? '') ?></td>
                    <td class="px-4 py-3"><?= esc($row['nama_satuan'] ?? '') ?></td>
                    <td class="px-4 py-3"><?= (int) $row['stok'] ?></td>
                    <td class="px-4 py-3"><?= $row['referensi_harga_beli'] !== null ? 'Rp ' . number_format((float) $row['referensi_harga_beli'], 0, ',', '.') : '—' ?></td>
                    <td class="px-4 py-3"><?= $row['referensi_harga_jual'] !== null ? 'Rp ' . number_format((float) $row['referensi_harga_jual'], 0, ',', '.') : '—' ?></td>
                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                        <a href="<?= base_url('products/show/' . $row['id_barang']) ?>" class="text-neutral-900 hover:underline dark:text-white">Detail</a>
                        <?php if ($canOps): ?>
                            <a href="<?= base_url('products/edit/' . $row['id_barang']) ?>" class="text-neutral-700 hover:underline dark:text-neutral-300">Edit</a>
                            <a href="<?= base_url('products/delete/' . $row['id_barang']) ?>" class="text-red-600 hover:underline dark:text-red-400" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?= $pager->links() ?></div>
<p class="mt-4 text-xs text-neutral-500 dark:text-neutral-400">Referensi beli/jual diambil dari transaksi masuk/keluar terakhir untuk barang tersebut.</p>
<?= $this->endSection() ?>
