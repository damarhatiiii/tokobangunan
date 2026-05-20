<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kategori &amp; Satuan</h1>
    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Kelola master kategori dan satuan barang.</p>
</div>
<div class="grid gap-6 lg:grid-cols-2">
    <section>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Kategori</h2>
            <a href="<?= base_url('categories/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Tambah</a>
        </div>
        <form method="get" class="mb-4 flex gap-2">
            <?php if ($qs !== ''): ?>
                <input type="hidden" name="qs" value="<?= esc($qs) ?>">
            <?php endif; ?>
            <input type="search" name="qk" value="<?= esc($qk) ?>" placeholder="Cari kategori…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm font-medium dark:border-neutral-600">Cari</button>
        </form>
        <div class="overflow-hidden rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
                    <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3 w-40"></th></tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    <?php if ($kategori === []): ?>
                        <tr><td colspan="2" class="px-4 py-6 text-center text-neutral-500">Belum ada kategori.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($kategori as $row): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?= esc($row['nama_kategori']) ?></td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="<?= base_url('categories/edit/' . $row['id_kategori']) ?>" class="text-neutral-900 hover:underline dark:text-white">Edit</a>
                                <a href="<?= base_url('categories/delete/' . $row['id_kategori']) ?>" class="text-red-600 hover:underline dark:text-red-400" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Satuan</h2>
            <a href="<?= base_url('satuan/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Tambah</a>
        </div>
        <form method="get" class="mb-4 flex gap-2">
            <?php if ($qk !== ''): ?>
                <input type="hidden" name="qk" value="<?= esc($qk) ?>">
            <?php endif; ?>
            <input type="search" name="qs" value="<?= esc($qs) ?>" placeholder="Cari satuan…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm font-medium dark:border-neutral-600">Cari</button>
        </form>
        <div class="overflow-hidden rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
                    <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3 w-40"></th></tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    <?php if ($satuan === []): ?>
                        <tr><td colspan="2" class="px-4 py-6 text-center text-neutral-500">Belum ada satuan.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($satuan as $row): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?= esc($row['nama_satuan']) ?></td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="<?= base_url('satuan/edit/' . $row['id_satuan']) ?>" class="text-neutral-900 hover:underline dark:text-white">Edit</a>
                                <a href="<?= base_url('satuan/delete/' . $row['id_satuan']) ?>" class="text-red-600 hover:underline dark:text-red-400" onclick="return confirm('Hapus satuan ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
