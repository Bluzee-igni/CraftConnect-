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

$sql = mysqli_query($koneksi, "SELECT * FROM db_produk");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftConnect</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- My Style -->
    <link rel="stylesheet" href="css1.css">
</head>

<body>

    <!-- Navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>
        <div class="navbar-nav">
            <a href="#">Home</a>
            <a href="kerajinan.php">Kerajinan</a>
            <a href="funfact.php">KYML</a>
            <a href="About.php">Tentang Kami</a>
            <a href="logout.php">Logout</a>
            <a href="kelola.php">+</a>
        </div>
        <div class="navbar-extra" id="hamburger-menu">
            <a href="#" id="search"><i data-feather="search"></i></a>
            <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Section -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Tuangkan Ide <span>Kreatifmu</span></h1>
            <p>Dukung dan lestarikan budaya Indonesia dengan bangga menggunakan produk kerajinan lokal!</p>
            <a href="#" class="cta">Yuk Lihat-Lihat</a>
        </main>
    </section>

    <!-- Deskripsi Khusus (Statik) -->
    <section id="about" class="deskripsi">
        <div class="content">
            <div class="deskripsi-item">
                <img src="img/gambar_bedog.png" alt="Bedog">
                <div class="deskripsi-text">
                    <p>Bedog adalah senjata tradisional dari Jawa yang awalnya digunakan sebagai alat serbaguna dalam kehidupan agraris, seperti menebas rumput atau memotong kayu. Selain itu, bedog juga berfungsi sebagai senjata bela diri bagi petani, terutama di masa penjajahan...</p>
                    <a href="https://www.lazada.co.id/products/bedog-panguseupan-panjang-bilahper-bajanya-19-cm-kerajinan-khas-daerah-tasikmalaya-jawa-barat-harga-yang-tertera-untuk-satu-buah-barang-i1841198621-s13937234796.html?from_gmc=1&fl_tag=1" target="_blank">Link Pembelian</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Dinamis -->
    <section id="produk" class="deskripsi">
        <div class="content">
            <?php
            if (mysqli_num_rows($sql) > 0) {
                while ($result = mysqli_fetch_assoc($sql)) {
            ?>
                    <div class="deskripsi-item">
                        <img src="img/<?php echo htmlspecialchars($result['foto_produk']); ?>" alt="Gambar Produk">
                        <div class="deskripsi-text">
                            <h1><?php echo htmlspecialchars($result['nama_produk']); ?></h1>
                            <p><?php echo htmlspecialchars($result['penjelasan']); ?></p>
                            <a href="<?php echo htmlspecialchars($result['link_pembelian']); ?>" target="_blank">Link Pembelian</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p style='text-align:center;'>Belum ada produk yang tersedia.</p>";
            }
            ?>
        </div>
    </section>




    <!-- Tombol Tambah Data -->
    <div class="tambah-data">
        <a href="kelola.php" type="button" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Tentang Kami</h4>
                    <ul>
                        <li><a href="About.php">Tentang CraftConnect</a></li>
                        <li><a href="#">Tim Kami</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links">
                        <a href="https://www.instagram.com/atha_f.pdf/"><i data-feather="instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Feather Icon -->
    <script>
        feather.replace();
    </script>

    <!-- JavaScript -->
    <script src="script.js"></script>
</body>

</html>