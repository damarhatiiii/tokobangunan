<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white"><?= esc((isset($row) && $row) ? 'Edit supplier' : 'Tambah supplier') ?></h1>
<form method="post" action="<?= (isset($row) && $row) ? base_url('suppliers/update/' . $row['id_supplier']) : base_url('suppliers/store') ?>" class="max-w-lg space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <?php $r = (isset($row) && is_array($row)) ? $row : []; ?>
    <div>
        <label class="block text-sm font-medium" for="nama_supplier">Nama</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nama_supplier" id="nama_supplier" value="<?= esc(old('nama_supplier', $r['nama_supplier'] ?? '')) ?>" required>
    </div>
    <div>
        <label class="block text-sm font-medium" for="nomor_telepon">No. telepon</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nomor_telepon" id="nomor_telepon" value="<?= esc(old('nomor_telepon', $r['nomor_telepon'] ?? '')) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium" for="alamat">Alamat</label>
        <textarea class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="alamat" id="alamat" rows="3"><?= esc(old('alamat', $r['alamat'] ?? '')) ?></textarea>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('suppliers') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
