<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-2xl font-semibold tracking-tight text-ink dark:text-stone-100">Analisis kebutuhan barang</h1>
        <p class="text-sm text-stone-600 dark:text-stone-400">Keluar harian sebagai dasar forecast; kolom SKU/stok minimum tidak ada di DB inventaris umum ini.</p>
    </div>
    <a href="<?= base_url('reports/forecast-pdf') ?>" target="_blank" class="rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent-hover">PDF analisis</a>
</div>

<div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
    <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Kebutuhan penjualan per bulan</h2>
    <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">Agregasi quantity barang keluar (12 bulan).</p>
    <div class="mt-4 h-72">
        <canvas id="chartNeed"></canvas>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Barang paling laku (90 hari)</h2>
        <div class="mt-4 h-64">
            <canvas id="chartFast"></canvas>
        </div>
    </div>
    <div class="rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
        <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Slow moving (90 hari)</h2>
        <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">Stok &gt; 0, penjualan rendah.</p>
        <div class="mt-4 h-64">
            <canvas id="chartSlow"></canvas>
        </div>
    </div>
</div>

<div class="mt-6 rounded-md border border-stone-300/80 bg-surface p-4 shadow-lift dark:border-stone-700 dark:bg-stone-900">
    <h2 class="text-sm font-semibold text-ink dark:text-stone-100">Forecast &amp; rekomendasi pembelian (30 hari)</h2>
    <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">Saran order = max(0, proyeksi − stok). Ambang &quot;stok rendah&quot; di dashboard: ≤ <?= (int) $lowStockMaxNote ?>.</p>
    <div class="mt-3 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-stone-200 text-xs uppercase text-stone-500 dark:border-stone-700">
                <tr>
                    <th class="py-2 pr-4">Produk</th>
                    <th class="py-2 pr-4">Kategori</th>
                    <th class="py-2">Stok</th>
                    <th class="py-2">Jual 90h</th>
                    <th class="py-2">Avg/hari</th>
                    <th class="py-2">Proyeksi</th>
                    <th class="py-2 font-semibold text-accent dark:text-orange-400">Saran order</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
                <?php foreach ($forecast as $f): ?>
                    <tr>
                        <td class="py-2 pr-4"><?= esc($f['name']) ?></td>
                        <td class="py-2 pr-4 text-stone-600 dark:text-stone-400"><?= esc($f['category_name']) ?></td>
                        <td class="py-2"><?= (int) $f['stock_qty'] ?></td>
                        <td class="py-2"><?= (int) $f['sold_in_period'] ?></td>
                        <td class="py-2"><?= esc((string) $f['avg_daily']) ?></td>
                        <td class="py-2"><?= (int) $f['forecast_qty'] ?></td>
                        <td class="py-2 font-semibold text-accent dark:text-orange-400"><?= (int) $f['suggested_order'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 rounded-xl border border-red-200 bg-red-50/50 p-4 dark:border-red-900/50 dark:bg-red-950/20">
    <h2 class="text-sm font-semibold text-red-900 dark:text-red-200">Monitoring stok ≤ <?= (int) $lowStockMaxNote ?></h2>
    <div class="mt-3 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-red-200 text-xs uppercase text-red-800/80 dark:border-red-900 dark:text-red-300/80">
                <tr><th class="py-2 pr-4">ID</th><th class="py-2 pr-4">Nama</th><th class="py-2">Stok</th><th class="py-2">Satuan</th></tr>
            </thead>
            <tbody class="divide-y divide-red-100 dark:divide-red-900/40">
                <?php if (empty($lowStock)): ?>
                    <tr><td colspan="4" class="py-4 text-stone-600 dark:text-stone-400">Tidak ada pada ambang ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($lowStock as $p): ?>
                        <tr>
                            <td class="py-2 pr-4 font-mono text-xs"><?= (int) $p['id_barang'] ?></td>
                            <td class="py-2 pr-4"><?= esc($p['nama_barang']) ?></td>
                            <td class="py-2 font-semibold"><?= (int) $p['stok'] ?></td>
                            <td class="py-2"><?= esc($p['nama_satuan'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const isDark = document.documentElement.classList.contains('dark');
const grid = isDark ? 'rgba(120,113,108,0.22)' : 'rgba(41,37,36,0.07)';
const tick = isDark ? '#a8a29e' : '#57534e';

new Chart(document.getElementById('chartNeed'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($needLabels) ?>,
        datasets: [{
            label: 'Qty terjual',
            data: <?= json_encode($needQty) ?>,
            backgroundColor: '#b45309'
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

new Chart(document.getElementById('chartFast'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topSelling, 'name')) ?>,
        datasets: [{
            label: 'Qty',
            data: <?= json_encode(array_map('intval', array_column($topSelling, 'qty'))) ?>,
            backgroundColor: '#d97706'
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

new Chart(document.getElementById('chartSlow'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($slowMoving, 'name')) ?>,
        datasets: [{
            label: 'Qty terjual',
            data: <?= json_encode(array_map('intval', array_column($slowMoving, 'sold_qty'))) ?>,
            backgroundColor: '#94a3b8'
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
</script>
<?= $this->endSection() ?>
