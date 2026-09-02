<?php
session_start();
include 'koneksi.php';

// Proses update status jika ada permintaan POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pesanan'], $_POST['status'])) {
  $id_pesanan = $_POST['id_pesanan'];
  $status = $_POST['status'];

  $query_update = "UPDATE db_pesanan SET status = ? WHERE id_pesanan = ?";
  $stmt = $conn->prepare($query_update);
  $stmt->bind_param("si", $status, $id_pesanan);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CraftConnect - Kelola Pesanan</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/kelola_pesanan.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <div class="container">
    <aside class="sidebar">
      <h2><span class="brand-white">Craft</span><span class="brand-blue">Connect.</span></h2>
      <a href="admin.php" class="nav-link">Data User</a>
      <a href="kelola_pesanan.php" class="nav-link active">Data Pesanan</a>
      <a href="kelola_kerajinan.php" class="nav-link">Kerajinan</a>
      <a href="tambah_kerajinan_admin.php" class="nav-link">+</a>
      <a href="proses/logout.php" class="logout-btn">Log Out ?</a>
    </aside>

    <main class="content">
      <h1>Daftar Pesanan</h1>
      <div class="card-wrapper">
        <?php
        $query = "SELECT * FROM db_pesanan ORDER BY tanggal_pesan DESC";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) :
          $id_pesanan = $row['id_pesanan'];

          // Cek apakah ada bukti pembayaran untuk pesanan ini
          $cek_pembayaran = mysqli_query($conn, "SELECT bukti_pembayaran FROM db_pembayaran WHERE id_pesanan = $id_pesanan LIMIT 1");
          $data_pembayaran = mysqli_fetch_assoc($cek_pembayaran);
          $bukti_ada = $data_pembayaran ? true : false;
        ?>
          <div class="card">
            <div class="text">
              <strong>ID Pesanan:</strong> <?= $row['id_pesanan'] ?><br>
              <strong>ID User:</strong> <?= $row['id_user'] ?><br>
              <strong>ID Produk:</strong> <?= $row['id_produk'] ?><br>
              <strong>Jumlah:</strong> <?= $row['jumlah'] ?><br>
              <strong>Total Harga:</strong> Rp<?= number_format($row['total_harga'], 0, ',', '.') ?><br>
              <strong>Tanggal:</strong> <?= $row['tanggal_pesan'] ?><br>
              
              <?php if ($bukti_ada): ?>
                <a href="uploads/<?= $data_pembayaran['bukti_pembayaran'] ?>" target="_blank" class="lihat-bukti-btn">
                  <i class="fa fa-eye"></i> Lihat Bukti
                </a>
              <?php endif; ?>
            </div>
            <form method="POST" class="status-form">
              <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
              <select name="status" onchange="this.form.submit()">
                <option <?= $row['status'] == 'Ditanyakan' ? 'selected' : '' ?>>Ditanyakan</option>
                <option <?= $row['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                <option <?= $row['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                <option <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
              </select>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    </main>
  </div>
</body>

</html>

