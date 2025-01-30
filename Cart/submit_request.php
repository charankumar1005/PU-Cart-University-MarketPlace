<?php
// submit_request.php
include 'config.php'; // Database connection

// Function to remove expired requests
function cleanupExpiredRequests($conn)
{
    $sql = "DELETE FROM urgent_requests WHERE deadline < CURDATE()";
    if (!$conn->query($sql)) {
        error_log("Error deleting expired requests: " . $conn->error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Call the cleanup function before processing new requests
    cleanupExpiredRequests($conn);

    // Retrieve form data and sanitize inputs
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $urgency = htmlspecialchars($_POST['urgency']);
    $category = htmlspecialchars($_POST['category']);
    $deadline = $_POST['deadline']; // Date does not need special character handling

    // Check if the deadline is valid (not in the past)
    if (strtotime($deadline) < strtotime(date('Y-m-d'))) {
        echo "<p>Error: The deadline cannot be in the past.</p>";
        echo "<p>Please <a href='Quick_requirements.php'>try again</a> with a valid deadline.</p>";
        exit();
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO urgent_requests (title, description, urgency, category, deadline, posted_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $title, $description, $urgency, $category, $deadline);

    // Execute the query and handle errors if any
    if ($stmt->execute()) {
        echo "<p>Your request has been posted successfully!</p>";
        echo "<p>You will be redirected shortly...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'Quick_requirements.php';
                }, 3000); // 3000 milliseconds = 3 seconds
              </script>";
    } else {
        echo "<p>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $conn->close();
} else {
    // If accessed without POST, redirect to the main form page
    header("Location: Quick_requirements.php");
    exit();
}
?>