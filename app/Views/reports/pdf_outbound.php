<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 8px; }
        .meta { color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
        .num { text-align: right; }
        .sum { margin-top: 12px; font-weight: bold; }
    </style>
</head>
<body>
<h1><?= esc($title) ?></h1>
<p class="meta">Periode: <?= esc($from) ?> — <?= esc($to) ?> | Dicetak: <?= esc($date) ?></p>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th class="num">Qty</th>
            <th class="num">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= (int) $r['id_keluar'] ?></td>
                <td><?= esc($r['tanggal']) ?></td>
                <td><?= esc($r['product_name'] ?? '') ?></td>
                <td class="num"><?= (int) $r['jumlah'] ?></td>
                <td class="num"><?= number_format((float) $r['harga_jual'] * (int) $r['jumlah'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p class="sum">Total nilai keluar periode: Rp <?= number_format((float) $total, 0, ',', '.') ?></p>
</body>
</html>
