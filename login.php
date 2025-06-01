<?php
session_start();
include "koneksi.php";

if (isset($_POST['submit'])) { 
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) { 
        echo '<script>alert("Isi username dulu yukk.");</script>';
    } else {
        $password = md5($password); 
        $query = mysqli_query($koneksi, "SELECT * FROM db_pengguna WHERE username='$username' AND password='$password'");

       if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_array($query);
    $_SESSION['user'] = $data;
    $_SESSION['id_user'] = $data['id_user']; // Tambahkan baris ini agar bisa dipakai di profil.php

    if ($data['role'] == 'admin') {
        echo '<script>alert("Selamat datang, Admin ' . $data['nama'] . '"); location.href="admin.php";</script>';
    } else {
        echo '<script>alert("Selamat datang, ' . $data['nama'] . '"); location.href="Index.php";</script>';
    }
        } else {
            echo '<script>alert("Username atau password salah.");</script>';
        }
    }
}
    ?>

<!DOCTYPE html>

<head>
    <html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Ke App</title>
        <link rel="stylesheet" href="css1.css">
</head>

<body class=login>
    <form method="post" class="login-form">
        <table align="center">
            <tr>
            <td colspan="2" align="center"></td>
            <h3>Login</h3>
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
            <td><button name="submit" type="submit">login</button>
                <p>Belum punya akun Yuk</p><a href="daftar.php">daftar</a>
            </td>
            </tr>
        </table>
    </form>
</body>

</html>