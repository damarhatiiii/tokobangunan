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
            <th>Supplier</th>
            <th class="num">Qty</th>
            <th class="num">Harga beli</th>
            <th class="num">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= (int) $r['id_masuk'] ?></td>
                <td><?= esc($r['tanggal']) ?></td>
                <td><?= esc($r['product_name'] ?? '') ?></td>
                <td><?= esc($r['nama_supplier'] ?? '') ?></td>
                <td class="num"><?= (int) $r['jumlah'] ?></td>
                <td class="num"><?= number_format((float) $r['harga_beli'], 0, ',', '.') ?></td>
                <td class="num"><?= number_format((float) $r['harga_beli'] * (int) $r['jumlah'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
