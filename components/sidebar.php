<?php
$current_page = basename($_SERVER["PHP_SELF"]);
?>
<aside class="sidebar">
  <h2><span class="brand-white">Craft</span><span class="brand-blue">Connect.</span></h2>
  <a href="admin.php" class="nav-link <?= $current_page == "admin.php" ? "active" : "" ?>">Data User</a>
  <a href="kelola_pesanan.php" class="nav-link <?= $current_page == "kelola_pesanan.php" ? "active" : "" ?>">Data Pesanan</a>
  <a href="kelola_kerajinan.php" class="nav-link <?= $current_page == "kelola_kerajinan.php" ? "active" : "" ?>">Kerajinan</a>
  <a href="kelola.php" class="nav-link <?= $current_page == "kelola.php" ? "active" : "" ?>">+</a>
  <a href="proses/logout.php" class="logout-btn">Log Out ?</a>
</aside>
