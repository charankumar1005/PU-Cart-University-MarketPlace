<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=index.php");
    exit();
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = nl2br(htmlspecialchars($_POST['product_description']));
    $condition = $_POST['Item_Condition'];
    $negotiable = $_POST['negotiable']; // New negotiable field
    $category = $_POST['category']; // New category field
    // $urgency = $_POST['urgency']; // New urgency field

    // Handle the product image uploads
    $uploadedImages = [];
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    $maxImages = 10;

    for ($i = 0; $i < $maxImages; $i++) {
        if (isset($_FILES['product_images']['error'][$i]) && $_FILES['product_images']['error'][$i] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['product_images']['tmp_name'][$i];
            $imageName = $_FILES['product_images']['name'][$i];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if (in_array($imageExt, $allowedExts)) {
                $newImageName = uniqid() . '.' . $imageExt;
                $uploadDir = 'uploads/';
                $destPath = $uploadDir . $newImageName;

                if (move_uploaded_file($imageTmpPath, $destPath)) {
                    $uploadedImages[] = $newImageName;
                } else {
                    echo "Error moving file: " . $_FILES['product_images']['name'][$i];
                }
            } else {
                echo "Only JPG, JPEG, PNG, and GIF files are allowed for: " . $_FILES['product_images']['name'][$i];
            }
        }
    }

    if (!empty($uploadedImages)) {
        $imagesString = implode(',', $uploadedImages);

        // Updated SQL query to include urgency, condition, negotiable fields, and category
        $insert = "INSERT INTO products (seller_id, name, price, description, image, Item_Condition, negotiable, category) VALUES 
                  ('" . $_SESSION['user_id'] . "', '$product_name', '$product_price', '$product_description', '$imagesString', '$condition', '$negotiable', '$category')";

        if (mysqli_query($conn, $insert)) {
            $_SESSION['success_message'] = "Product added successfully!";
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "No images were uploaded.";
    }
}

// Function to delete a product
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $checkProductQuery = "SELECT * FROM products WHERE id = $product_id AND seller_id = " . $_SESSION['user_id'];
    $productCheckResult = mysqli_query($conn, $checkProductQuery);

    if (mysqli_num_rows($productCheckResult) > 0) {
        $deleteQuery = "DELETE FROM products WHERE id = $product_id";
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success_message'] = "Product deleted successfully!";
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting product: " . mysqli_error($conn);
        }
    } else {
        echo "You do not have permission to delete this product.";
    }
}

// Function to get user's products for display
$user_id = $_SESSION['user_id'];
$productsQuery = "SELECT * FROM products WHERE seller_id = $user_id";
$productsResult = mysqli_query($conn, $productsQuery);

// Define categories
$categories = [
    'Textbooks',
    'Electronics',
    'Stationery',
    'Clothing',
    'Sports Equipment',
    'Furniture',
    'Bikes',
    'Kitchen Appliances',
    'Personal Care',
    'Toys and Games'
];

// Define urgency levels
// $urgency_levels = ['Urgent', 'Normal', 'Not Urgent'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Products - Campus Cart</title>
    <link rel="stylesheet" href="Styles/become_seller.css">
    <script>
        function updateCharCount() {
            const description = document.getElementById('product_description');
            const charCount = document.getElementById('char_count');
            const maxChars = 300;

            charCount.textContent = `${description.value.length}/${maxChars} characters`;
        }
    </script>
</head>

<body>
    <div class="seller-container">
        <h2>ADD ITEMS</h2>
        <form action="become_seller.php" method="POST" enctype="multipart/form-data">
            <label for="product_name">Item Name:</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="product_price">Item Price:</label>
            <input type="number" name="product_price" id="product_price" required>

            <label for="product_description">Item Description:</label>
            <textarea name="product_description" id="product_description" maxlength="300" required
                oninput="updateCharCount()"></textarea>
            <p id="char_count">0/300 characters</p>

            <label for="product_images">Item Images:</label>
            <input type="file" name="product_images[]" id="product_images" multiple required>

            <label for="condition">Condition:</label>
            <select name="Item_Condition" id="condition">
                <option value="New">New</option>
                <option value="Used">Already Used</option>
                <option value="Like New">Like New</option> <!-- Added additional condition option -->
                <option value="Refurbished">Refurbished</option>
                <option value="Damaged">Damaged</option><!-- Added additional condition option -->
            </select>

            <label for="negotiable">Negotiable:</label>
            <select name="negotiable" id="negotiable">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <!-- <label for="urgency">Urgency Level:</label> -->
            <!-- <select name="urgency" id="urgency" required>
                <?php foreach ($urgency_levels as $urgency): ?>
                    <option value="<?php echo $urgency; ?>"><?php echo $urgency; ?></option>
                <?php endforeach; ?>
            </select> -->

            <button type="submit">Add Items</button>
        </form>

        <h2>Your Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Condition</th>
                    <th>Negotiable</th>
                    <th>Category</th> <!-- New category column -->
                    <!-- <th>Urgency</th> New urgency column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($productsResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>&#8377;<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['Item_Condition']); ?></td>
                        <td><?php echo htmlspecialchars($product['negotiable']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td> <!-- Display category -->
                        <!-- <td><?php echo htmlspecialchars($product['urgency']); ?></td> Display urgency -->
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                            <a href="?action=delete&id=<?php echo $product['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>