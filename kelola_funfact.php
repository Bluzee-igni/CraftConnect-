<?php
session_start();
include 'koneksi.php';

$id_pengguna = $_SESSION['id_pengguna'] ?? null;

// Fungsi bantu
function sudahDisukai($koneksi, $id_funfact, $id_pengguna) {
    $cek = mysqli_query($koneksi, "SELECT * FROM db_suka_produk WHERE id_produk = '$id_funfact' AND id_pengguna = '$id_pengguna'");
    return mysqli_num_rows($cek) > 0;
}

function jumlahLike($koneksi, $id_funfact) {
    $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM db_suka_produk WHERE id_produk = '$id_funfact'");
    $data = mysqli_fetch_assoc($q);
    return $data['total'];
}

function ambilKomentar($koneksi, $id_funfact) {
    return mysqli_query($koneksi, "d
        SELECT k.*, p.nama_pengguna 
        FROM db_komentar k 
        JOIN db_pengguna p ON k.id_pengguna = p.id_pengguna 
        WHERE k.id_produk = '$id_funfact' 
        ORDER BY k.tanggal_komentar DESC
    ");
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_funfact = $_POST['id_funfact'];

    if (isset($_POST['like']) && $id_pengguna) {
        mysqli_query($koneksi, "INSERT INTO db_suka_produk (id_produk, id_pengguna) VALUES ('$id_funfact', '$id_pengguna')");
    } elseif (isset($_POST['unlike']) && $id_pengguna) {
        mysqli_query($koneksi, "DELETE FROM db_suka_produk WHERE id_produk = '$id_funfact' AND id_pengguna = '$id_pengguna'");
    } elseif (isset($_POST['kirim']) && $id_pengguna) {
        $isi_komentar = mysqli_real_escape_string($koneksi, $_POST['isi_komentar']);
        mysqli_query($koneksi, "INSERT INTO db_komentar (id_produk, id_pengguna, isi_komentar, tanggal_komentar) VALUES ('$id_funfact', '$id_pengguna', '$isi_komentar', NOW())");
    }

    header("Location: funfact.php");
    exit;
}

// Ambil semua funfact
$query_funfact = mysqli_query($koneksi, "SELECT * FROM db_funfact ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Funfact - CraftConnect</title>
</head>
<body>
    <h1 class="judul-halaman">Halaman Funfact</h1>

    <?php while ($f = mysqli_fetch_assoc($query_funfact)) : ?>
        <div class="funfact-card">
            <h2 class="funfact-judul"><?= htmlspecialchars($f['judul']); ?></h2>
            <div class="funfact-gambar">
                <img src="img/<?= htmlspecialchars($f['gambar']); ?>" alt="<?= htmlspecialchars($f['judul']); ?>" class="gambar-funfact" width="300">
            </div>
            <p class="funfact-deskripsi"><?= nl2br(htmlspecialchars($f['deskripsi'])); ?></p>

            <!-- LIKE -->
            <div class="aksi-suka">
                <?php if ($id_pengguna): ?>
                    <form method="post" class="form-suka">
                        <input type="hidden" name="id_funfact" value="<?= $f['id_funfact']; ?>">
                        <?php if (sudahDisukai($koneksi, $f['id_funfact'], $id_pengguna)): ?>
                            <button type="submit" name="unlike" class="btn-unlike">Batal Suka</button>
                        <?php else: ?>
                            <button type="submit" name="like" class="btn-like">Suka</button>
                        <?php endif; ?>
                        <span class="jumlah-like"><?= jumlahLike($koneksi, $f['id_funfact']); ?> suka</span>
                    </form>
                <?php else: ?>
                    <p class="pesan-login"><em>Login untuk menyukai funfact ini.</em></p>
                <?php endif; ?>
            </div>

            <!-- KOMENTAR -->
            <div class="komentar-section">
                <h4 class="judul-komentar">Komentar:</h4>
                <div class="daftar-komentar">
                    <?php
                    $komentar = ambilKomentar($koneksi, $f['id_funfact']);
                    while ($k = mysqli_fetch_assoc($komentar)) :
                    ?>
                        <p class="item-komentar"><strong><?= htmlspecialchars($k['nama_pengguna']); ?>:</strong> <?= htmlspecialchars($k['isi_komentar']); ?></p>
                    <?php endwhile; ?>
                </div>

                <?php if ($id_pengguna): ?>
                    <form method="post" class="form-komentar">
                        <input type="hidden" name="id_funfact" value="<?= $f['id_funfact']; ?>">
                        <textarea name="isi_komentar" required placeholder="Tulis komentar..." class="input-komentar" rows="2" cols="40"></textarea><br>
                        <button type="submit" name="kirim" class="btn-kirim-komentar">Kirim Komentar</button>
                    </form>
                <?php else: ?>
                    <p class="pesan-login"><em>Login untuk memberi komentar.</em></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</body>
</html>

