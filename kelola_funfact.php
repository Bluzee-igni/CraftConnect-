<?php
session_start();
include 'koneksi.php'; // koneksi ke database

$id_user = $_SESSION['id_user'] ?? 1; // sementara, untuk tes

// Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM db_produk ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="kelola_funfact.css">

<div class="container">
  <h2>Kerajinan yang Sudah Mulai Langka</h2>

  <!-- Form Upload Produk -->
  <form class="form-upload" action="upload_produk.php" method="post" enctype="multipart/form-data">
    <input type="text" name="nama_produk" placeholder="Nama Produk" required><br>
    <textarea name="penjelasan" placeholder="Penjelasan Produk" required></textarea><br>
    <input type="file" name="foto_produk" accept="image/*" required><br>
    <input type="number" name="harga" placeholder="Harga"><br>
    <input type="hidden" name="id_user" value="<?= $id_user ?>">
    <input type="hidden" name="id_kategori" value="1">
    <button class="btn" type="submit">Upload Produk</button>
  </form>

  <hr>

  <?php while($row = mysqli_fetch_assoc($query)): ?>
    <div class="card">
      <img src="img/<?= $row['foto_produk'] ?>" alt="foto">
      <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
      <p><?= nl2br(htmlspecialchars($row['penjelasan'])) ?></p>

      <!-- Like & Komentar -->
      <form action="like.php" method="post" style="display:inline;">
        <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
        <input type="hidden" name="id_user" value="<?= $id_user ?>">
        <button class="btn" type="submit">👍 Like</button>
      </form>

      <!-- Komentar -->
      <form class="form-komen" action="komen.php" method="post">
        <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
        <input type="hidden" name="id_user" value="<?= $id_user ?>">
        <input type="text" name="isi_komentar" placeholder="Tulis komentar..." required>
        <button class="btn" type="submit">💬 Kirim</button>
      </form>

      <?php
        $id_produk = $row['id_produk'];
        $komen = mysqli_query($conn, "SELECT * FROM db_komentar WHERE id_produk = $id_produk ORDER BY created_at DESC");
        while($k = mysqli_fetch_assoc($komen)):
      ?>
        <p><strong>Komentar:</strong> <?= htmlspecialchars($k['isi_komentar']) ?> <em>(<?= $k['created_at'] ?>)</em></p>
      <?php endwhile; ?>
    </div>
  <?php endwhile; ?>
</div>
