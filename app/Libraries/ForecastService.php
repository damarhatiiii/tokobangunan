<?php

namespace App\Libraries;

use App\Models\BarangModel;
use Config\Database;

class ForecastService
{
    public function __construct(
        protected BarangModel $barang = new BarangModel(),
    ) {
    }

    /**
     * Rata-rata harian qty keluar dalam lookback diproyeksikan ke horizon (hari).
     * Saran pemesanan = max(0, proyeksi − stok saat ini). Tanpa kolom minimum stok di skema inventaris.
     *
     * @return list<array<string, mixed>>
     */
    public function buildForecast(int $horizonDays = 30, int $lookbackDays = 90): array
    {
        $db    = Database::connect();
        $end   = date('Y-m-d');
        $start = date('Y-m-d', strtotime("-{$lookbackDays} days"));

        $rows = $this->barang->withRelations()->orderBy('barang.nama_barang', 'ASC')->findAll();

        // Satu query untuk semua barang sekaligus (menggantikan N query individual)
        $salesRaw = $db->table('barang_keluar')
            ->select('id_barang, SUM(jumlah) AS qty')
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->groupBy('id_barang')
            ->get()
            ->getResultArray();

        $salesMap = [];
        foreach ($salesRaw as $s) {
            $salesMap[(int) $s['id_barang']] = (int) $s['qty'];
        }

        $out = [];
        foreach ($rows as $p) {
            $pid      = (int) $p['id_barang'];
            $totalQty = $salesMap[$pid] ?? 0;
            $avgDaily = $lookbackDays > 0 ? ($totalQty / $lookbackDays) : 0.0;
            $forecast = (int) ceil($avgDaily * $horizonDays);
            $stock    = (int) $p['stok'];
            $suggest  = (int) max(0, $forecast - $stock);

            $out[] = [
                'id_barang'       => $pid,
                'sku'             => '-',
                'name'            => $p['nama_barang'],
                'category_name'   => $p['nama_kategori'] ?? '',
                'stock_qty'       => $stock,
                'min_stock'       => 0,
                'sold_in_period'  => $totalQty,
                'avg_daily'       => round($avgDaily, 3),
                'forecast_qty'    => $forecast,
                'suggested_order' => $suggest,
            ];
        }

        return $out;
    }


    /** @return list<array<string, mixed>> */
    public function topSelling(int $limit = 8, int $lookbackDays = 90): array
    {
        $db    = Database::connect();
        $start = date('Y-m-d', strtotime("-{$lookbackDays} days"));

        return $db->table('barang_keluar')
            ->select('barang.id_barang AS id, barang.nama_barang AS name, SUM(barang_keluar.jumlah) AS qty, SUM(barang_keluar.jumlah * barang_keluar.harga_jual) AS revenue')
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang')
            ->where('barang_keluar.tanggal >=', $start)
            ->groupBy('barang.id_barang, barang.nama_barang')
            ->orderBy('qty', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /** @return list<array<string, mixed>> */
    public function slowMoving(int $limit = 8, int $lookbackDays = 90): array
    {
        $db    = Database::connect();
        $start = date('Y-m-d', strtotime("-{$lookbackDays} days"));

        $lim = (int) $limit;
        $sql = 'SELECT b.id_barang AS id, b.nama_barang AS name, b.stok AS stock_qty,
                COALESCE(SUM(CASE WHEN bk.tanggal >= ? THEN bk.jumlah ELSE 0 END), 0) AS sold_qty
                FROM barang b
                LEFT JOIN barang_keluar bk ON bk.id_barang = b.id_barang
                WHERE b.stok > 0
                GROUP BY b.id_barang, b.nama_barang, b.stok
                ORDER BY sold_qty ASC, b.stok DESC
                LIMIT ' . $lim;

        return $db->query($sql, [$start])->getResultArray();
    }

    /** @return list<array<string, mixed>> */
    public function monthlySales(int $months = 12): array
    {
        $db = Database::connect();

        return $db->table('barang_keluar')
            ->select("DATE_FORMAT(tanggal, '%Y-%m') AS ym, SUM(jumlah) AS qty, SUM(jumlah * harga_jual) AS revenue", false)
            ->where('tanggal >=', date('Y-m-01', strtotime('-' . ($months - 1) . ' months')))
            ->groupBy('ym')
            ->orderBy('ym', 'ASC')
            ->get()
            ->getResultArray();
    }
}
