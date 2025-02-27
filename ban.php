<?php
include 'koneksi.php';
session_start();

if (isset($_GET['ban'])) {
    $userId = $_GET['ban'];

    // Update the user's status to banned in the database
    $query = "UPDATE users SET status = 'banned' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User has been banned successfully.";
    } else {
        $_SESSION['message'] = "Failed to ban user.";
    }

    $stmt->close();
    header("Location: views/admin_table.php");
    exit();
}
?>