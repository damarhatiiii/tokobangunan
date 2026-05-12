<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Mutasi / penyesuaian</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Gunakan halaman ini untuk penyesuaian stok. Untuk pembelian resmi gunakan menu Barang Masuk.</p>
    </div>
    <a href="<?= base_url('stock-movements/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Input mutasi</a>
</div>
<form method="get" class="mb-4 flex max-w-md gap-2">
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari barang / kode trx…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
    <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Cari</button>
</form>
<div class="overflow-x-auto rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
            <tr>
                <th class="px-4 py-3">Waktu</th>
                <th class="px-4 py-3">Barang</th>
                <th class="px-4 py-3">Jenis</th>
                <th class="px-4 py-3">Qty</th>
                <th class="px-4 py-3">Supplier</th>
                <th class="px-4 py-3">Ref / Kode</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-neutral-500"><?= esc($row['created_at']) ?></td>
                    <td class="px-4 py-3"><?= esc($row['product_name']) ?> <span class="font-mono text-xs text-neutral-500">(<?= esc($row['sku']) ?>)</span></td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium <?= $row['movement_type'] === 'in' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : ($row['movement_type'] === 'out' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-slate-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200') ?>"><?= esc($row['movement_type']) ?></span>
                    </td>
                    <td class="px-4 py-3 font-medium"><?= (int) $row['quantity'] ?></td>
                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400"><?= esc($row['supplier_name'] ?? '—') ?></td>
                    <td class="px-4 py-3 font-mono text-xs"><?= esc($row['transaction_code'] ?? $row['reference'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
