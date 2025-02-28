<?php
include 'koneksi.php';

if (isset($_GET['id_user'])) {
    $id_user = intval($_GET['id_user']);
    $query = "UPDATE user SET role = 'admin' WHERE id_user = $id_user";
    mysqli_query($koneksi, $query);
    header('Location: Admin.php');
}
?>