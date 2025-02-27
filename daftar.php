<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css 1.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ke App</title>
</head>
<body class="login">
    <?php

        if(isset($_POST['username'])){
            $nama = $_POST['nama'];
            $username = $_POST['username'];
            $password = md5($_POST['password']);
        

            $query = mysqli_query($koneksi,"INSERT INTO user(nama,username,password) 
                values('$nama','$username','$password')");
            if ($query) {
                echo '<script>alert("Selamat, Pendaftaran anda berhasil.")</script>';
            } else {
                echo '<script>alert("Pendaftaran Gagal.")</script>';
            }
        }  

    ?>
    <form method="post" class="login-form">
       <table align="center" >
            <tr>
                <td colspan="2" align="center"></td>
                <h3>Pendaftaran User</h3>
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
                <td><button type="submit">Daftar User</button>
                <a href="login.php">Login</a>
            </td>
            </tr>
       </table> 
    </form>
</body>
</html>