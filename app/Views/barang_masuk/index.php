<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Barang masuk</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Pembelian / pemasukan stok dari supplier.</p>
    </div>
    <a href="<?= base_url('barang-masuk/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Transaksi baru</a>
</div>
<form method="get" class="mb-4 flex max-w-md gap-2">
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari barang / supplier…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
    <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Cari</button>
</form>
<div class="overflow-x-auto rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Barang</th>
                <th class="px-4 py-3">Supplier</th>
                <th class="px-4 py-3 text-right">Harga beli</th>
                <th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3 w-28">Bukti</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="px-4 py-3 font-mono text-xs"><?= (int) $row['id_masuk'] ?></td>
                    <td class="px-4 py-3 whitespace-nowrap"><?= esc($row['tanggal']) ?></td>
                    <td class="px-4 py-3"><?= esc($row['product_name']) ?></td>
                    <td class="px-4 py-3"><?= esc($row['nama_supplier'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">Rp <?= number_format((float) $row['harga_beli'], 0, ',', '.') ?></td>
                    <td class="px-4 py-3 font-semibold"><?= (int) $row['jumlah'] ?></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="<?= base_url('barang-masuk/receipt/' . (int) $row['id_masuk']) ?>" class="text-neutral-900 underline hover:no-underline dark:text-white" target="_blank" rel="noopener">Cetak</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
