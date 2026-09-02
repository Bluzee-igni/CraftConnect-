<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$id_user = $_SESSION['id_user'] ?? null;

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
} else {
    echo "ID Produk tidak tersedia.";
    exit();
}

// Ambil data produk
$query_produk = $conn->prepare("SELECT * FROM db_produk WHERE id_produk = ?");
$query_produk->bind_param("s", $id_produk);
$query_produk->execute();
$result_produk = $query_produk->get_result();
$produk = $result_produk->fetch_assoc();
$query_produk->close();

if (!$produk) {
    echo "Data produk tidak tersedia.";
    exit();
}

// Tangani mode edit komentar
$edit_mode = false;
$isi_komentar_edit = '';
$id_komentar_edit = '';

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_komentar_edit = intval($_GET['edit']);

    $stmt_edit = $conn->prepare("SELECT isi_komentar FROM db_komentar WHERE id_komentar = ?");
    $stmt_edit->bind_param("i", $id_komentar_edit);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $row_edit = $result_edit->fetch_assoc();
    $isi_komentar_edit = $row_edit['isi_komentar'] ?? '';
    $stmt_edit->close();
}

// Proses kirim atau update komentar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    $tanggal = date('Y-m-d H:i:s');

    if (isset($_POST['id_komentar_edit']) && !empty($_POST['id_komentar_edit'])) {
        // Update komentar
        $id_edit = intval($_POST['id_komentar_edit']);
        $stmt = $conn->prepare("UPDATE db_komentar SET isi_komentar = ?, created_at = ? WHERE id_komentar = ?");
        $stmt->bind_param("ssi", $komentar, $tanggal, $id_edit);
    } else {
        // Komentar baru
        $stmt = $conn->prepare("INSERT INTO db_komentar (id_user, id_produk, isi_komentar, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id_user, $id_produk, $komentar, $tanggal);
    }

    if ($stmt->execute()) {
        header("Location: lihat_komentar.php?id_produk=$id_produk");
        exit();
    } else {
        echo "Gagal menyimpan komentar: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil komentar produk
$query_komentar = $conn->prepare("
    SELECT k.id_komentar, k.id_produk, k.id_user, k.isi_komentar, k.created_at, peng.username
    FROM db_komentar k
    JOIN db_produk p ON k.id_produk = p.id_produk
    JOIN db_pengguna peng ON k.id_user = peng.id_user
    WHERE k.id_produk = ?
    ORDER BY k.created_at DESC
");
$query_komentar->bind_param("s", $id_produk);
$query_komentar->execute();
$result_komentar = $query_komentar->get_result();
$query_komentar->close();

// Hapus komentar
if (isset($_GET['hapus'])) {
    $id_komentar = $_GET['hapus'];
    $stmt_hapus = $conn->prepare("DELETE FROM db_komentar WHERE id_komentar = ?");
    $stmt_hapus->bind_param("i", $id_komentar);

    if ($stmt_hapus->execute()) {
        header("Location: lihat_komentar.php?id_produk=$id_produk");
        exit();
    } else {
        echo "Gagal menghapus komentar: " . $stmt_hapus->error;
    }
    $stmt_hapus->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Komentar Produk</title>
    <link rel="stylesheet" href="css/lihat_komentar.css">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-komentar {
            color: #3498db;
            margin-left: 10px;
            text-decoration: none;
        }
        .batal-edit {
            background-color: #ccc;
            color: black;
            padding: 6px 12px;
            text-decoration: none;
            margin-left: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <h2><span class="brand-white">Craft</span><span class="brand-blue">Connect.</span></h2>
        <a href="admin.php" class="nav-link">Data User</a>
        <a href="kelola_pesanan.php" class="nav-link">Data Pesanan</a>
        <a href="kelola_kerajinan.php" class="nav-link">Kerajinan</a>
        <a href="tambah_kerajinan_admin.php" class="nav-link">+</a>
        <a href="proses/logout.php" class="logout-btn">Log Out ?</a>
    </aside>

    <main class="content">
        <div class="main-content">
            <h1>Komentar Produk</h1>

            <div class="produk-container">
                <img class="produk-gambar" src="img/<?= htmlspecialchars($produk['foto_produk']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
                <h1 class="produk-nama"><?= htmlspecialchars($produk['nama_produk']) ?></h1>
                <p class="produk-harga">Harga: Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                <div class="produk-deskripsi">
                    <p><?= nl2br(htmlspecialchars($produk['penjelasan'])) ?></p>
                </div>
            </div>

            <div class="komentar-container">
                <form method="POST" class="komentar-form">
                    <textarea name="komentar" placeholder="Tuliskan Komentar Anda" required><?= htmlspecialchars($isi_komentar_edit) ?></textarea>
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="id_komentar_edit" value="<?= $id_komentar_edit ?>">
                        <button type="submit">Update Komentar</button>
                        <a href="lihat_komentar.php?id_produk=<?= $id_produk ?>" class="batal-edit">Batal</a>
                    <?php else: ?>
                        <button type="submit">Kirim</button>
                    <?php endif; ?>
                </form>

                <div class="daftar-komentar">
                    <?php while ($row = $result_komentar->fetch_assoc()) : ?>
                        <div class="komentar-item">
                            <strong><?= htmlspecialchars($row['username']) ?></strong>
                            <p><?= nl2br(htmlspecialchars($row['isi_komentar']), false) ?></p>
                            <small><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></small>
                            <a href="lihat_komentar.php?id_produk=<?= $_GET['id_produk'] ?>&hapus=<?= $row['id_komentar'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');" class="hapus-komentar">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="lihat_komentar.php?id_produk=<?= $_GET['id_produk'] ?>&edit=<?= $row['id_komentar'] ?>" class="edit-komentar">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="kembali-container">
                <a href="kelola_kerajinan.php" class="kembali">Kembali ke Kerajinan</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>

<?php
$conn->close();
?>

