<?php
// Include the database configuration file
include 'config.php';
// Include phpMailer files
// require 'phpMailer-master/PHPMailerAutoload.php';
require 'phpMailer-master/phpMailer-master/src/Exception.php';
require 'phpMailer-master/phpMailer-master/src/PHPMailer.php';
require 'phpMailer-master/phpMailer-master/src/SMTP.php';
// require 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Start session to store OTP and user data temporarily

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    // 

    // $address = $_POST['address'];
    // $latitude = $_POST['latitude'];  // Ensure this is received from the form or geolocation API
    // $longitude = $_POST['longitude'];

    // Ensure email ends with @pondiuni.ac.in
    if (preg_match('/^[a-zA-Z0-9._%+-]+@pondiuni\.ac\.in$/', $email)) {
        // Server-side password validation for complexity
        if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{7,20}$/', $password)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Check if email already exists
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                // Generate OTP
                $otp = rand(100000, 999999);

                // Store user data and OTP in session variables
                $_SESSION['otp'] = $otp;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;
                $_SESSION['password'] = $hashed_password;
                // $_SESSION['address'] = $address;
                // $_SESSION['latitude'] = $latitude;
                // $_SESSION['longitude'] = $longitude;


                // Send OTP to the user's email using phpMailer
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '';//usE your email
                $mail->Password = '';  // Use the App-Specific Password here
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
               


                $mail->setFrom('email', 'name'); // Replace with your email and name
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'OTP Verification for Campus Cart Registration';
                $mail->Body = "Your OTP for registration is: <b>$otp</b>";

                if ($mail->send()) {
                    header("Location: verify_otp.php"); // Redirect to OTP verification page
                    exit();
                } else {
                    echo "Email could not be sent. Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                echo "Email already registered.";
            }
        } else {
            echo "Password does not meet the complexity requirements.";
        }
    } else {
        echo "Please enter a valid university email address ending with @pondiuni.ac.in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Campus Cart</title>
    <link rel="stylesheet" href="Styles/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <script async defer -->
    <!-- src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAN0Y-mKucJZZJ5tGV8XwakE-fGqQ3hJZ8&libraries=places"></script> -->
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <label for="username">Username*</label>
            <input type="text" name="username" id="username" required placeholder="Enter your username">

            <label for="email">Email*</label>
            <input type="email" name="email" id="email" required pattern="[a-zA-Z0-9._%+-]+@pondiuni\.ac\.in"
                title="Please enter a valid university email address ending with @pondiuni.ac.in"
                placeholder="your Reg No@pondiuni.ac.in">
            <p style="color: red; text-align: center;">Enter a Valid Email, used for verification**</p>

            <label for="mobile">Mobile No*</label>
            <input type="text" name="mobile" id="mobile" pattern="\d{10}"
                title="Please enter a valid 10-digit mobile number" required maxlength="10"
                placeholder="Enter your 10-digit mobile number" oninput="checkMobileLength()">
            <p class="instructions" id="mobileError" style="color: red; display: none;">Mobile number must be exactly 10
                digits.</p>

            <label for="password">Password*</label>
            <div class="password-toggle">
                <input type="password" name="password" id="password"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,20}"
                    title="Password must be 7-20 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character."
                    required placeholder="Enter your password" onfocus="showInstructions()" oninput="validatePassword()"
                    maxlength="20">
                <i class="toggle-icon fas fa-eye" id="toggle-password" onclick="togglePasswordVisibility()"></i>
            </div>

            <!-- Password strength meter -->
            <div class="password-strength">
                <div class="strength-bar" id="strength-bar"></div>
            </div>

            <!-- Password requirements displayed in one line with smaller font -->
            <div class="password-instructions" id="password-instructions">
                <span id="length" class="invalid">7-20 characters</span>
                <span id="uppercase" class="invalid">1 uppercase</span>
                <span id="lowercase" class="invalid">1 lowercase</span>
                <span id="number" class="invalid">1 number</span>
                <span id="special" class="invalid">1 special character</span>
            </div>
            <p id="password-limit" style="color: red; display: none;">You can't provide more than 20 characters.</p>
            <!-- <label for="address">Address:*</label> -->
            <!-- <input type="text" name="address" id="address" placeholder="Click to select location" readonly required -->
            <!-- onclick="initMap()"> -->
            <!-- <button type="button" onclick="initMap()">Select Location on Map</button> -->

            <!-- <div id="map" style="width: 100%; height: 300px; display: none;"></div> -->

            <button type="submit">Register</button>
        </form>

        <div class="cta-text">
            <p>Already have an account? <a href="login.php?redirect=become_seller.php">Login here</a></p>
        </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-password');

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

        // Function to check if mobile number is exactly 10 digits
        function checkMobileLength() {
            const mobileField = document.getElementById('mobile');
            const mobileError = document.getElementById('mobileError');

            if (mobileField.value.length === 10) {
                mobileError.style.display = "none";
            } else {
                mobileError.style.display = "block";
            }
        }

        // Show instructions when the password field is focused
        function showInstructions() {
            const instructions = document.getElementById('password-instructions');
            instructions.classList.add("visible");
        }

        // Validate password in real-time and display strength
        function validatePassword() {
            const passwordField = document.getElementById('password');
            const strengthBar = document.getElementById('strength-bar');
            const lengthRequirement = document.getElementById('length');
            const uppercaseRequirement = document.getElementById('uppercase');
            const lowercaseRequirement = document.getElementById('lowercase');
            const numberRequirement = document.getElementById('number');
            const specialRequirement = document.getElementById('special');
            const passwordLimit = document.getElementById('password-limit');

            const password = passwordField.value;
            let strength = 0;

            // Show a warning if password exceeds 20 characters
            if (password.length > 20) {
                passwordLimit.style.display = "block";
                passwordField.value = password.substring(0, 20);  // Limit the input to 20 characters
                return;  // Stop further execution
            } else {
                passwordLimit.style.display = "none";
            }

            // Validate length (7-20 characters)
            if (password.length >= 7 && password.length <= 20) {
                lengthRequirement.classList.remove("invalid");
                lengthRequirement.classList.add("valid");
                strength += 1;
            } else {
                lengthRequirement.classList.remove("valid");
                lengthRequirement.classList.add("invalid");
            }

            // Validate uppercase letter
            if (/[A-Z]/.test(password)) {
                uppercaseRequirement.classList.remove("invalid");
                uppercaseRequirement.classList.add("valid");
                strength += 1;
            } else {
                uppercaseRequirement.classList.remove("valid");
                uppercaseRequirement.classList.add("invalid");
            }

            // Validate lowercase letter
            if (/[a-z]/.test(password)) {
                lowercaseRequirement.classList.remove("invalid");
                lowercaseRequirement.classList.add("valid");
                strength += 1;
            } else {
                lowercaseRequirement.classList.remove("valid");
                lowercaseRequirement.classList.add("invalid");
            }

            // Validate number
            if (/\d/.test(password)) {
                numberRequirement.classList.remove("invalid");
                numberRequirement.classList.add("valid");
                strength += 1;
            } else {
                numberRequirement.classList.remove("valid");
                numberRequirement.classList.add("invalid");
            }

            // Validate special character
            if (/[\W_]/.test(password)) {
                specialRequirement.classList.remove("invalid");
                specialRequirement.classList.add("valid");
                strength += 1;
            } else {
                specialRequirement.classList.remove("valid");
                specialRequirement.classList.add("invalid");
            }

            // Update strength bar based on criteria met
            if (strength === 5) {
                strengthBar.style.width = "100%";
                strengthBar.className = "strength-bar strong";
            } else if (strength >= 3) {
                strengthBar.style.width = "66%";
                strengthBar.className = "strength-bar medium";
            } else if (strength >= 1) {
                strengthBar.style.width = "33%";
                strengthBar.className = "strength-bar weak";
            } else {
                strengthBar.style.width = "0";
                strengthBar.className = "strength-bar weak";
            }
        }

        // Validate the form on submission
        function validateForm() {
            validatePassword();  // Ensure password validation is checked
            const strengthBar = document.getElementById('strength-bar');
            return strengthBar.style.width === "100%"; // Allow submission only if the password is strong
        }

        // new added location
    </script>
</body>

</html>