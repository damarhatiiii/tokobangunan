<?php

namespace App\Libraries;

/**
 * Algoritma input/output nominal untuk transaksi invoice/struk:
 * normalisasi string nominal dari form, subtotal baris (qty × harga), pembulatan konsisten.
 */
final class InvoiceStrukCalculator
{
    /**
     * Mengubah input pengguna menjadi string DECIMAL(15,2) aman untuk kolom DB.
     */
    public static function normalizeMoneyInput(mixed $raw): string
    {
        if ($raw === null) {
            return '0.00';
        }

        $s = trim((string) $raw);
        if ($s === '') {
            return '0.00';
        }

        // Hilangkan label/mata uang umum.
        $s = str_replace(["\u{00a0}", 'Rp', 'rp', 'IDR', 'idr'], '', $s);
        $s = preg_replace('/[^\d,\.\-]/', '', $s) ?? '';

        // Format Indonesia: 1.234.567,89
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d+)?$/', $s)) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif (preg_match('/^\d+,\d+$/', $s)) {
            $s = str_replace(',', '.', $s);
        } elseif (substr_count($s, '.') > 1) {
            $s = str_replace('.', '', $s);
        }

        $v = filter_var($s, FILTER_VALIDATE_FLOAT);
        if ($v === false || $v < 0) {
            return '0.00';
        }

        return number_format((float) $v, 2, '.', '');
    }

    /**
     * Subtotal satu baris: harga satuan (decimal string) × qty.
     */
    public static function lineSubtotal(string $unitPriceDecimal, int $quantity): string
    {
        if ($quantity < 1) {
            return '0.00';
        }

        $unit = self::normalizeMoneyInput($unitPriceDecimal);

        if (function_exists('bcmul')) {
            return bcadd('0.00', bcmul($unit, (string) $quantity, 2), 2);
        }

        return number_format(round((float) $unit * $quantity, 2), 2, '.', '');
    }
}
