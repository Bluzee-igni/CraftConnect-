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
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerajinan</title>
    
</head>
<body>
<nav class="navbar">
        <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>

        <div class="navbar-nav">
            <a href="#">Home</a>
            <a href="#about">Kerajinan</a>
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

    

    <!-- Tambah Data Button -->
    <div class="tambah-data">
        <a href="kelola.php" type="button" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i>
            Tambah Data
        </a>
    </div>
</body>
</html>
