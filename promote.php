<?php
include 'koneksi.php';
session_start();

if (isset($_GET['promote'])) {
    $userId = $_GET['promote'];

    // Update user role to admin in the database
    $query = "UPDATE tb_users SET role = 'admin' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User promoted to admin successfully.";
    } else {
        $_SESSION['message'] = "Failed to promote user.";
    }

    $stmt->close();
    header("Location: views/admin_table.php");
    exit();
}
?>