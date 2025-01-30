<?php
session_start();
include 'config.php';

// Fetch product data if product ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product_query = "SELECT * FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);

    // If no product is found, redirect back to admin dashboard
    if (mysqli_num_rows($product_result) == 0) {
        header("Location: admin_dashboard.php");
        exit();  // Stop further script execution
    }

    $product = mysqli_fetch_assoc($product_result);
} else {
    // If no product ID is provided, redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();  // Stop further script execution
}

// Update product data after the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    // $category = trim($_POST['category']); // Add category field
    // $urgency = trim($_POST['urgency']); // Add urgency field
    $negotiable = trim($_POST['negotiable']); // Add negotiable field

    // Validation checks for the input fields
    if (empty($name) || empty($description)) {
        echo "Product name and description are required.";
    } elseif (!is_numeric($price) || $price < 0) {
        echo "Invalid price.";
    } else {
        // Sanitize input values to prevent SQL injection
        $name = mysqli_real_escape_string($conn, $name);
        $price = mysqli_real_escape_string($conn, $price);
        $description = mysqli_real_escape_string($conn, $description);
        // $category = mysqli_real_escape_string($conn, $category); // Sanitize category
        // $urgency = mysqli_real_escape_string($conn, $urgency); // Sanitize urgency
        $negotiable = mysqli_real_escape_string($conn, $negotiable); // Sanitize negotiable

        // Update query for the product, including category, urgency, and negotiable fields
        $update_query = "UPDATE products SET 
                            name = '$name', 
                            price = '$price', 
                            description = '$description',
                            -- category = '$category', 
                            -- urgency = '$urgency', 
                            negotiable = '$negotiable'
                          WHERE id = $product_id";

        // Execute the update query and check for success
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_message'] = "Product updated successfully!";
            header("Location: products.php");
            exit();  // Redirect to admin dashboard after update
        } else {
            echo "Error updating product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Campus Cart</title>
    <link rel="stylesheet" href="Styles/edit.css">
</head>

<body>
    <header>
        <h1>Edit Product</h1>
    </header>

    <form method="POST">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"
            required>

        <!-- <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Electronics" <?php echo ($product['category'] == 'Electronics') ? 'selected' : ''; ?>>
                Electronics</option>
            <option value="Clothing" <?php echo ($product['category'] == 'Clothing') ? 'selected' : ''; ?>>Clothing
            </option>
            <option value="Books" <?php echo ($product['category'] == 'Books') ? 'selected' : ''; ?>>Books</option>
            <option value="Furniture" <?php echo ($product['category'] == 'Furniture') ? 'selected' : ''; ?>>Furniture
            </option>
            <option value="Stationery" <?php echo ($product['category'] == 'Stationery') ? 'selected' : ''; ?>>Stationery
            </option>
            <!-- Add more categories as needed -->
        <!-- </select> -->

        <!-- <label for="urgency">Urgency Level:</label>
        <input type="text" id="urgency" name="urgency" value="<?php echo htmlspecialchars($product['urgency']); ?>"
            required> -->

        <label for="negotiable">Negotiable:</label>
        <select id="negotiable" name="negotiable" required>
            <option value="Yes" <?php echo ($product['negotiable'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo ($product['negotiable'] == 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description"
            required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <button type="submit">Update Product</button>
    </form>
</body>

</html>