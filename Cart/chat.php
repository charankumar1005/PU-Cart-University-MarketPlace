<?php
session_start();
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // ID of the logged-in user

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['receiver_id'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    if (!empty($message)) {
        // Insert the message into the database
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $receiver_id, $message);
        $stmt->execute();
    }
}

// Fetch all users who have added products (Join users with products table)
$users_result = $conn->query("
    SELECT u.id, u.username 
    FROM users u
    JOIN products p ON u.id = p.seller_id
    WHERE u.id != $user_id
    GROUP BY u.id
");

// Get selected receiver's username for displaying in chat
$receiver_id = $_GET['user'] ?? 0;
$receiver_result = $conn->query("SELECT username FROM users WHERE id = $receiver_id");
$receiver = $receiver_result->fetch_assoc();
$receiver_name = $receiver ? $receiver['username'] : 'Unknown User';

// Fetch chat messages with a specific recipient
$messages_result = $conn->query("
    SELECT m.*, u.username AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE (m.sender_id = $user_id AND m.receiver_id = $receiver_id)
       OR (m.sender_id = $receiver_id AND m.receiver_id = $user_id)
    ORDER BY m.timestamp ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Students</title>
    <link rel="stylesheet" href="styles/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .go-back-btn {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <div class="chat-sidebar">
            <h3>Select a Student to Chat</h3>
            <ul class="user-list">
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <li>
                        <a href="chat.php?user=<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="chat-box">

            <h3>Chat with <?php echo htmlspecialchars($receiver_name); ?></h3> <!-- Display receiver's name here -->
            <div class="messages">
                <?php while ($msg = $messages_result->fetch_assoc()): ?>
                    <div class="message <?php echo $msg['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                        <strong><?php echo htmlspecialchars($msg['sender_name']); ?>:</strong>
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        <small><?php echo $msg['timestamp']; ?></small>
                    </div>
                <?php endwhile; ?>
            </div>

            <form action="chat.php?user=<?php echo $receiver_id; ?>" method="POST" class="message-form">
                <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                <input type="text" name="message" placeholder="Type a message" required>
                <button type="submit"><i class="fas fa-paper-plane"></i> Send</button>
            </form>
        </div>

    </div>
    <button onclick="window.location.href='index.php'" class="go-back-btn">Go Back</button>
</body>

</html>