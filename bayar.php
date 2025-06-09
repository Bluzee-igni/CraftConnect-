<?php
session_start();
include 'koneksi.php';

// Validasi ID pesanan
if (!isset($_GET['id_pesanan'])) {
    exit('ID pesanan tidak ditemukan.');
}

$id_pesanan = intval($_GET['id_pesanan']);
if ($id_pesanan <= 0) {
    exit('ID pesanan tidak valid.');
}

$qr = 'qr_pembayaran.jpg'; // Ganti dengan nama file QR Code yang sesuai

// Ambil data pesanan
$query = "SELECT * FROM db_pesanan WHERE id_pesanan = $id_pesanan";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    exit('Pesanan tidak ditemukan.');
}
$pesanan = mysqli_fetch_assoc($result);

// Ambil data pengguna
$id_user = $pesanan['id_user'];
$query_user = "SELECT * FROM db_pengguna WHERE id_user = $id_user";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

// Ambil data produk
$id_produk = $pesanan['id_produk'];
$query_produk = "SELECT * FROM db_produk WHERE id_produk = $id_produk";
$result_produk = mysqli_query($conn, $query_produk);
$produk = mysqli_fetch_assoc($result_produk);

// Total harga
$total_harga = $pesanan['total_harga'];

// Ambil data metode pembayaran dari POST
$metode_id = $_POST['metode_pembayaran'] ?? null;
$metode = null;
if ($metode_id !== null) {
    $metode_id = intval($metode_id);
    $query_metode = mysqli_query($conn, "SELECT * FROM db_metode WHERE id_metode = $metode_id");
    $metode = mysqli_fetch_assoc($query_metode);
}

// ID transaksi disamakan dengan ID pesanan
$id_transaksi = $id_pesanan;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upload Bukti Pembayaran</title>
  <link rel="stylesheet" href="bayar.css">
</head>
<body>
  <div class="container">

    <!-- QR Code jika metode QRIS -->
    <?php if ($metode && $metode['kategori'] === 'qris'): ?>
      <div class="qr-wrapper">
        <img src="bukti/<?= htmlspecialchars($qr) ?>" alt="QR Code">
      </div>
    <?php endif; ?>

    <h1>Upload Bukti Pembayaran</h1>

    <div class="user-info">
      <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
      <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    </div>
    <div class="product-info">
      <p><strong>Produk:</strong> <?= htmlspecialchars($produk['nama_produk']) ?></p>
      <p><strong>Harga:</strong> Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
      <p><strong>Jumlah:</strong> <?= intval($pesanan['jumlah']) ?></p>

    <div class="payment-method">
      <p><strong>Transfer ke:</strong></p>
      <p><?= $metode ? htmlspecialchars($metode['nama_metode']) : '6019 0112 6711 4444.' ?></p>
    </div>

    <div class="amount-box">
      <strong>Total Pembayaran:</strong><br>
      Rp <?= number_format($total_harga, 0, ',', '.') ?>
    </div>

    <form action="upload_bukti.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
      <input type="file" name="bukti" accept="image/*" required>
      <button type="submit" name="submit" class="submit-button">Upload Bukti Pembayaran</button>
    </form>

    <div class="back-link">
      <a href="pesanan.php">Kembali ke Beranda</a>

  </div>
</body>
</html>
