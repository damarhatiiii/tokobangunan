<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="mb-6 text-2xl font-semibold text-neutral-900 dark:text-white"><?= esc((isset($row) && $row) ? 'Edit kategori' : 'Tambah kategori') ?></h1>
<form method="post" action="<?= (isset($row) && $row) ? base_url('categories/update/' . $row['id_kategori']) : base_url('categories/store') ?>" class="max-w-lg space-y-4 rounded-md border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
    <?= csrf_field() ?>
    <div>
        <label class="block text-sm font-medium" for="nama_kategori">Nama kategori</label>
        <input class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white" name="nama_kategori" id="nama_kategori" value="<?= esc(old('nama_kategori', (isset($row) && is_array($row)) ? $row['nama_kategori'] : '')) ?>" required>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="rounded-sm bg-neutral-900 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
        <a href="<?= base_url('categories') ?>" class="rounded-lg border border-neutral-200 px-4 py-2 text-sm dark:border-neutral-600">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
