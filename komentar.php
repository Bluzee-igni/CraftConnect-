<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$id_user = $_SESSION['id_user'] ?? null;

// Check if id_produk is set
if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
} else {
    echo "ID Produk tidak tersedia.";
    exit();
}


$query_produk = $conn->prepare("SELECT * FROM db_produk WHERE id_produk = ?");
$query_produk->bind_param("s", $id_produk);  // "s" indicates a string parameter
$query_produk->execute();
$result_produk = $query_produk->get_result();
$produk = $result_produk->fetch_assoc();
$query_produk->close();

if (!$produk) {
    echo "Data produk tidak tersedia.";
    exit();
}

// Simpan komentar jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['komentar']) && !empty($_POST['komentar'])) {
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    $tanggal = date('Y-m-d H:i:s');

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO db_komentar (id_user, id_produk, isi_komentar, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id_user, $id_produk, $komentar, $tanggal);

    if ($stmt->execute()) {
        header("Location: komentar.php?id_produk=$id_produk");
        exit();
    } else {
        echo "Gagal menyimpan komentar: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil komentar
// Use prepared statement to prevent SQL injection
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
//$komentar = mysqli_fetch_assoc($query_komentar);
$query_komentar->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Komentar Produk</title>
    <link rel="stylesheet" href="komentar.css">
</head>
<body>

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
        <textarea name="komentar" placeholder="Tuliskan Komentar Anda" required></textarea>
        <button type="submit">Kirim</button>
    </form>

    <div class="daftar-komentar">
        <?php  while ($row = $result_komentar->fetch_assoc()) : ?>
            <div class="komentar-item">
                <strong><?= htmlspecialchars($row['username']) ?></strong>
                <p><?= nl2br(htmlspecialchars($row['isi_komentar']), false) ?></p>
                <small><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></small>
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
