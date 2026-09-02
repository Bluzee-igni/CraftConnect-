<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_produk = intval($_POST['id_produk']);
    $qty = intval($_POST['qty']);

    if ($qty < 1) {
        die("Jumlah pembelian tidak valid.");
    }

    // Ambil harga dari produk
    $query = mysqli_query($conn, "SELECT harga FROM db_produk WHERE id_produk = $id_produk");
    $produk = mysqli_fetch_assoc($query);

    if (!$produk) {
        die("Produk tidak ditemukan.");
    }

    $harga_satuan = intval($produk['harga']);
    $total_harga = $harga_satuan * $qty;

    // Simpan pesanan ke db_pesanan
    $sql = "INSERT INTO db_pesanan (id_user, id_produk, jumlah, total_harga, status, tanggal_pesan)
            VALUES ($id_user, $id_produk, $qty, $total_harga, 'Ditanyakan', NOW())";

    if (mysqli_query($conn, $sql)) {
        // ✅ Tambahkan pesan ke session
        $_SESSION['eksekusi'] = "Pesanan Anda berhasil dimasukkan ke keranjang. Silakan cek di halaman keranjang.";
        header("Location: ../kerajinan.php");
        exit;
    } else {
        echo "Gagal menyimpan pesanan: " . mysqli_error($conn);
    }
}
?>


