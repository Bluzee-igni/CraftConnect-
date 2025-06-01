<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data produk dari database
$query = mysqli_query($conn, "SELECT * FROM db_produk WHERE id_produk = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    die("Produk tidak ditemukan.");
}

$harga = intval($produk['harga']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CraftConnect</title>
  <link rel="stylesheet" href="beli.css" />
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="logo">Craft<span>Connect.</span></div>
      <img src="img/<?= htmlspecialchars($produk['foto_produk']) ?>" alt="Produk" />
      <p><?= htmlspecialchars($produk['penjelasan']) ?></p>
    </div>
    <div class="right">
      <!-- Arahkan ke file proses -->
      <form action="proses_beli.php" method="POST">
        <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>" />
        <div class="options">
          <?php foreach ([1, 5, 10, 15, 20] as $qty): ?>
            <button type="button" class="option-btn" onclick="setQty(<?= $qty ?>)"><?= $qty ?>x</button>
          <?php endforeach; ?>
          <input type="number" id="qtyInput" name="qty" placeholder="Custom" class="option-btn" min="1" oninput="updateTotal()" style="width:100px;" required />
        </div>

        <div class="price-input">
          <label>Total Harga:</label>
          <input type="text" id="totalHarga" readonly value="Rp <?= number_format($harga, 0, ',', '.') ?>" />
        </div>

        <div class="actions">
          <a href="kerajinan.php" class="btn cancel">Batal</a>
          <button type="submit" class="btn buy">Beli !!</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const hargaSatuan = <?= $harga ?>;
    const qtyInput = document.getElementById('qtyInput');
    const totalHarga = document.getElementById('totalHarga');

    function setQty(qty) {
      qtyInput.value = qty;
      updateTotal();
    }

    function updateTotal() {
      const qty = parseInt(qtyInput.value) || 1;
      const total = qty * hargaSatuan;
      totalHarga.value = 'Rp ' + total.toLocaleString('id-ID');
    }
  </script>
</body>
</html>
