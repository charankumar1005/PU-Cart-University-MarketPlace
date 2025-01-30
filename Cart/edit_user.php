<?php
session_start();
include 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Fetch user data
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user_query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    if (empty($username) || empty($email)) {
        echo "Username and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);

        $update_query = "UPDATE users SET username = '$username', email = '$email', is_active = '$is_active' WHERE id = $user_id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_message'] = "User updated successfully!";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error updating user: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Campus Cart</title>
    <link rel="stylesheet" href="Styles/edit.css">
</head>

<body>
    <header>
        <h1>Edit User</h1>
    </header>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
            required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="is_active">
            <input type="checkbox" id="is_active" name="is_active" <?php echo $user['is_active'] ? 'checked' : ''; ?>>
            Active
        </label>

        <button type="submit">Update User</button>
    </form>
</body>

</html>