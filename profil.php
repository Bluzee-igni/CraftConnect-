<?php
session_start();
include 'koneksi.php';

// Ambil ID user dari sesi login
$user_id = $_SESSION['id_user'] ?? 0;



// Ambil data user dari tabel `db_pengguna`
$sql = "SELECT nama, username FROM db_pengguna WHERE id_user = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Gagal mengambil data pengguna: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

$nama = $user['nama'] ?? 'Pengguna';
$username = $user['username'] ?? 'username';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil</title>
  <link rel="stylesheet" href="css/profil.css"/>
  <link rel="stylesheet" href="css/css1.css"/>
</head>
<body>

<nav class="navbar">
  <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>  
  <div class="navbar-nav">
    <a href="Index.php">Home</a>
    <a href="kerajinan.php">Kerajinan</a>
    <a href="About.php">Tentang Kami</a>
    <a href="profil.php">Profil</a>
    <a href="kelola.php">+</a>
  </div>
  <div class="navbar-extra" id="hamburger-menu">
    <a href="#" id="search"><i data-feather="search"></i></a>
    <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
    <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
  </div>
</nav>

  <div class="profile-container">
    <div class="profile-header">
      <div class="profile-image" style="background-image: url('uploads/default.jpg');"></div>
      <h2>Hai <?= htmlspecialchars($nama) ?>👋</h2>
    </div>

    <div class="profile-buttons">
      <a href="like_saya.php" class="button like-button">Like ❤️ <span class="arrow">→</span></a>
      <a href="pesanan.php" class="button order-button">Pesanan 📦 <span class="arrow">→</span></a>
    </div>

    <form action="proses/logout.php" method="post">
      <button class="logout-button" type="submit">Log Out</button>
    </form>
  </div>
</body>
</html>

