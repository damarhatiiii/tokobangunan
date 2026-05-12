<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> — Toko Bangunan</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; max-width: 22rem; margin: 1.5rem auto; padding: 0 0.75rem; color: #111; font-size: 13px; line-height: 1.45; }
        h1 { font-size: 1rem; font-weight: 700; margin: 0 0 0.35rem; text-align: center; }
        .muted { color: #555; font-size: 11px; text-align: center; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; margin: 0.75rem 0; }
        th, td { padding: 4px 0; vertical-align: top; }
        th { text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #666; border-bottom: 1px dashed #ccc; }
        .num { text-align: right; white-space: nowrap; }
        .total { font-weight: 700; font-size: 1.05rem; border-top: 1px solid #111; margin-top: 0.5rem; padding-top: 0.5rem; }
        .actions { margin-top: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .actions a, .actions button {
            display: inline-block; padding: 0.4rem 0.75rem; font-size: 12px; border-radius: 4px;
            border: 1px solid #ccc; background: #f5f5f4; color: #111; text-decoration: none; cursor: pointer; font-family: inherit;
        }
        .actions button { background: #1c1917; color: #fff; border-color: #1c1917; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; max-width: none; }
        }
    </style>
</head>
<body>
    <h1>Toko Bangunan</h1>
    <p class="muted">Bukti penerimaan barang &middot; #<?= (int) $row['id_masuk'] ?><br><?= esc($row['tanggal']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= esc($row['product_name']) ?><?= ! empty($row['nama_satuan']) ? ' <span style="color:#666">(' . esc($row['nama_satuan']) . ')</span>' : '' ?></td>
                <td class="num"><?= (int) $row['jumlah'] ?></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top:8px;font-size:11px;color:#555;">
                    Supplier: <?= esc($row['nama_supplier'] ?? '—') ?><br>
                    Harga beli / satuan: Rp <?= esc($harga_print) ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <table>
            <tr>
                <td>Nilai masuk</td>
                <td class="num">Rp <?= esc($subtotal_print) ?></td>
            </tr>
        </table>
    </div>

    <?php if (! empty($row['nama_petugas'])): ?>
        <p style="margin-top:1rem;font-size:11px;color:#666;">Petugas: <?= esc($row['nama_petugas']) ?></p>
    <?php endif; ?>

    <div class="actions no-print">
        <button type="button" onclick="window.print()">Cetak</button>
        <a href="<?= base_url('barang-masuk') ?>">Kembali ke daftar</a>
    </div>
</body>
</html>
