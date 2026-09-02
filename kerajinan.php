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


$id_user = $_SESSION['id_user'] ?? 1;


$id_produk_submitted = isset($_GET['id_produk']) ? (int)$_GET['id_produk'] : null;


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

// Ambil semua produk dari database
$cek_produk = $conn->query("SELECT * FROM db_produk");

session_start();
if (isset($_SESSION['eksekusi'])) {
    echo "<div style='padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;'>
            {$_SESSION['eksekusi']}
          </div>";
    unset($_SESSION['eksekusi']);
}
if (isset($_SESSION['error'])) {
    echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>
            {$_SESSION['error']}
          </div>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerajinan</title>
    <link rel="stylesheet" href="css/css1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/navbar.php'; ?>

    <section id="produk" class="deskripsi">
        <div class="content">
            <?php while ($row = $cek_produk->fetch_assoc()) :
                $id_produk = $row['id_produk'];

                // Cek apakah user sudah like produk 
                $cek_like = $conn->query("SELECT 1 FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
                $sudah_like = ($cek_like && $cek_like->num_rows > 0);

                // total like 
                $query_like = "SELECT COUNT(*) as total FROM db_suka_produk WHERE id_produk = '$id_produk'";
                $result_like = mysqli_query($conn, $query_like);
                $row_like = mysqli_fetch_assoc($result_like)['total'];
            ?>
                <div class="deskripsi-item">
                    <img src="img/<?php echo htmlspecialchars($row['foto_produk']); ?>" alt="Gambar Produk">
                    <div class="deskripsi-text">
                        <h1><?php echo htmlspecialchars($row['nama_produk']); ?></h1>

                        <p><?php echo htmlspecialchars($row['penjelasan']); ?></p>

                        <div class="tombol-aksi">
                            <a href="beli.php?id=<?= $row['id_produk'] ?>" class="btn-beli">Beli Sekarang</a>
                            <?php if ($row['id_user'] == $id_user): ?>
                                <a href="kelola.php?edit=<?= $row['id_produk'] ?>" 
                                       class="btn-hapus" title="Hapus Produk">
                                       <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <a href="proses.php?hapus=<?= $row['id_produk'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                                       class="btn-hapus" title="Hapus Produk">
                                       <i class="fas fa-trash-alt"></i>
                                    </a>
                            <?php endif; ?>
                        </div>

                        <p>Harga: Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>

                        <form method="POST" action="kerajinan.php?id_produk=<?php echo $id_produk; ?>">
                            <button type="submit" name="<?php echo $sudah_like ? 'unlike' : 'like'; ?>" class="like-button">
                                <i class="fa<?php echo $sudah_like ? 's' : 'r'; ?> fa-heart"></i>
                            </button>
                            <a class="komentar" href="komentar.php?id_produk=<?php echo $id_produk; ?>">Lihat Komentar</a>
                        </form>
                        <p>Total Like: <?php echo $row_like; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>

</html>

