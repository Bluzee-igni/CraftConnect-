<?php
session_start();
include 'koneksi.php';
if(!isset($_SESSION['user'])) {
    header('location:login.php');
} 

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

$sql = mysqli_query($koneksi, "SELECT * FROM posting");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> CraftConnect</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- My Style-->
    <link rel="stylesheet" href="css 1.css">
    

</head>

<body>

    <!-- Navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>

        <div class="navbar-nav">
            <a href="#">Home</a>
            <a href="#about">Kerajinan</a>
            <a href="funfact.php">KYML</a>
            <a href="About.php">Tentang Kami</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="search"><i data-feather="search"></i></a>
            <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Section start -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Tuangkan Ide <Span>Kreatifmu</Span></h1>
            <P>Dukung dan lestarikan budaya Indonesia dengan bangga menggunakan produk kerajinan lokal!</P>
            <a href="#" class="cta">Yuk Lihat-Lihat</a>
        </main>
    </section>
    <!-- Hero Section End -->

    <section id="about" class="deskripsi">
        <div class="content">
            <div class="deskripsi-item">
                <img src="Img/gambar_bedog.png" alt="">
                <div class="deskripsi-text">
                    <p>Bedog adalah senjata tradisional dari Jawa yang awalnya digunakan sebagai alat serbaguna dalam kehidupan
                        agraris, seperti menebas rumput atau memotong kayu. Selain itu, bedog juga berfungsi sebagai senjata
                        bela diri bagi petani, terutama di masa penjajahan. Bedog dibuat oleh pandai besi dengan teknik tempa
                        tradisional, menggunakan bilah besi atau baja serta gagang dari kayu atau tanduk. Selain menjadi alat
                        sehari-hari, bedog juga melambangkan keberanian dan kemandirian masyarakat Jawa. Saat ini, bedog lebih
                        sering dianggap sebagai simbol budaya atau koleksi seni, meskipun masih digunakan dalam seni bela diri
                        seperti pencak silat.</p>
                    <a
                        href="https://www.lazada.co.id/products/bedog-panguseupan-panjang-bilahper-bajanya-19-cm-kerajinan-khas-daerah-tasikmalaya-jawa-barat-harga-yang-tertera-untuk-satu-buah-barang-i1841198621-s13937234796.html?from_gmc=1&fl_tag=1">Link Pembelian</a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="deskripsi">
        <div class="content">
            <?php
            $no = 1;
            while($result = mysqli_fetch_assoc($sql)){
            ?>  
            <div class="produk">
                <div class="produk-text">
                    <h3><?php echo htmlspecialchars($result['nama_produk']); ?></h3>
                    <p><?php echo htmlspecialchars($result['penjelasan']); ?></p>
                    <a href="<?php echo htmlspecialchars($result['link_pembelian']); ?>">Link Pembelian</a>
                </div>
                <div class="produk-image">
                    <img src="img/<?php echo htmlspecialchars($result['foto_produk']); ?>" alt="Gambar Produk" style="width: 500px;">
                </div>
            </div>
            <?php 
            }
            ?>
        </div>
    </section>

    <!-- Tambah Data Button -->
    <div class="tambah-data">
        <button href="kelola.php" type="button" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i>
            Tambah Data
        </button>
    </div>

    <!-- Footer start -->
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
    <!-- Footer end -->

    <!-- Feather icon -->
    <script>
        feather.replace();
    </script>

    <!-- My JavaScript -->
    <script src="script.js"></script>
</body>
</html>