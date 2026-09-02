<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
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
    }
    header("Location: kelola_kerajinan.php");
    exit;
}

$cek_produk = $conn->query("SELECT * FROM db_produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CraftConnect - Kelola Kerajinan</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/kelola_kerajinan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'components/sidebar.php'; ?>

        <main class="content">
            <h1>Kerajinan Yang Kami Tampilkan</h1>
            <div class="card-wrapper">
                <section id="produk" class="deskripsi">
                    <div class="content">
                        <?php while ($row = $cek_produk->fetch_assoc()) :
                            $id_produk = $row['id_produk'];

                            $cek_like = $conn->query("SELECT 1 FROM db_suka_produk WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
                            $sudah_like = ($cek_like && $cek_like->num_rows > 0);

                            $query_like = "SELECT COUNT(*) as total FROM db_suka_produk WHERE id_produk = '$id_produk'";
                            $result_like = mysqli_query($conn, $query_like);
                            $row_like = mysqli_fetch_assoc($result_like)['total'];

                            $query_komen = "SELECT COUNT(*) as total FROM db_komentar WHERE id_produk = '$id_produk'";
                            $result_komen = mysqli_query($conn, $query_komen);
                            $row_komen = mysqli_fetch_assoc($result_komen)['total'];
                        ?>
                        <div class="deskripsi-item">
                            <img src="img/<?php echo htmlspecialchars($row['foto_produk']); ?>" alt="Gambar Produk">
                            <div class="deskripsi-text">
                                <h1><?php echo htmlspecialchars($row['nama_produk']); ?></h1>
                                <p><?php echo htmlspecialchars($row['penjelasan']); ?></p>
                                <p>Harga: Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>

                                <div class="tombol-aksi">
                                    <a href="beli.php?id=<?= $row['id_produk'] ?>" class="btn-beli">Beli Sekarang</a>
                                    <a href="kelola.php?edit=<?= $row['id_produk'] ?>" 
                                       class="btn-edit" title="Edit Produk">
                                       <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <a href="proses/proses_admin.php?hapus=<?= $row['id_produk'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                                       class="btn-hapus" title="Hapus Produk">
                                       <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>

                                <div class="sosial">
                                <a class="komentar" href="lihat_komentar.php?id_produk=<?php echo $id_produk; ?>"><i class="fa-solid fa-comment">  <?php echo $row_komen; ?></i></a>
                                <form method="POST" action="kelola_kerajinan.php?id_produk=<?php echo $row['id_produk']; ?>">
                                    <button type="submit" name="<?php echo $sudah_like ? 'unlike' : 'like'; ?>" class="like-button">
                                        <i class="fa<?php echo $sudah_like ? 's' : 'r'; ?> fa-heart">  <?php echo $row_like; ?></i>
                                    </button>
                                </form>
                                </div>

                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>


