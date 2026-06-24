<?php
/**
 * Script untuk menyinkronkan data database aktif:
 * 1. Trim username pada tabel pengguna.
 * 2. Memperbaiki typo harga transaksi pada barang_masuk dan barang_keluar.
 * 3. Menghitung ulang stok barang berdasarkan mutasi masuk/keluar.
 *
 * HANYA boleh dijalankan via CLI:
 *   php app/Database/sync_database.php
 */
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Script ini hanya boleh dijalankan via CLI, bukan via browser.' . PHP_EOL);
}


$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'db_inventaris';

try {
    $mysqli = new mysqli($host, $user, $pass, $dbName);
    if ($mysqli->connect_error) {
        die("Koneksi gagal: " . $mysqli->connect_error . "\n");
    }
    echo "Terhubung ke database '$dbName' dengan sukses.\n\n";

    // 1. Trim username
    echo "1. Membersihkan spasi pada username... ";
    if ($mysqli->query("UPDATE pengguna SET username = TRIM(username)")) {
        echo "Sukses (" . $mysqli->affected_rows . " baris diperbarui).\n";
    } else {
        echo "Gagal: " . $mysqli->error . "\n";
    }

    // 2. Perbaiki typo harga beli Keramik Roman 40x40 (id_masuk = 6)
    echo "2. Memperbaiki typo harga beli Keramik Roman... ";
    if ($mysqli->query("UPDATE barang_masuk SET harga_beli = 80000.00 WHERE id_masuk = 6 AND id_barang = 4")) {
        echo "Sukses (" . $mysqli->affected_rows . " baris diperbarui).\n";
    } else {
        echo "Gagal: " . $mysqli->error . "\n";
    }

    // 3. Perbaiki typo harga jual Besi Beton 12mm (id_keluar = 6 & 7)
    echo "3. Memperbaiki typo harga jual Besi Beton... ";
    $q1 = $mysqli->query("UPDATE barang_keluar SET harga_jual = 80000.00 WHERE id_keluar = 6 AND id_barang = 2");
    $q2 = $mysqli->query("UPDATE barang_keluar SET harga_jual = 75000.00 WHERE id_keluar = 7 AND id_barang = 2");
    if ($q1 && $q2) {
        echo "Sukses.\n";
    } else {
        echo "Gagal: " . $mysqli->error . "\n";
    }

    // 4. Hitung ulang dan update stok barang
    echo "4. Menghitung ulang dan menyinkronkan stok barang... ";
    $syncSql = "
        UPDATE barang b 
        SET b.stok = GREATEST(0, 
            COALESCE((SELECT SUM(bm.jumlah) FROM barang_masuk bm WHERE bm.id_barang = b.id_barang), 0) - 
            COALESCE((SELECT SUM(bk.jumlah) FROM barang_keluar bk WHERE bk.id_barang = b.id_barang), 0)
        )
    ";
    if ($mysqli->query($syncSql)) {
        echo "Sukses (" . $mysqli->affected_rows . " baris diperbarui).\n";
    } else {
        echo "Gagal: " . $mysqli->error . "\n";
    }

    echo "\nSinkronisasi database selesai.\n";

} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
