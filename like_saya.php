<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = "
    SELECT p.id_produk, p.nama_produk, p.foto_produk, p.harga, p.penjelasan
    FROM db_suka_produk s
    JOIN db_produk p ON s.id_produk = p.id_produk
    WHERE s.id_user = $id_user
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Like Saya - CraftConnect</title>
    <link rel="stylesheet" href="css/css1.css">
    <link rel="stylesheet" href="css/like_saya.css">
</head>
<body>

<nav class="navbar">
        <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>
        <div class="navbar-nav">
            <a href="Index.php">Home</a>
            <a href="kerajinan.php">Kerajinan</a>
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

<h1>Produk yang Kamu Sukai ❤️</h1>

<div class="deskripsi">
    <div class="content">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): 
                $id_produk = $row['id_produk'];

                // Cek apakah user sudah like produk ini
                $cek_like = $conn->query("SELECT 1 FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
                $sudah_like = ($cek_like && $cek_like->num_rows > 0);

                // Hitung total like untuk produk ini
                $query_like = "SELECT COUNT(*) as total FROM db_suka_produk WHERE id_produk = '$id_produk'";
                $result_like = mysqli_query($conn, $query_like);
                $row_like = mysqli_fetch_assoc($result_like)['total'];
            ?>
                <div class="deskripsi-item">
                    <img src="img/<?= htmlspecialchars($row['foto_produk']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                    <div class="deskripsi-text">
                        <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
                        <p><?= htmlspecialchars($row['penjelasan']) ?></p>
                        <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        <a href="beli.php?id=<?= $row['id_produk'] ?>">Beli Sekarang</a>
                        
                        <!-- Tombol Like/Unlike -->
                        <form method="POST" action="kerajinan.php?id_produk=<?= $id_produk ?>">
                            <button type="submit" name="<?= $sudah_like ? 'unlike' : 'like' ?>">
                                <?= $sudah_like ? 'Disukai' : 'Suka' ?>
                            </button>
                        </form>

                        <p>Total Like: <?= $row_like ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">Kamu belum menyukai produk apa pun.</p>
        <?php endif; ?>
    </div>
</div>
            

</body>
</html>

