<?php
session_start();
include 'config.php';

// Check if the admin is already logged in
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $query = "SELECT * FROM admins WHERE email = ? AND is_admin = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Debugging: Uncomment the next line to see fetched admin data
    // var_dump($admin);

    if ($admin && $password == $admin['password']) {
        // Set session variables for the admin
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['is_admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Campus Cart</title>
    <link rel="stylesheet" href="Styles/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>