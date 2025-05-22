<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css1.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ke App</title>
</head>

<body class="login">
    <?php


    if (isset($_POST['submit'])) {
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = mysqli_query($koneksi, "INSERT INTO db_pengguna (nama, username, password) 
                values('$nama','$username','$password')");
        if ($query) {
            echo '<script>alert("Selamat, Pendaftaran anda berhasil.")';
        } else {
            echo '<script>alert("Pendaftaran Gagal.")</script>';
        }
        header('Location: login.php');
    }

    ?>
    <form method="post" class="login-form">
        <table align="center">
            <tr>
                <td colspan="2" align="center"></td>
                <h3>Registrasi</h3>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama"></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td></td>
                <td><button name="submit" type="submit">Registrasi</button>
                    <a href="login.php">Login</a>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>