<?php
session_start();
include 'config.php';

$error = ""; // Variable to hold error messages
$success = ""; // Variable to hold success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email and password fields
    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the user exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

            // Update the password in the database
            $update_query = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
            if (mysqli_query($conn, $update_query)) {
                $success = "Password updated successfully. You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Error updating password: " . mysqli_error($conn);
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
    <title>Reset Password - Campus Cart</title>
    <link rel="stylesheet" href="Styles/forgot_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>

        <!-- Display error message if any -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Display success message if any -->
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="new_password">New Password:</label>
            <div class="password-toggle">
                <input type="password" name="new_password" id="new_password" maxlength="20" required
                    onfocus="showPasswordRequirements()" oninput="validatePassword()">
                <span class="toggle-icon fas fa-eye" id="toggleNewPassword"
                    onclick="togglePasswordVisibility('new_password', 'toggleNewPassword')"></span>
            </div>

            <div class="password-requirements" id="password-requirements">
                <p>Password must:</p>
                <ul>
                    <li id="length" class="invalid">Be 7-20 characters long</li>
                    <li id="uppercase" class="invalid">Include at least 1 uppercase letter</li>
                    <li id="lowercase" class="invalid">Include at least 1 lowercase letter</li>
                    <li id="number" class="invalid">Include at least 1 number</li>
                    <li id="special" class="invalid">Include at least 1 special character</li>
                </ul>
            </div>

            <label for="confirm_password">Confirm Password:</label>
            <div class="password-toggle">
                <input type="password" name="confirm_password" id="confirm_password" maxlength="20" required
                    onfocus="showPasswordRequirements()" oninput="validatePassword()">
                <span class="toggle-icon fas fa-eye" id="toggleConfirmPassword"
                    onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassword')"></span>
            </div>

            <button type="submit">Update Password</button>
        </form>

        <p>Remembered your password? <a href="login.php">Login here</a></p>
    </div>

    <script>
        function togglePasswordVisibility(inputId, toggleId) {
            const inputField = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            const isPasswordVisible = inputField.type === 'password';

            inputField.type = isPasswordVisible ? 'text' : 'password'; // Toggle between text and password

            // Change icon based on visibility
            toggleIcon.classList.toggle('fa-eye', isPasswordVisible);
            toggleIcon.classList.toggle('fa-eye-slash', !isPasswordVisible);
        }

        function showPasswordRequirements() {
            const requirements = document.getElementById('password-requirements');
            requirements.style.display = "block"; // Show the requirements when the user focuses on the password fields
        }

        function validatePassword() {
            const newPassword = document.getElementById('new_password').value;
            const lengthCheck = document.getElementById('length');
            const uppercaseCheck = document.getElementById('uppercase');
            const lowercaseCheck = document.getElementById('lowercase');
            const numberCheck = document.getElementById('number');
            const specialCheck = document.getElementById('special');

            // Check password length
            lengthCheck.classList.toggle('valid', newPassword.length >= 7 && newPassword.length <= 20);
            lengthCheck.classList.toggle('invalid', newPassword.length < 7 || newPassword.length > 20);

            // Check for uppercase letter
            uppercaseCheck.classList.toggle('valid', /[A-Z]/.test(newPassword));
            uppercaseCheck.classList.toggle('invalid', !/[A-Z]/.test(newPassword));

            // Check for lowercase letter
            lowercaseCheck.classList.toggle('valid', /[a-z]/.test(newPassword));
            lowercaseCheck.classList.toggle('invalid', !/[a-z]/.test(newPassword));

            // Check for number
            numberCheck.classList.toggle('valid', /\d/.test(newPassword));
            numberCheck.classList.toggle('invalid', !/\d/.test(newPassword));

            // Check for special character
            specialCheck.classList.toggle('valid', /[!@#$%^&*(),.?":{}|<>]/.test(newPassword));
            specialCheck.classList.toggle('invalid', !/[!@#$%^&*(),.?":{}|<>]/.test(newPassword));

            // Validate confirm password field
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmLengthCheck = confirmPassword.length >= 7 && confirmPassword.length <= 20;
            const confirmMatches = newPassword === confirmPassword;

            if (confirmLengthCheck && confirmMatches) {
                // If confirm password meets the criteria, change its color
                document.getElementById('confirm_password').style.borderColor = 'green';
            } else {
                document.getElementById('confirm_password').style.borderColor = 'red';
            }
        }
    </script>
</body>

</html>