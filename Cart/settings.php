<?php
session_start();
include('config.php'); // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission to update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifications_enabled = isset($_POST['notifications_enabled']) ? 1 : 0;
    $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;
    $language = $_POST['language'];
    $timezone = $_POST['timezone'];

    // Update the user's settings in the database
    $query = "UPDATE users SET notifications_enabled = ?, dark_mode = ?, language = ?, timezone = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissi", $notifications_enabled, $dark_mode, $language, $timezone, $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the settings page with a success message
    header('Location: index.php?status=success');
    exit();
}

// Fetch current user settings
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
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
    <title>User Settings</title>
    <link rel="stylesheet" href="Styles/settings.css"> <!-- Link to external CSS -->
</head>

<body>

    <div class="settings-container">
        <h2>Update Your Settings</h2>

        <?php
        if (isset($_GET['status']) && $_GET['status'] === 'success') {
            echo "<p class='success-msg'>Settings updated successfully!</p>";
        }
        ?>

        <form method="POST" action="settings.php">
            <label for="notifications_enabled">Enable Notifications:</label>
            <input type="checkbox" name="notifications_enabled" id="notifications_enabled" <?php echo $user['notifications_enabled'] ? 'checked' : ''; ?>>

            <label for="dark_mode">Enable Dark Mode:</label>
            <input type="checkbox" name="dark_mode" id="dark_mode" <?php echo $user['dark_mode'] ? 'checked' : ''; ?>>

            <label for="language">Language:</label>
            <select name="language" id="language">
                <option value="en" <?php echo $user['language'] === 'en' ? 'selected' : ''; ?>>English</option>
                <!-- <option value="es" <?php echo $user['language'] === 'es' ? 'selected' : ''; ?>>Spanish</option> -->
                <!-- Add more language options as needed -->
            </select>

            <label for="timezone">Timezone:</label>
            <input type="text" name="timezone" id="timezone" value="<?php echo htmlspecialchars($user['timezone']); ?>"
                placeholder="e.g., India">

            <button type="submit">Save Settings</button>
        </form>
    </div>

</body>

</html>