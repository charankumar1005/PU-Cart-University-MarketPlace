<?php
session_start();
include 'config.php';

$message = ""; // Variable to hold success/error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Validate the token
    $query = "SELECT * FROM users WHERE reset_token='$token' AND token_expiry > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password

        // Update the password and reset the token
        $updateQuery = "UPDATE users SET password='$hashed_password', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'";
        if (mysqli_query($conn, $updateQuery)) {
            $message = "Your password has been successfully reset. You can now log in.";
        } else {
            $message = "Error updating password.";
        }
    } else {
        $message = "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Campus Cart</title>
    <link rel="stylesheet" href="Styles/reset_password.css">
</head>

<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="reset_password.php" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

            <button type="submit">Reset Password</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>

</html>