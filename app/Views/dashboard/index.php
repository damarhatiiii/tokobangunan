<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-2xl font-semibold tracking-tight text-ink dark:text-stone-100">Dashboard</h1>
        <p class="text-sm text-stone-600 dark:text-stone-400">Ringkasan stok, penjualan, supplier, dan indikator operasional.</p>
    </div>
    <a href="<?= base_url('analysis') ?>" class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-800 shadow-sm hover:bg-stone-50 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-200 dark:hover:bg-stone-700">Analisis kebutuhan</a>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <p class="text-xs font-medium uppercase tracking-wide text-stone-500 dark:text-stone-500">Total barang</p>
        <p class="mt-1 font-display text-2xl font-semibold text-ink dark:text-stone-100"><?= number_format((int) $totalProducts) ?></p>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <p class="text-xs font-medium uppercase tracking-wide text-stone-500 dark:text-stone-500">Total supplier</p>
        <p class="mt-1 font-display text-2xl font-semibold text-ink dark:text-stone-100"><?= number_format((int) $totalSuppliers) ?></p>
    </div>
    <div class="rounded-md border border-amber-900/15 bg-gradient-to-br from-amber-50 to-orange-50/80 p-4 shadow-lift dark:border-amber-800/30 dark:from-amber-950/40 dark:to-stone-900">
        <p class="text-xs font-medium uppercase tracking-wide text-amber-950/80 dark:text-amber-200/90">Penjualan bulan ini</p>
        <p class="mt-1 font-display text-2xl font-bold text-stone-900 dark:text-stone-100">Rp <?= number_format((float) $salesMonthRevenue, 0, ',', '.') ?></p>
        <p class="mt-1 text-xs text-stone-600 dark:text-stone-400"><?= number_format((int) $salesMonthCount) ?> transaksi</p>
    </div>
    <div class="rounded-md border border-amber-800/20 bg-amber-50/90 p-4 shadow-lift dark:border-amber-800/40 dark:bg-amber-950/35">
        <p class="text-xs font-medium uppercase tracking-wide text-amber-800 dark:text-amber-300">Stok rendah</p>
        <p class="mt-1 text-2xl font-bold text-amber-900 dark:text-amber-200"><?= number_format((int) $lowStockCount) ?></p>
        <p class="mt-1 text-xs text-amber-900/70 dark:text-amber-300/90">Stok ≤ <?= (int) $lowStockMax ?> unit</p>
    </div>
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <p class="text-xs font-medium uppercase text-stone-600 dark:text-stone-400">Barang masuk (bulan ini)</p>
        <p class="mt-1 text-xl font-semibold text-ink dark:text-stone-100"><?= number_format((int) $inboundMonthCount) ?></p>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <p class="text-xs font-medium uppercase text-stone-600 dark:text-stone-400">Kategori aktif</p>
        <p class="mt-1 text-xl font-semibold text-ink dark:text-stone-100"><?= number_format((int) $categoriesCount) ?></p>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift sm:col-span-2 dark:border-stone-700 dark:bg-stone-900">
        <p class="text-xs font-medium uppercase text-stone-600 dark:text-stone-400">Nilai persediaan (referensi)</p>
        <p class="mt-1 text-xl font-semibold text-ink dark:text-stone-100">Rp <?= number_format((float) $stockValue, 0, ',', '.') ?></p>
        <p class="mt-1 text-xs text-stone-500 dark:text-stone-400">Per barang: harga beli dari transaksi masuk terakhir.</p>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Grafik penjualan bulanan</h2>
        <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">Omzet 12 bulan terakhir.</p>
        <div class="mt-4 h-72">
            <canvas id="chartMonthlyRev"></canvas>
        </div>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Barang terlaris (90 hari)</h2>
        <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">Berdasarkan quantity terjual.</p>
        <div class="mt-4 h-72">
            <canvas id="chartTopSelling"></canvas>
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Stok per kategori</h2>
        <div class="mt-4 h-72">
            <canvas id="chartCat"></canvas>
        </div>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Stok terbesar (TOP 10)</h2>
        <div class="mt-4 h-72">
            <canvas id="chartStockTop"></canvas>
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Penjualan 7 hari terakhir</h2>
        <div class="mt-4 h-64">
            <canvas id="chartQty"></canvas>
        </div>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Stok rendah (≤ <?= (int) $lowStockMax ?>)</h2>
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-stone-200 text-xs uppercase text-stone-500 dark:border-stone-700">
                    <tr><th class="py-2 pr-4">ID</th><th class="py-2 pr-4">Nama</th><th class="py-2">Stok</th><th class="py-2">Satuan</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
                    <?php if (empty($lowStock)): ?>
                        <tr><td colspan="4" class="py-4 text-slate-500">Tidak ada barang di ambang ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($lowStock as $p): ?>
                            <tr>
                                <td class="py-2 pr-4 font-mono text-xs"><?= (int) $p['id_barang'] ?></td>
                                <td class="py-2 pr-4"><?= esc($p['nama_barang']) ?></td>
                                <td class="py-2 font-semibold text-amber-600 dark:text-amber-400"><?= (int) $p['stok'] ?></td>
                                <td class="py-2"><?= esc($p['nama_satuan'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-6 rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
    <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Forecast — rekomendasi pembelian</h2>
    <p class="mt-1 text-xs text-slate-500">Proyeksi 30 hari dari rata-rata keluar harian 90 hari terakhir; saran order = max(0, proyeksi − stok).</p>
    <ul class="mt-3 grid gap-2 md:grid-cols-2">
        <?php foreach ($forecastTop as $f): ?>
            <li class="flex justify-between gap-2 rounded-md border border-stone-200/80 bg-white px-3 py-2 text-sm dark:border-stone-700 dark:bg-stone-800/50">
                <span class="truncate"><?= esc($f['name']) ?></span>
                <span class="shrink-0 font-semibold tabular-nums text-accent dark:text-orange-400"><?= (int) $f['suggested_order'] ?> unit</span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const isDark = document.documentElement.classList.contains('dark');
const grid = isDark ? 'rgba(120,113,108,0.22)' : 'rgba(41,37,36,0.07)';
const tick = isDark ? '#a8a29e' : '#57534e';
const rust = '#b45309';
const rustFill = 'rgba(180, 83, 9, 0.14)';

new Chart(document.getElementById('chartMonthlyRev'), {
    type: 'line',
    data: {
        labels: <?= json_encode($monthlyLabels) ?>,
        datasets: [{
            label: 'Omzet (Rp)',
            data: <?= json_encode($monthlyRev) ?>,
            borderColor: rust,
            backgroundColor: rustFill,
            fill: true,
            tension: 0.35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: tick } } },
        scales: {
            x: { grid: { color: grid }, ticks: { color: tick } },
            y: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('chartTopSelling'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topSelling, 'name')) ?>,
        datasets: [{
            label: 'Qty terjual',
            data: <?= json_encode(array_map('intval', array_column($topSelling, 'qty'))) ?>,
            backgroundColor: '#d97706'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true },
            y: { grid: { display: false }, ticks: { color: tick } }
        }
    }
});

new Chart(document.getElementById('chartCat'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($byCategory, 'label')) ?>,
        datasets: [{
            label: 'Stok (qty)',
            data: <?= json_encode(array_map('intval', array_column($byCategory, 'qty'))) ?>,
            backgroundColor: ['#b45309','#78716c','#57534e','#d97706','#a8a29e','#44403c']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { color: tick } },
            y: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('chartStockTop'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topStock, 'name')) ?>,
        datasets: [{
            label: 'Stok',
            data: <?= json_encode(array_map('intval', array_column($topStock, 'stock_qty'))) ?>,
            backgroundColor: '#a8a29e'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true },
            y: { grid: { display: false }, ticks: { color: tick } }
        }
    }
});

new Chart(document.getElementById('chartQty'), {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Qty terjual',
            data: <?= json_encode($chartQty) ?>,
            borderColor: '#92400e',
            backgroundColor: 'rgba(146, 64, 14, 0.12)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: tick } } },
        scales: {
            x: { grid: { color: grid }, ticks: { color: tick } },
            y: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true }
        }
    }
});
</script>
<?= $this->endSection() ?>
