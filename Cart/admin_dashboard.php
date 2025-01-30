<?php
session_start();
include 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);

// Fetch all products
$products_query = "SELECT * FROM products";
$products_result = mysqli_query($conn, $products_query);

//fetch the issues from the students
$issues_query = "SELECT * FROM issues_table";
$issues_result = mysqli_query($conn, $issues_query);

// Display success message if set
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']); // Clear success message after displaying
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Campus Cart</title>
    <link rel="stylesheet" href="Styles/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnKpiCvPjIWjJ3mOzXEMwNsfh5uM7E" crossorigin="anonymous">

</head>

<body>
    <!-- Navbar -->
    <header>
        <div class="navbar">
            <div class="logo-container">
                <h1>Admin Dashboard</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h2>Welcome, CharanGowd</h2>

        <?php if ($success_message): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <section class="user-management">
            <h3>User Management</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" <i class="fas fa-edit"></i>Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>">
                                    <i class="fas fa-trash"></i>Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="product-management">
            <h3>Product Management</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Seller ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo $product['seller_id']; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>">
                                    <i class="fas fa-edit"></i>Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>">
                                    <i class="fas fa-trash"></i>Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <section class="issue-management">
                <h3>Reported Issues</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($issue = mysqli_fetch_assoc($issues_result)): ?>
                            <tr>
                                <td><?php echo $issue['id']; ?></td>
                                <td><?php echo htmlspecialchars($issue['issue_description']); ?></td>
                                <td><?php echo $issue['created_at']; ?></td>
                                <td>
                                    <?php if ($issue['status'] == 'resolved'): ?>
                                        <span class="issue-status status-resolved">Resolved</span>
                                    <?php else: ?>
                                        <span class="issue-status status-pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="resolve_issue.php?id=<?php echo $issue['id']; ?>">Mark as Resolved</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Campus Cart. All Rights Reserved.</p>
    </footer>
</body>

</html>