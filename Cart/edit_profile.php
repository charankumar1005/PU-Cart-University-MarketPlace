<?php
session_start();
include('config.php'); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update the user's profile in the database
    $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssi', $username, $email, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update the session with the new username
    $_SESSION['username'] = $username;

    // Redirect to the homepage or profile page after the update
    header('Location: index.php');
    exit();
}

// Fetch current user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="Styles/edit_profile.css"> <!-- Link to your stylesheet -->
</head>

<body>
    <div class="form-container">
        <h2>Edit Profile</h2>

        <?php if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        } ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>


</html>