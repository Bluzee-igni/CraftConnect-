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
    <link rel="stylesheet" href="css/css1.css">
</head>

<body>

    <?php include 'components/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Tuangkan Ide <span>Kreatifmu</span></h1>
            <p>Dukung dan lestarikan budaya Indonesia dengan bangga menggunakan produk kerajinan lokal!</p>
            <a href="#" class="cta">Yuk Lihat-Lihat</a>
        </main>
    </section> 

    <!-- Feather Icon -->
    <script>
        feather.replace();
    </script>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
</body>

</html>

