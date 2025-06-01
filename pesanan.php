<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id_user'];
$query = "SELECT p.*, pr.nama_produk, pr.foto_produk, pr.penjelasan
          FROM db_pesanan p
          JOIN db_produk pr ON p.id_produk = pr.id_produk
          WHERE p.id_user = '$id_user'
          ORDER BY p.tanggal_pesan DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="pesanan.css">
</head>
<body>
    <div class="pesanan-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="pesanan-card">
                    <div class="pesanan-image">
                        <img src="img/<?= htmlspecialchars($row['foto_produk']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                    </div>
                    <div class="pesanan-info">
                        <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
                        <p><?= htmlspecialchars($row['penjelasan']) ?></p>
                        <p><strong>Jumlah:</strong> <?= $row['jumlah'] ?></p>
                        <p><strong>Total Harga:</strong> Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></p>
                        <p><strong>Tanggal:</strong> <?= $row['tanggal_pesan'] ?></p>
                    </div>
                    <div class="pesanan-status">
                        <h4>Status</h4>
                        <p class="status <?= strtolower($row['status']) ?>">
                            <em><?= htmlspecialchars($row['status']) ?></em>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Belum ada pesanan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
