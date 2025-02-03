<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ke App</title>
</head>
<body>

    <?php
        if(isset($_POST['username'])) {
            $username = $_POST ['username'];
            $password = md5($_POST ['password']);

            $query = mysqli_query($koneksi, "SELECT*FROM user where username='$username' and password='$password'");

            if (mysqli_num_rows($query) > 0){
                $data = mysqli_fetch_array($query);
                $_SESSION['user'] = $data;
                echo'<script>alert("Selamat datang, '.$data['nama'].'")  
                    location.href="Index.php"</script>';
            }else{

                echo '<script>alert("Username/password tidak sesuai.");</script>';

            }
        }

    ?>

    <form method="post">
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
                <td><button type="submit">login</button>
                <a href="daftar.php">daftar</a>
            </td>
            </tr>
       </table> 
    </form>
</body>
</html>