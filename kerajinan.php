<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['user'])) {
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

 // Ambil ID user dari session login
$id_user = $_SESSION['id_user'] ?? 1; // Default 1 jika belum login

// Ambil ID produk dari parameter URL
$id_produk = $_GET['id'] ?? 0;
$id_produk = intval($id_produk); // pastikan integer

// Cek validasi ID produk
if ($id_produk <= 0) {
    die("Produk tidak ditemukan.");
}

// Cek apakah produk benar-benar ada di database
$cek_produk = $conn->query("SELECT * FROM produk WHERE id = '$id_produk'");
if (!$cek_produk || $cek_produk->num_rows == 0) {
    die("Produk tidak ditemukan di database.");
}

// Proses jika tombol like ditekan
if (isset($_POST['like'])) {
    $created_at = date('Y-m-d H:i:s');

    // Cek apakah user sudah menyukai produk ini
    $cek = $conn->query("SELECT * FROM likes WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
    
    if ($cek->num_rows == 0) {
        $query = "INSERT INTO likes (id_user, id_produk, created_at) VALUES ('$id_user', '$id_produk', '$created_at')";
        if (!$conn->query($query)) {
            echo "<p style='color:red;'>Gagal menyukai: " . $conn->error . "</p>";
        } else {
            // Refresh supaya POST tidak diulang jika di-refresh browser
            header("Location: kerajinan.php?id=$id_produk");
            exit;
        }
    }
}

// Cek status like untuk tombol
$sudah_like = false;
$cek = $conn->query("SELECT * FROM likes WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
if ($cek && $cek->num_rows > 0) {
    $sudah_like = true;
}

// Jumlah total like
$jumlah_like = 0;
$jumlah = $conn->query("SELECT COUNT(*) as total FROM likes WHERE id_produk = '$id_produk'");
if ($jumlah) {
    $jumlah_like = $jumlah->fetch_assoc()['total'];
}
?>

<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kerajinan</title>
        <link rel="stylesheet" href="css1.css">
    </head>

    <body>
        <nav class="navbar">
            <a href="#" class="navbar-logo">Craft<span>Connect</span>.</a>

            <div class="navbar-nav">
                <a href="Index.php">Home</a>
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
                            <form method="POST">
                            <button type="submit" name="like" <?php if($sudah_like) echo 'disabled'; ?>>
                                <?php echo $sudah_like ? "✔️ Sudah Disukai" : "👍 Like"; ?>
                            </button>
                        </form>
                                                    <p>Total Like: <?php echo $jumlah_like; ?></p>
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
    </body>

    </html>