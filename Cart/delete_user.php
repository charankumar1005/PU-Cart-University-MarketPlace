<?php
session_start();
include 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Delete user
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "User deleted successfully!";
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}

header("Location: admin_dashboard.php");
exit();
