<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Manajemen pengguna</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Role: <strong>admin</strong> (termasuk CRUD pengguna), <strong>petugas</strong> (operasi barang &amp; transaksi).</p>
    </div>
    <a href="<?= base_url('users/create') ?>" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Tambah pengguna</a>
</div>
<form method="get" class="mb-4 flex max-w-md gap-2">
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari nama / username…" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
    <button type="submit" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Cari</button>
</form>
<div class="overflow-x-auto rounded-md border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/80 dark:text-neutral-400">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Username</th>
                <th class="px-4 py-3">Role</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="px-4 py-3 font-medium"><?= esc($row['nama_pengguna']) ?></td>
                    <td class="px-4 py-3 font-mono text-xs"><?= esc($row['username']) ?></td>
                    <td class="px-4 py-3"><span class="rounded-full border border-neutral-300 bg-stone-100 px-2 py-0.5 text-xs font-semibold uppercase text-neutral-800 dark:border-neutral-600 dark:bg-neutral-800 dark:text-stone-200"><?= esc($row['role']) ?></span></td>
                    <td class="px-4 py-3"><?= (int) $row['status_aktif'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                        <a href="<?= base_url('users/edit/' . $row['id_pengguna']) ?>" class="text-neutral-900 hover:underline dark:text-white">Edit</a>
                        <form method="post" action="<?= base_url('users/delete/' . $row['id_pengguna']) ?>" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-red-600 hover:underline dark:text-red-400 bg-transparent border-none p-0 cursor-pointer text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
