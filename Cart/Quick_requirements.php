<?php
// submit_request.php
include 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $urgency = $_POST['urgency'];
    $category = $_POST['category'];
    $deadline = $_POST['deadline'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO urgent_requests (title, description, urgency, category, deadline, posted_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $title, $description, $urgency, $category, $deadline);

    if ($stmt->execute()) {
        echo "Your request has been posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!-- Urgent Requests Form -->
<html>

<head>
    <title>Requirements</title>
    <style>
        .urgent-request-form,
        .urgent-requests {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .urgent-request-form h2,
        .urgent-requests h2 {
            text-align: center;
            color: #333;
        }

        .urgent-request-form label,
        .urgent-requests .request {
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .urgent-request-form input[type="text"],
        .urgent-request-form textarea,
        .urgent-request-form select,
        .urgent-request-form input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .urgent-request-form button,
        .back-button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
        }

        .urgent-request-form button:hover,
        .back-button:hover {
            background-color: #218838;
        }

        .urgent-requests .request {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .urgent-requests .request h3 {
            color: #d9534f;
            font-weight: bold;
        }
    </style>
</head>


<body>

    <div class="urgent-request-form">
        <h2>Post an Urgent Request</h2>
        <form action="submit_request.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required placeholder="What do you need urgently?">

            <label for="description">Description:</label>
            <textarea id="description" name="description" required placeholder="Describe your need"></textarea>

            <label for="urgency">Urgency Level:</label>
            <select id="urgency" name="urgency">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>

            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="Books">Books</option>
                <option value="Electronics">Electronics</option>
                <option value="Services">Services</option>
                <option value="Other">Other</option>
            </select>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" required>

            <button type="submit">Post Request</button>
        </form>
        <button class="back-button" onclick="window.location.href='index.php';">Go Back</button>
    </div>

    <div class="urgent-requests">
        <h2>Urgent Requests</h2>
        <?php
        include 'config.php'; // Database connection
        
        // Function to remove expired requests
        function cleanupExpiredRequests($conn)
        {
            $sql = "DELETE FROM urgent_requests WHERE deadline < CURDATE()";
            if (!$conn->query($sql)) {
                error_log("Error deleting expired requests: " . $conn->error);
            }
        }

        // Cleanup expired requests before displaying
        cleanupExpiredRequests($conn);

        // Display only active requests
        $query = "SELECT * FROM urgent_requests WHERE deadline >= CURDATE() ORDER BY posted_at DESC LIMIT 5";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='request'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><strong>Urgency:</strong> " . htmlspecialchars($row['urgency']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p><strong>Deadline:</strong> " . htmlspecialchars($row['deadline']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No urgent requests at the moment.</p>";
        }

        $conn->close();
        ?>
    </div>

</body>

</html>