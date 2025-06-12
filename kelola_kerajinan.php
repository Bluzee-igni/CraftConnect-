<?php
session_start();
include 'koneksi.php';

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


$id_user = $_SESSION['id_user'] ?? 1;

// Ambil ID produk dari URL jika ada (saat form like disubmit)
$id_produk_submitted = isset($_GET['id_produk']) ? (int)$_GET['id_produk'] : null;

// Proses jika tombol like ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_produk_submitted !== null) {
    $cek = $conn->query("SELECT * FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk_submitted'");

    if ($cek->num_rows <= 0) {
        mysqli_query($conn, "INSERT INTO db_suka_produk (id_user, id_produk) VALUES ('$id_user', '$id_produk_submitted')");
    } else {
        mysqli_query($conn, "DELETE FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk_submitted'");
        header("Location: kerajinan.php");
        exit;
    }
}


$cek_produk = $conn->query("SELECT * FROM db_produk");
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CraftConnect - Kelola Pesanan</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="kelola_kerajinan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2><span class="brand-white">Craft</span><span class="brand-blue">Connect.</span></h2>
            <a href="admin.php" class="nav-link">Data User</a>
            <a href="kelola_pesanan.php" class="nav-link active">Data Pesanan</a>
            <a href="kerajinan.php" class="nav-link">Kerajinan</a>
            <a href="logout.php" class="logout-btn">Log Out ?</a>
        </aside>

        <main class="content">
            <h1>Kerajinan Yang Kami Tampilkan</h1>
            <div class="card-wrapper">
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

                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <a href="beli.php?id=<?= $row['id_produk'] ?>" class="btn-beli">Beli Sekarang</a>

                                        <a href="proses_admin.php?hapus=<?= $row['id_produk'] ?>"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                            class="btn-hapus"
                                            title="Hapus Produk">
                                            <i class="fas fa-trash-alt" style="color: red;"></i>
                                        </a>
                                    </div>
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
        </main>
    </div>
</body>

</html>