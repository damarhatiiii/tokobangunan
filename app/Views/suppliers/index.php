<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Supplier</h1>
    <a href="<?= base_url('suppliers/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Tambah supplier</a>
</div>
<form method="get" class="mb-4 flex max-w-md gap-2">
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari nama / telepon…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
    <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Cari</button>
</form>
<div class="overflow-hidden rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
            <tr>
                <th class="px-4 py-3">Nama supplier</th>
                <th class="px-4 py-3">Telepon</th>
                <th class="px-4 py-3 hidden sm:table-cell">Alamat</th>
                <th class="px-4 py-3 w-36"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="px-4 py-3 font-medium"><?= esc($row['nama_supplier']) ?></td>
                    <td class="px-4 py-3"><?= esc($row['nomor_telepon'] ?? '') ?></td>
                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400 hidden sm:table-cell"><?= esc($row['alamat'] ?? '') ?></td>
                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                        <a href="<?= base_url('suppliers/edit/' . $row['id_supplier']) ?>" class="text-neutral-900 hover:underline dark:text-white">Edit</a>
                        <a href="<?= base_url('suppliers/delete/' . $row['id_supplier']) ?>" class="text-red-600 hover:underline dark:text-red-400" onclick="return confirm('Hapus supplier ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
