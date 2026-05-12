<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Profil</h1>
    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Nama tampilan, username, dan kata sandi.</p>
</div>
<div class="max-w-lg rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <form method="post" action="<?= base_url('profile/update') ?>" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm font-medium" for="nama_pengguna">Nama tampilan</label>
            <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nama_pengguna" id="nama_pengguna" value="<?= esc(old('nama_pengguna', $user['nama_pengguna'])) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium" for="username">Username</label>
            <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="username" id="username" value="<?= esc(old('username', $user['username'])) ?>" required autocomplete="username">
        </div>
        <div class="rounded-lg border border-neutral-100 bg-neutral-50 p-3 dark:border-neutral-800 dark:bg-neutral-950/40">
            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Ubah password</p>
            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Kosongkan jika tidak mengganti.</p>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium" for="password">Password baru</label>
                    <input type="password" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="password" id="password" autocomplete="new-password">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium" for="password_confirm">Konfirmasi password</label>
                    <input type="password" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="password_confirm" id="password_confirm" autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="flex gap-2 pt-2">
            <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan perubahan</button>
            <a href="<?= base_url('dashboard') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Kembali</a>
        </div>
    </form>
    <dl class="mt-6 border-t border-neutral-100 pt-4 text-sm dark:border-neutral-800">
        <div class="flex justify-between gap-4"><dt class="text-neutral-500">Role</dt><dd class="font-medium uppercase"><?= esc($user['role']) ?></dd></div>
        <div class="mt-2 flex justify-between gap-4"><dt class="text-neutral-500">Status</dt><dd><?= (int) $user['status_aktif'] === 1 ? 'Aktif' : 'Nonaktif' ?></dd></div>
    </dl>
</div>
<?= $this->endSection() ?>
