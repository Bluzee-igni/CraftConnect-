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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css1.css">
</head>

<nav class="navbar">
    <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>
    <div class="navbar-nav">
        <a href="Index.php">Home</a>
        <a href="kerajinan.php">Kerajinan</a>
        <a href="funfact.php">KYML</a>
        <a href="About.php">Tentang Kami</a>
        <a href="profil.php">Profil</a>
        <a href="kelola.php">+</a>
    </div>
    <div class="navbar-extra" id="hamburger-menu">
        <a href="#" id="search"><i data-feather="search"></i></a>
        <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
</nav>

<body>
    <div class="pesanan-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                    $id_pesanan = $row['id_pesanan'];
                    // Cek apakah pesanan sudah dibayar
                    $query_cek_bayar = mysqli_query($conn, "SELECT * FROM db_pembayaran WHERE id_pesanan = $id_pesanan");
                    $sudah_dibayar = mysqli_num_rows($query_cek_bayar) > 0;
                ?>
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

                        <?php if (strtolower($row['status']) == 'diproses' && !$sudah_dibayar): ?>
                            <a href="bayar.php?id_pesanan=<?= $row['id_pesanan'] ?>" class="tombol-bayar">
                                <em><strong>BAYAR</strong></em>
                            </a>
                        <?php elseif (strtolower($row['status']) == 'diproses' && $sudah_dibayar): ?>
                            <p style="color: green; font-weight: bold;"><em>Sudah Dibayar</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Belum ada pesanan.</p>
        <?php endif; ?>
    </div>
</body>


</html>
