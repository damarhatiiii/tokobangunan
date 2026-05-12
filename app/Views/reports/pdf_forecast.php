<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        h1 { font-size: 15px; margin: 0 0 8px; }
        .meta { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 5px 6px; text-align: left; }
        th { background: #f0f0f0; }
        .num { text-align: right; }
    </style>
</head>
<body>
<h1><?= esc($title) ?></h1>
<p class="meta">Metode: rata-rata harian qty keluar 90 hari × 30 hari; saran = max(0, proyeksi − stok). Dicetak: <?= esc($date) ?></p>
<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th class="num">Stok</th>
            <th class="num">Jual 90h</th>
            <th class="num">Avg/hr</th>
            <th class="num">Proyeksi</th>
            <th class="num">Saran</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($forecast as $f): ?>
            <tr>
                <td><?= esc($f['name']) ?></td>
                <td><?= esc($f['category_name']) ?></td>
                <td class="num"><?= (int) $f['stock_qty'] ?></td>
                <td class="num"><?= (int) $f['sold_in_period'] ?></td>
                <td class="num"><?= esc((string) $f['avg_daily']) ?></td>
                <td class="num"><?= (int) $f['forecast_qty'] ?></td>
                <td class="num"><strong><?= (int) $f['suggested_order'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
