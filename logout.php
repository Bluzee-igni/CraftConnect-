<?php
session_start();
session_destroy();
header('location:login.php');
?>
<script type+"text/javascript">
    alert('Selamat, Anda berhasil Log out');
    location.href ="login.php";
</script>