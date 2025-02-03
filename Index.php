<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('location:login.php');
} ?>
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
            <a href="#">Tentang Kami</a>
            <a href="logout.php">logout</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="search"><i data-feather="search"></i></a>
            <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>


    <!-- navbar End -->
    
    <!-- Hero Section start -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Tuangkan Ide <Span>Kreatifmu</Span></h1>
            <P>Lorem ipsum dolor sit, amet consectetur adipisicing elit. .</P>
            <a href="#" class="cta">Yuk Promsikan</a>
        </main>
    </section>

    <!-- Hero Section End -->

    <section id="about" class="deskripsi">
        <div class="content">
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
        <img src="Img/gambar_bedog.png" alt="">
    </section>


    <section id="about" class="deskripsi">
        <div class="content" style="color: red;">
            <p>Bedog adalah senjata tradisional dari Jawa yang awalnya digunakan sebagai alat serbaguna dalam kehidupan
                agraris, seperti menebas rumput atau memotong kayu. Selain itu, bedog juga berfungsi sebagai senjata
                bela diri bagi petani, terutama di masa penjajahan. Bedog dibuat oleh pandai besi dengan teknik tempa
                tradisional, menggunakan bilah besi atau baja serta gagang dari kayu atau tanduk. Selain menjadi alat
                sehari-hari, bedog juga melambangkan keberanian dan kemandirian masyarakat Jawa. Saat ini, bedog lebih
                sering dianggap sebagai simbol budaya atau koleksi seni, meskipun masih digunakan dalam seni bela diri
                seperti pencak silat.</p>
            <a
                href="https://shopee.co.id/product/168859287/6671390435?gads_t_sig=VTJGc2RHVmtYMTlxTFVSVVRrdENkVzBLS2xuUGZzMlQ5NjlFWklmRkZjVEk2M3V1MUgyR0tYalIxTXNjRW5wVlRmL2xrcWZRK1dIdkFwUWpjWjdzc0dpNDhCQlcxL2RtUjNYa1Q1RnRCTk1zdFdVU3JIV0hwS0Nwd3lqbEJwTTk">Link Pembelian</a>
        </div>
        <img src="Img/gambar_bedog.png" alt="">
    </section>


    <!-- Feather icon -->
    <script>
        feather.replace();
    </script>

    <!-- My java Java script-->
    <script src="script.js"></script>
</body>
</html>