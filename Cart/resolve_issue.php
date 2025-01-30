<?php
include 'config.php';

// Check if the user is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: admin_login.php");
    exit();
}

// Get the issue ID from the query string
$issue_id = $_GET['id'];

// Update the status to 'resolved'
$stmt = $conn->prepare("UPDATE issues_table SET status = 'resolved' WHERE id = ?");
$stmt->bind_param("i", $issue_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Issue marked as resolved successfully.";
} else {
    $_SESSION['success_message'] = "Error updating issue status: " . $stmt->error;
}

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
$stmt->close();
$conn->close();
?>