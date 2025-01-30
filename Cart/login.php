<?php
session_start();
include 'config.php';

$error = "";  // Variable to hold error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic email validation
    if (empty($email) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        // Check if the user exists in the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Check if the user is admin (you may need to adjust this condition)
                if ($row['role'] === 'admin') { // Assuming you have a 'role' field in the users table
                    header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                } else {
                    // Redirect to the intended page (e.g., become_seller.php) or homepage
                    if (isset($_GET['redirect'])) {
                        header("Location: " . $_GET['redirect']);
                    } else {
                        header("Location: index.php");
                    }
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Campus Cart</title>
    <link rel="stylesheet" href="Styles/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if any -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php<?php if (isset($_GET['redirect']))
            echo '?redirect=' . urlencode($_GET['redirect']); ?>" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <div class="password-toggle">
                <input type="password" name="password" id="password" required>
                <i class="toggle-icon fas fa-eye" id="toggle-password" onclick="togglePasswordVisibility()"></i>
            </div>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php<?php if (isset($_GET['redirect']))
            echo '?redirect=' . urlencode($_GET['redirect']); ?>">Register
                here</a></p>
        <p><a href="forgot_password.php">Forgot Password?</a></p> <!-- Link to Forgot Password -->
    </div>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.getElementById("toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>