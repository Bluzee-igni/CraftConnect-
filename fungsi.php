<?php
    include 'koneksi.php';

    function tambah_data($data, $files){
        $nama_produk = $data['nama_produk'];
        $penjelasan = $data['penjelasan'];
        $link_pembelian = $data['link_pembelian'];

        $split = explode('.', $files['foto_produk']['name']);
        $ekstensi = $split[count($split)-1];
        $foto_produk = uniqid() . '.' . $ekstensi;

        $dir = "img/";
        $tmpFile = $files['foto_produk']['tmp_name'];

        move_uploaded_file($tmpFile, $dir . $foto_produk);
        
        $query = "INSERT INTO posting (nama_produk, penjelasan, link_pembelian, foto_produk) VALUES ('$nama_produk', '$penjelasan', '$link_pembelian', '$foto_produk')";
        $sql = mysqli_query($GLOBALS['koneksi'], $query);

        return true;
    }

    function ubah_data($data, $files){
        $id_produk = $data['id_produk'];
        $nama_produk = $data['nama_produk'];
        $penjelasan = $data['penjelasan'];
        $link_pembelian = $data['link_pembelian'];

        $queryShow = "SELECT * FROM posting WHERE id_produk = '$id_produk';";
        $sqlShow = mysqli_query($GLOBALS['koneksi'], $queryShow);
        $result = mysqli_fetch_assoc($sqlShow);

        if($files['foto_produk']['name'] == ""){
            $foto_produk = $result['foto_produk'];
        } else {
            $split = explode('.', $files['foto_produk']['name']);
            $ekstensi = $split[count($split)-1];
            $foto_produk = uniqid() . '.' . $ekstensi;
            unlink("img/" . $result['foto_produk']);
            move_uploaded_file($files['foto_produk']['tmp_name'], 'img/' . $foto_produk);
        }

        $query = "UPDATE posting SET nama_produk='$nama_produk', penjelasan='$penjelasan', link_pembelian='$link_pembelian', foto_produk='$foto_produk' WHERE id_produk='$id_produk';";
        $sql = mysqli_query($GLOBALS['koneksi'], $query);

        return true;
    }

    function hapus_data($data){
        $id_produk = $data['hapus'];

        $queryShow = "SELECT * FROM posting WHERE id_produk = '$id_produk';";
        $sqlShow = mysqli_query($GLOBALS['koneksi'], $queryShow);
        $result = mysqli_fetch_assoc($sqlShow);

        unlink("img/" . $result['foto_produk']);

        $query = "DELETE FROM posting WHERE id_produk = '$id_produk';";
        $sql = mysqli_query($GLOBALS['koneksi'], $query);

        return true;
    }
?>