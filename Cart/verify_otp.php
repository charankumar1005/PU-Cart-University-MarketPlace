<?php
session_start();
include('config.php'); // Include the database configuration file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the OTP entered by the user
    $email_otp = $_POST['email_otp'];

    // Compare the entered OTP with the one stored in the session
    if ($email_otp == $_SESSION['otp']) {
        // OTP matches, proceed with registration
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $mobile = $_SESSION['mobile'];
        $password = $_SESSION['password'];

        // Insert into the users table (modify as per your DB schema)
        $query = "INSERT INTO users (username, email, mobile, password) VALUES ('$username', '$email', '$mobile', '$password')";
        if (mysqli_query($conn, $query)) {
            // Clear session data after successful registration
            session_unset();
            session_destroy();

            echo "Registration successful!";
            header("Location: login.php"); // Redirect to the login page
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // OTP does not match, show an error
        echo "Invalid OTP, please try again.";
    }
}
?>




<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f0f0f5;
    }

    .otp-container {
        width: 300px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    h2 {
        margin-bottom: 15px;
        color: #333;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #555;
    }

    input[type="text"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }

    .note {
        margin-top: 15px;
        font-size: 12px;
        color: #777;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>

</head>

<body>
    <div class="otp-container">
        <h2>Verify OTP</h2>
        <form method="POST">
            <!-- <label for="email">Enter University email:</label> -->
            <!-- <input type="text" name="email" required> -->
            <label for="email_otp">Enter OTP sent to your email:</label>
            <input type="text" name="email_otp" required>
            <button type="submit">Verify OTP</button>
        </form>
        <p class="note">If you didn't receive the OTP, check your email inbox or spam folder.</p>
    </div>
</body>

</html>