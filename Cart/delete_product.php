<?php
session_start();
include 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Delete product
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $delete_query = "DELETE FROM products WHERE id = $product_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
}

header("Location: admin_dashboard.php");
exit();
