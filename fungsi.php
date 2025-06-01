<?php
include 'koneksi.php';

function tambah_data($data, $files)
{
    $id_user = $_SESSION['user']['id_user'];
    $id_kategori = $data['id_kategori'];
    $nama_produk = $data['nama_produk'];
    $penjelasan = $data['penjelasan'];
    $harga = $data['harga']; 

    $split = explode('.', $files['foto_produk']['name']);
    $ekstensi = $split[count($split) - 1];
    $foto_produk = uniqid() . '.' . $ekstensi;

    $dir = "img/";
    $tmpFile = $files['foto_produk']['tmp_name'];

    move_uploaded_file($tmpFile, $dir . $foto_produk);

    $query = "INSERT INTO db_produk (id_user, id_kategori, nama_produk, penjelasan, harga, foto_produk) 
              VALUES ('$id_user', '$id_kategori', '$nama_produk', '$penjelasan', '$harga', '$foto_produk')";
    $sql = mysqli_query($GLOBALS['koneksi'], $query);

    return true;
}

function ubah_data($data, $files)
{
    $id_produk = $data['id_produk'];
    $nama_produk = $data['nama_produk'];
    $penjelasan = $data['penjelasan'];
    $harga = $data['harga']; 

    $queryShow = "SELECT * FROM db_produk WHERE id_produk = '$id_produk';";
    $sqlShow = mysqli_query($GLOBALS['koneksi'], $queryShow);
    $result = mysqli_fetch_assoc($sqlShow);

    if ($files['foto_produk']['name'] == "") {
        $foto_produk = $result['foto_produk'];
    } else {
        $split = explode('.', $files['foto_produk']['name']);
        $ekstensi = end($split);
        $foto_produk = uniqid() . '.' . $ekstensi;

        unlink("img/" . $result['foto_produk']);
        move_uploaded_file($files['foto_produk']['tmp_name'], 'img/' . $foto_produk);
    }

    $query = "UPDATE db_produk 
              SET nama_produk='$nama_produk', penjelasan='$penjelasan', harga='$harga', foto_produk='$foto_produk' 
              WHERE id_produk='$id_produk';";
    $sql = mysqli_query($GLOBALS['koneksi'], $query);

    return true;
}

function hapus_data($data)
{
    $id_produk = $data['hapus'];

    $queryShow = "SELECT * FROM db_produk WHERE id_produk = '$id_produk';";
    $sqlShow = mysqli_query($GLOBALS['koneksi'], $queryShow);
    $result = mysqli_fetch_assoc($sqlShow);

    unlink("img/" . $result['foto_produk']);

    $query = "DELETE FROM db_produk WHERE id_produk = '$id_produk';";
    $sql = mysqli_query($GLOBALS['koneksi'], $query);

    return true;
}
