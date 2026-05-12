<?php

namespace App\Controllers;

use App\Libraries\ForecastService;
use App\Models\BarangModel;
use Config\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

class Reports extends BaseController
{
    public function index()
    {
        return view('reports/index', [
            'title' => 'Laporan',
        ]);
    }

    public function stockPdf()
    {
        $m    = new BarangModel();
        $rows = $m->withRelations()->orderBy('barang.nama_barang', 'ASC')->findAll();
        $rows = $m->attachLastHargaBeli($m->attachLastHargaJual($rows));

        $html = view('reports/pdf_stock', [
            'title' => 'Laporan Stok Barang',
            'rows'  => $rows,
            'date'  => date('d/m/Y H:i'),
        ]);

        return $this->renderPdf('laporan-stok.pdf', $html);
    }

    public function salesPdf()
    {
        $from = (string) $this->request->getGet('from') ?: date('Y-m-01');
        $to   = (string) $this->request->getGet('to') ?: date('Y-m-d');

        $db = Database::connect();
        $rows = $db->table('barang_keluar')
            ->select('barang_keluar.*, barang.nama_barang AS product_name')
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang')
            ->where('barang_keluar.tanggal >=', $from)
            ->where('barang_keluar.tanggal <=', $to)
            ->orderBy('barang_keluar.tanggal', 'DESC')
            ->orderBy('barang_keluar.id_keluar', 'DESC')
            ->get()
            ->getResultArray();

        $sum = $db->table('barang_keluar')
            ->select('COALESCE(SUM(jumlah * harga_jual), 0) AS total', false)
            ->where('tanggal >=', $from)
            ->where('tanggal <=', $to)
            ->get()
            ->getRowArray();

        $html = view('reports/pdf_sales', [
            'title' => 'Laporan Penjualan / Keluar',
            'rows'  => $rows,
            'from'  => $from,
            'to'    => $to,
            'total' => (float) ($sum['total'] ?? 0),
            'date'  => date('d/m/Y H:i'),
        ]);

        return $this->renderPdf('laporan-penjualan.pdf', $html);
    }

    public function forecastPdf()
    {
        $forecast = (new ForecastService())->buildForecast(30, 90);

        $html = view('reports/pdf_forecast', [
            'title'    => 'Forecast Kebutuhan Barang (30 hari)',
            'forecast' => $forecast,
            'date'     => date('d/m/Y H:i'),
        ]);

        return $this->renderPdf('laporan-forecast.pdf', $html);
    }

    public function inboundPdf()
    {
        $from = (string) $this->request->getGet('from') ?: date('Y-m-01');
        $to   = (string) $this->request->getGet('to') ?: date('Y-m-d');

        $db = Database::connect();
        $rows = $db->table('barang_masuk')
            ->select('barang_masuk.*, barang.nama_barang AS product_name, supplier.nama_supplier AS nama_supplier')
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang')
            ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier')
            ->where('barang_masuk.tanggal >=', $from)
            ->where('barang_masuk.tanggal <=', $to)
            ->orderBy('barang_masuk.tanggal', 'DESC')
            ->orderBy('barang_masuk.id_masuk', 'DESC')
            ->get()
            ->getResultArray();

        $html = view('reports/pdf_inbound', [
            'title' => 'Laporan Barang Masuk',
            'rows'  => $rows,
            'from'  => $from,
            'to'    => $to,
            'date'  => date('d/m/Y H:i'),
        ]);

        return $this->renderPdf('laporan-barang-masuk.pdf', $html);
    }

    public function outboundPdf()
    {
        $from = (string) $this->request->getGet('from') ?: date('Y-m-01');
        $to   = (string) $this->request->getGet('to') ?: date('Y-m-d');

        $db = Database::connect();
        $rows = $db->table('barang_keluar')
            ->select('barang_keluar.*, barang.nama_barang AS product_name')
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang')
            ->where('barang_keluar.tanggal >=', $from)
            ->where('barang_keluar.tanggal <=', $to)
            ->orderBy('barang_keluar.tanggal', 'DESC')
            ->orderBy('barang_keluar.id_keluar', 'DESC')
            ->get()
            ->getResultArray();

        $sum = $db->table('barang_keluar')
            ->select('COALESCE(SUM(jumlah * harga_jual), 0) AS total', false)
            ->where('tanggal >=', $from)
            ->where('tanggal <=', $to)
            ->get()
            ->getRowArray();

        $html = view('reports/pdf_outbound', [
            'title' => 'Laporan Barang Keluar',
            'rows'  => $rows,
            'from'  => $from,
            'to'    => $to,
            'total' => (float) ($sum['total'] ?? 0),
            'date'  => date('d/m/Y H:i'),
        ]);

        return $this->renderPdf('laporan-barang-keluar.pdf', $html);
    }

    protected function renderPdf(string $filename, string $html)
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }
}
