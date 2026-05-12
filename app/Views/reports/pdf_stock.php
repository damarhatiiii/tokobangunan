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
<p class="meta">Dicetak: <?= esc($date) ?> — Harga referensi dari transaksi masuk/keluar terakhir.</p>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th class="num">Stok</th>
            <th class="num">Ref. beli</th>
            <th class="num">Ref. jual</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= (int) $r['id_barang'] ?></td>
                <td><?= esc($r['nama_barang']) ?></td>
                <td><?= esc($r['nama_kategori'] ?? '') ?></td>
                <td><?= esc($r['nama_satuan'] ?? '') ?></td>
                <td class="num"><?= (int) $r['stok'] ?></td>
                <td class="num"><?= isset($r['referensi_harga_beli']) && $r['referensi_harga_beli'] !== null ? number_format((float) $r['referensi_harga_beli'], 0, ',', '.') : '—' ?></td>
                <td class="num"><?= isset($r['referensi_harga_jual']) && $r['referensi_harga_jual'] !== null ? number_format((float) $r['referensi_harga_jual'], 0, ',', '.') : '—' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
