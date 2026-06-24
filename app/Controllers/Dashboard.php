<?php

namespace App\Controllers;

use App\Libraries\ForecastService;
use App\Models\BarangModel;
use App\Models\CategoryModel;
use App\Models\SupplierModel;
use Config\Database;

class Dashboard extends BaseController
{
    private const LOW_STOCK_MAX = 10;

    public function index()
    {
        $db            = Database::connect();
        $barangModel   = new BarangModel();
        $forecastSvc   = new ForecastService();
        $totalProducts = $barangModel->countAllResults();
        $totalSuppliers = (new SupplierModel())->countAllResults();

        $lowStockCount = $barangModel->where('stok <=', self::LOW_STOCK_MAX)->countAllResults(false);

        $lowStock = $barangModel->withRelations()
            ->where('barang.stok <=', self::LOW_STOCK_MAX)
            ->orderBy('barang.stok', 'ASC')
            ->limit(8)
            ->findAll();


        $monthStart = date('Y-m-01');
        $monthEnd   = date('Y-m-t');

        $salesMonthRow = $db->table('barang_keluar')
            ->select('COUNT(*) AS cnt, COALESCE(SUM(jumlah * harga_jual), 0) AS revenue', false)
            ->where('tanggal >=', $monthStart)
            ->where('tanggal <=', $monthEnd)
            ->get()
            ->getRowArray();

        $salesMonthCount   = (int) ($salesMonthRow['cnt'] ?? 0);
        $salesMonthRevenue = (float) ($salesMonthRow['revenue'] ?? 0);

        $inboundMonthCount = (int) $db->table('barang_masuk')
            ->where('tanggal >=', $monthStart)
            ->where('tanggal <=', $monthEnd)
            ->countAllResults();

        $stockSql = <<<'SQL'
SELECT COALESCE(SUM(b.stok * COALESCE(lp.harga_beli, 0)), 0) AS val
FROM barang b
LEFT JOIN (
    SELECT bm.id_barang, bm.harga_beli
    FROM barang_masuk bm
    INNER JOIN (
        SELECT id_barang, MAX(id_masuk) AS mx FROM barang_masuk GROUP BY id_barang
    ) t ON t.mx = bm.id_masuk AND t.id_barang = bm.id_barang
) lp ON lp.id_barang = b.id_barang
SQL;
        $stockRow   = $db->query($stockSql)->getRowArray();
        $stockValue = (float) ($stockRow['val'] ?? 0);

        $sales7raw = $db->table('barang_keluar')
            ->select('tanggal, SUM(jumlah) AS qty, SUM(jumlah * harga_jual) AS revenue')
            ->where('tanggal >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $byDate = [];
        foreach ($sales7raw as $r) {
            $byDate[$r['tanggal']] = $r;
        }
        $chartLabels = [];
        $chartQty    = [];
        $chartRev    = [];
        for ($i = 6; $i >= 0; $i--) {
            $d             = date('Y-m-d', strtotime("-{$i} days"));
            $chartLabels[] = date('d M', strtotime($d));
            $chartQty[]    = isset($byDate[$d]) ? (int) $byDate[$d]['qty'] : 0;
            $chartRev[]    = isset($byDate[$d]) ? (float) $byDate[$d]['revenue'] : 0.0;
        }

        $byCategory = $db->table('barang')
            ->select('kategori.nama_kategori AS label, SUM(barang.stok) AS qty')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori')
            ->groupBy('kategori.id_kategori')
            ->get()
            ->getResultArray();

        $forecast   = $forecastSvc->buildForecast(30, 90);
        $rawMonthly = $forecastSvc->monthlySales(12);
        $monthMap   = [];
        foreach ($rawMonthly as $r) {
            $monthMap[$r['ym']] = $r;
        }
        $monthlyLabels = [];
        $monthlyQty    = [];
        $monthlyRev    = [];
        for ($i = 11; $i >= 0; $i--) {
            $ym              = date('Y-m', strtotime("-{$i} months"));
            $monthlyLabels[] = date('M Y', strtotime($ym . '-01'));
            $monthlyQty[]    = isset($monthMap[$ym]) ? (int) $monthMap[$ym]['qty'] : 0;
            $monthlyRev[]    = isset($monthMap[$ym]) ? (float) $monthMap[$ym]['revenue'] : 0.0;
        }

        $topSelling = $forecastSvc->topSelling(8, 90);

        $topStock = $db->table('barang')
            ->select('nama_barang AS name, stok AS stock_qty')
            ->orderBy('stok', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'title'               => 'Dashboard',
            'totalProducts'       => $totalProducts,
            'totalSuppliers'      => $totalSuppliers,
            'categoriesCount'     => (new CategoryModel())->countAllResults(),
            'lowStockCount'       => $lowStockCount,
            'lowStock'            => $lowStock,
            'lowStockMax'         => self::LOW_STOCK_MAX,
            'stockValue'          => $stockValue,
            'chartLabels'         => $chartLabels,
            'chartQty'            => $chartQty,
            'chartRev'            => $chartRev,
            'monthlyLabels'       => $monthlyLabels,
            'monthlyQty'          => $monthlyQty,
            'monthlyRev'          => $monthlyRev,
            'byCategory'          => $byCategory,
            'forecastTop'         => array_slice($forecast, 0, 6),
            'topSelling'          => $topSelling,
            'topStock'            => $topStock,
            'salesMonthCount'     => $salesMonthCount,
            'salesMonthRevenue'   => $salesMonthRevenue,
            'inboundMonthCount'   => $inboundMonthCount,
        ]);
    }

    public function analysis()
    {
        $barangModel = new BarangModel();
        $forecastSvc = new ForecastService();

        $forecast   = $forecastSvc->buildForecast(30, 90);
        $lowStock   = $barangModel->withRelations()
            ->where('barang.stok <=', self::LOW_STOCK_MAX)
            ->orderBy('barang.stok', 'ASC')
            ->findAll();
        $topSelling = $forecastSvc->topSelling(15, 90);
        $slowMoving = $forecastSvc->slowMoving(15, 90);
        $monthly    = $forecastSvc->monthlySales(12);

        $monthMap = [];
        foreach ($monthly as $r) {
            $monthMap[$r['ym']] = $r;
        }
        $needLabels = [];
        $needQty    = [];
        for ($i = 11; $i >= 0; $i--) {
            $ym           = date('Y-m', strtotime("-{$i} months"));
            $needLabels[] = date('M Y', strtotime($ym . '-01'));
            $needQty[]    = isset($monthMap[$ym]) ? (int) $monthMap[$ym]['qty'] : 0;
        }

        return view('dashboard/analysis', [
            'title'           => 'Analisis Kebutuhan Barang',
            'forecast'        => $forecast,
            'lowStock'        => $lowStock,
            'topSelling'      => $topSelling,
            'slowMoving'      => $slowMoving,
            'needLabels'      => $needLabels,
            'needQty'         => $needQty,
            'lowStockMaxNote' => self::LOW_STOCK_MAX,
        ]);
    }
}
