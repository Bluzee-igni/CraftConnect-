<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$id_user = $_SESSION['id_user'] ?? null;
$role = $_SESSION['role'] ?? null;

if (!$id_user) {
    echo "Session tidak valid.";
    exit();
}

// ===== Hapus Komentar =====
if (isset($_GET['hapus']) && isset($_GET['id_produk'])) {
    $id_komentar = $_GET['hapus'];
    $id_produk = $_GET['id_produk'];

    $stmt_validasi = $conn->prepare("SELECT id_user FROM db_komentar WHERE id_komentar = ?");
    $stmt_validasi->bind_param("i", $id_komentar);
    $stmt_validasi->execute();
    $hasil = $stmt_validasi->get_result()->fetch_assoc();
    $stmt_validasi->close();

    if ($hasil && ($hasil['id_user'] == $id_user || $role === 'admin')) {
        $stmt_hapus = $conn->prepare("DELETE FROM db_komentar WHERE id_komentar = ?");
        $stmt_hapus->bind_param("i", $id_komentar);
        if ($stmt_hapus->execute()) {
            $stmt_hapus->close();
            header("Location: komentar.php?id_produk=" . $id_produk);
            exit();
        } else {
            echo "Gagal menghapus komentar: " . $stmt_hapus->error;
        }
    } else {
        echo "Akses ditolak.";
        exit();
    }
}

// ===== Ambil Data Produk =====
if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
} else {
    echo "ID Produk tidak tersedia.";
    exit();
}

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

$isi_komentar_edit = '';
$id_komentar_edit = null;

if (isset($_GET['edit'])) {
    $id_komentar_edit = $_GET['edit'];

    $stmt_edit = $conn->prepare("SELECT * FROM db_komentar WHERE id_komentar = ?");
    $stmt_edit->bind_param("i", $id_komentar_edit);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();

    if ($result_edit && ($result_edit['id_user'] == $id_user || $role === 'admin')) {
        $isi_komentar_edit = $result_edit['isi_komentar'];
    } else {
        echo "Akses ditolak untuk edit.";
        exit();
    }
}

// ===== Simpan Komentar Baru atau Edit =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['komentar']) && !empty($_POST['komentar'])) {
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    $tanggal = date('Y-m-d H:i:s');

    if (isset($_POST['id_komentar']) && !empty($_POST['id_komentar'])) {
        $id_komentar_edit = $_POST['id_komentar'];

        $stmt_validasi = $conn->prepare("SELECT id_user FROM db_komentar WHERE id_komentar = ?");
        $stmt_validasi->bind_param("i", $id_komentar_edit);
        $stmt_validasi->execute();
        $hasil_validasi = $stmt_validasi->get_result()->fetch_assoc();
        $stmt_validasi->close();

        if ($hasil_validasi && ($hasil_validasi['id_user'] == $id_user || $role === 'admin')) {
            $stmt_update = $conn->prepare("UPDATE db_komentar SET isi_komentar = ?, created_at = ? WHERE id_komentar = ?");
            $stmt_update->bind_param("ssi", $komentar, $tanggal, $id_komentar_edit);
            if ($stmt_update->execute()) {
                $stmt_update->close();
                header("Location: komentar.php?id_produk=$id_produk");
                exit();
            } else {
                echo "Gagal mengedit komentar: " . $stmt_update->error;
            }
        } else {
            echo "Akses ditolak.";
            exit();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO db_komentar (id_user, id_produk, isi_komentar, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id_user, $id_produk, $komentar, $tanggal);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: komentar.php?id_produk=$id_produk");
            exit();
        } else {
            echo "Gagal menyimpan komentar: " . $stmt->error;
        }
    }
}

// ===== Ambil Komentar =====
$query_komentar = $conn->prepare("
    SELECT k.id_komentar, k.id_produk, k.id_user, k.isi_komentar, k.created_at, peng.username
    FROM db_komentar k
    JOIN db_pengguna peng ON k.id_user = peng.id_user
    WHERE k.id_produk = ?
    ORDER BY k.created_at DESC
");
$query_komentar->bind_param("s", $id_produk);
$query_komentar->execute();
$result_komentar = $query_komentar->get_result();
$query_komentar->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Komentar Produk</title>
    <link rel="stylesheet" href="css/komentar.css">
    <link rel="stylesheet" href="css/css1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
</head>
<body>

<?php include 'components/navbar.php'; ?>

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
        <?php if ($id_komentar_edit): ?>
            <input type="hidden" name="id_komentar" value="<?= $id_komentar_edit ?>">
            <button type="submit">Perbarui Komentar</button>
            <a href="komentar.php?id_produk=<?= $id_produk ?>" class="batal-edit">Batal</a>
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

                <?php if ($row['id_user'] == $id_user || $role === 'admin') : ?>
                    <a href="komentar.php?id_produk=<?= $id_produk ?>&hapus=<?= $row['id_komentar'] ?>"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');"
                       class="hapus-komentar">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a href="komentar.php?id_produk=<?= $id_produk ?>&edit=<?= $row['id_komentar'] ?>"
                       class="edit-komentar">
                        <i class="fas fa-pen"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="kembali-container">
    <a href="kerajinan.php" class="kembali">Kembali ke Kerajinan</a>
</div>

</body>
</html>

<?php
$conn->close();
?>


