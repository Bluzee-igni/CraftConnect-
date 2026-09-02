<?php
session_start();
include '../koneksi.php';

// Cek apakah user login
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak. Silakan login.");
}

if (isset($_GET['hapus'])) {
    $id_produk = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    // Ambil nama file gambar dulu
    $q_foto = mysqli_query($koneksi, "SELECT foto_produk FROM db_produk WHERE id_produk = '$id_produk'");
    $data_foto = mysqli_fetch_assoc($q_foto);
    $foto = $data_foto['foto_produk'];

    // Hapus gambar dari folder (jika ada)
    if ($foto && file_exists("img/" . $foto)) {
        unlink("img/" . $foto);
    }

    // Hapus data produk
    $hapus = mysqli_query($koneksi, "DELETE FROM db_produk WHERE id_produk = '$id_produk'");

    if ($hapus) {
        $_SESSION['eksekusi'] = "hapus-berhasil";
    } else {
        $_SESSION['eksekusi'] = "hapus-gagal";
    }

    header("Location: ../kelola_kerajinan.php");
    exit;
}

if (isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    // Escape input
    $id_produk = mysqli_real_escape_string($koneksi, $_POST['id_produk'] ?? '');
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk'] ?? '');
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori'] ?? '');
    $penjelasan = mysqli_real_escape_string($koneksi, $_POST['penjelasan'] ?? '');
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga'] ?? '');

    $id_user = $_SESSION['id_user'];
    $foto = null;

    if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['name'] !== '') {
        $foto = $_FILES['foto_produk']['name'];
        $tmp = $_FILES['foto_produk']['tmp_name'];

        // Validasi file
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            die("Format file tidak diizinkan.");
        }

        // Rename file agar unik
        $foto = time() . "_" . $foto;
        move_uploaded_file($tmp, "img/" . $foto);
    }

    if ($aksi === 'add') {
        $query = "INSERT INTO db_produk 
                    (nama_produk, id_kategori, penjelasan, harga, foto_produk, id_user) 
                  VALUES 
                    ('$nama_produk', '$id_kategori', '$penjelasan', '$harga', '$foto', '$id_user')";
    } elseif ($aksi === 'edit') {
        $q_foto = mysqli_query($koneksi, "SELECT foto_produk FROM db_produk WHERE id_produk='$id_produk'");
        $data_foto = mysqli_fetch_assoc($q_foto);
        $foto_lama = $data_foto['foto_produk'];

        $query = "UPDATE db_produk SET 
                    nama_produk = '$nama_produk',
                    id_kategori = '$id_kategori',
                    penjelasan = '$penjelasan',
                    harga = '$harga'";
        if ($foto) {
            $query .= ", foto_produk = '$foto'";
            if (file_exists("img/" . $foto_lama)) {
                unlink("img/" . $foto_lama);
            }
        }
        $query .= " WHERE id_produk = '$id_produk'";
    } elseif ($aksi === 'delete') {
        // Hapus data dan gambar
        $q_foto = mysqli_query($koneksi, "SELECT foto_produk FROM db_produk WHERE id_produk='$id_produk'");
        $data_foto = mysqli_fetch_assoc($q_foto);
        $foto_lama = $data_foto['foto_produk'];

        if ($foto_lama && file_exists("img/" . $foto_lama)) {
            unlink("img/" . $foto_lama);
        }

        $query = "DELETE FROM db_produk WHERE id_produk = '$id_produk'";
    }

    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {
        $_SESSION['eksekusi'] = "berhasil";
        header("Location: ../kelola_kerajinan.php");
    } else {
        echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
    }
    exit();
}
?>


