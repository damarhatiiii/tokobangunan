<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Laporan</h1>
    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Ekspor PDF dan cetak laporan dengan filter tanggal (jika tersedia).</p>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    <div class="rounded-md border border-neutral-200 bg-white p-5  dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Stok barang</h2>
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Semua barang &amp; nilai persediaan.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a class="rounded-sm bg-neutral-900 px-3 py-2 text-xs font-semibold text-white hover:bg-neutral-800" href="<?= base_url('reports/stock-pdf') ?>" target="_blank" rel="noopener">Unduh PDF</a>
            <a class="rounded-lg border border-neutral-200 px-3 py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800" href="<?= base_url('reports/stock-pdf') ?>" target="_blank" onclick="window.open(this.href);return false;">Buka tab → Print</a>
        </div>
    </div>

    <div class="rounded-md border border-neutral-200 bg-white p-5  dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Penjualan</h2>
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Filter rentang tanggal.</p>
        <form class="mt-3 grid gap-2 sm:grid-cols-2" method="get" action="<?= base_url('reports/sales-pdf') ?>" target="_blank">
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Dari</label>
                <input type="date" name="from" value="<?= esc(date('Y-m-01')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Sampai</label>
                <input type="date" name="to" value="<?= esc(date('Y-m-d')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div class="sm:col-span-2 flex gap-2 pt-1">
                <button type="submit" class="rounded-sm bg-neutral-900 px-3 py-2 text-xs font-semibold text-white hover:bg-neutral-800">PDF penjualan</button>
            </div>
        </form>
    </div>

    <div class="rounded-md border border-neutral-200 bg-white p-5  dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Barang masuk</h2>
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Histori pemasukan stok.</p>
        <form class="mt-3 grid gap-2 sm:grid-cols-2" method="get" action="<?= base_url('reports/inbound-pdf') ?>" target="_blank">
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Dari</label>
                <input type="date" name="from" value="<?= esc(date('Y-m-01')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Sampai</label>
                <input type="date" name="to" value="<?= esc(date('Y-m-d')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div class="sm:col-span-2 flex gap-2 pt-1">
                <button type="submit" class="rounded-sm bg-neutral-900 px-3 py-2 text-xs font-semibold text-white hover:bg-neutral-800">PDF barang masuk</button>
            </div>
        </form>
    </div>

    <div class="rounded-md border border-neutral-200 bg-white p-5  dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Barang keluar</h2>
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Penjualan / pengeluaran stok.</p>
        <form class="mt-3 grid gap-2 sm:grid-cols-2" method="get" action="<?= base_url('reports/outbound-pdf') ?>" target="_blank">
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Dari</label>
                <input type="date" name="from" value="<?= esc(date('Y-m-01')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Sampai</label>
                <input type="date" name="to" value="<?= esc(date('Y-m-d')) ?>" class="mt-1 w-full rounded-lg border border-neutral-300 px-2 py-1.5 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-white">
            </div>
            <div class="sm:col-span-2 flex gap-2 pt-1">
                <button type="submit" class="rounded-sm bg-neutral-900 px-3 py-2 text-xs font-semibold text-white hover:bg-neutral-800">PDF barang keluar</button>
            </div>
        </form>
    </div>

    <div class="rounded-md border border-neutral-200 bg-white p-5  dark:border-neutral-800 dark:bg-neutral-900">
        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Analisis kebutuhan</h2>
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Forecast &amp; rekomendasi order.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a class="rounded-sm bg-neutral-900 px-3 py-2 text-xs font-semibold text-white hover:bg-neutral-800" href="<?= base_url('reports/forecast-pdf') ?>" target="_blank" rel="noopener">PDF analisis</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
