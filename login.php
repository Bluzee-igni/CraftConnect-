<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>

<head>
    <html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Ke App</title>
        <link rel="stylesheet" href="css 1.css">
</head>

<body class=login>

    <?php

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");

        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_array($query);
            $_SESSION['user'] = $data;

            if ($data['role'] == 'admin') {
                echo '<script>alert("Selamat datang, Admin ' . $data['nama'] . '"); location.href="indexadmin.php";</script>';
            } else {
                echo '<script>alert("Selamat datang, ' . $data['nama'] . '"); location.href="Index.php";</script>';
            }
        } else {
            echo '<script>alert("Username/password tidak sesuai.");</script>';
        }
    }
    ?>

    <form method="post" class="login-form">
        <table align="center">
            <tr>
            <td colspan="2" align="center"></td>
            <h3>Login User</h3>
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
                <a href="daftar.php">daftar</a>
            </td>
            </tr>
        </table>
    </form>
</body>

</html>