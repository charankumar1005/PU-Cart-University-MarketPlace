<?php
// Include the database configuration file
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the issue description from the form
    $issue_description = $_POST['issue_description'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO issues_table (issue_description) VALUES (?)");
    $stmt->bind_param("s", $issue_description); // 's' specifies the variable type => 'string'

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Issue reported successfully!";
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect back to the Report an Issue page
    header("Location: report_issue.php?status=success");
    exit();
} else {
    // Redirect to the report issue page if accessed incorrectly
    header("Location: report_issue.php");
    exit();
}
?>