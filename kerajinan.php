<?php
session_start();
include 'koneksi.php';

// Cek jika belum login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Cek jika user dibanned
if ($user['role'] == 'banned') {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// Ambil ID produk dari URL jika ada (saat form like disubmit)
$id_produk_submitted = isset($_GET['id_produk']) ? (int)$_GET['id_produk'] : null;

// Proses jika tombol like ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_produk_submitted !== null) {
    // Cek apakah user sudah menyukai produk ini
    $cek = $conn->query("SELECT * FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk_submitted'");

    if ($cek->num_rows <= 0) {mysqli_query($conn,"INSERT INTO db_suka_produk (id_user, id_produk) VALUES ('$id_user', '$id_produk_submitted')");
        } else {
            mysqli_query($conn, "DELETE FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk_submitted'");
            header("Location: kerajinan.php");
            exit;
        }
    }

// Ambil semua produk dari database
$cek_produk = $conn->query("SELECT * FROM db_produk");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerajinan</title>
    <link rel="stylesheet" href="css1.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>
        <div class="navbar-nav">
            <a href="Index.php">Home</a>
            <a href="#about">Kerajinan</a>
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

    <section id="produk" class="deskripsi">
        <div class="content">
            <?php while ($row = $cek_produk->fetch_assoc()) :
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
                    <img src="img/<?php echo htmlspecialchars($row['foto_produk']); ?>" alt="Gambar Produk">
                    <div class="deskripsi-text">
                        <h1><?php echo htmlspecialchars($row['nama_produk']); ?></h1>

                        <p><?php echo htmlspecialchars($row['penjelasan']); ?></p>

                       <a href="beli.php?id=<?= $row['id_produk'] ?>">Beli Sekarang</a>
                        <p>Harga: Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <form method="POST" action="kerajinan.php?id_produk=<?php echo $id_produk; ?>">
                            <button type="submit" name=<?php echo $sudah_like ? 'unlike' : 'like'; ?>; ?>>
                                <?php echo $sudah_like ? "Disukai" : "Suka"; ?>
                            </button>
                        </form>
                        <p>Total Like: <?php echo $row_like; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>

</html>
