<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white"><?= esc((isset($row) && $row) ? 'Edit pengguna' : 'Tambah pengguna') ?></h1>
<form method="post" action="<?= (isset($row) && $row) ? base_url('users/update/' . $row['id_pengguna']) : base_url('users/store') ?>" class="max-w-lg space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <?php $r = (isset($row) && is_array($row)) ? $row : []; ?>
    <div>
        <label class="block text-sm font-medium" for="nama_pengguna">Nama tampilan</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nama_pengguna" id="nama_pengguna" value="<?= esc(old('nama_pengguna', $r['nama_pengguna'] ?? '')) ?>" required>
    </div>
    <div>
        <label class="block text-sm font-medium" for="username">Username</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="username" id="username" value="<?= esc(old('username', $r['username'] ?? '')) ?>" required autocomplete="username">
    </div>
    <div>
        <label class="block text-sm font-medium" for="role">Role</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="role" id="role" required>
            <?php foreach (['admin' => 'Admin', 'petugas' => 'Petugas'] as $val => $label): ?>
                <option value="<?= esc($val, 'attr') ?>" <?= (string) old('role', $r['role'] ?? 'petugas') === $val ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="status_aktif">Status</label>
        <select class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="status_aktif" id="status_aktif" required>
            <?php $stDef = $r !== [] ? (string) (int) $r['status_aktif'] : '1'; ?>
            <option value="1" <?= (string) old('status_aktif', $stDef) === '1' ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= (string) old('status_aktif', $stDef) === '0' ? 'selected' : '' ?>>Nonaktif</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium" for="password"><?= $row ? 'Password baru (opsional)' : 'Password' ?></label>
        <input type="password" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="password" id="password" <?= (isset($row) && $row) ? '' : 'required' ?> autocomplete="new-password">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('users') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
