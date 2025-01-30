<?php
session_start();
include 'config.php';

// Check for search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Check for sorting options (sort by price)
$sort_order = isset($_GET['sort']) && ($_GET['sort'] === 'asc' || $_GET['sort'] === 'desc') ? $_GET['sort'] : 'asc'; // Default to ascending

// Modify query to include search and sorting options
$query = "SELECT products.*, users.email AS seller_email, users.username AS seller_name, users.mobile AS seller_mobile 
          FROM products 
          JOIN users ON products.seller_id = users.id 
          WHERE products.name LIKE '%$search%' ";

$query .= " ORDER BY products.price $sort_order"; // Sort by price

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error executing query: " . mysqli_error($conn)); // Log any database query errors
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Cart - Products</title>
    <link rel="stylesheet" href="Styles/product.css">
    <style>
        /* General Styling for Sort Bar and Search Bar */
        /* General Styling for Sort Bar and Search Bar */
        .sort-bar,
        .search-bar {
            width: 50%;
            max-width: 400px;
            margin: -1px auto;
            padding: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f7f7f7;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Styling for the search input */
        .search-bar input[type="text"] {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-bar input[type="text"]:focus {
            border-color: #007bff;
        }

        /* Styling for the search button */
        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* Sort Bar - select dropdown */
        .sort-bar select {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .sort-bar select:focus {
            border-color: #007bff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .sort-bar,
            .search-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar input[type="text"] {
                width: 50%;
                margin-bottom: 8px;
            }

            .search-bar button {
                width: 100%;
            }

            .sort-bar select {
                width: 50%;
                margin-top: 8px;
            }
        }
    </style>
</head>

<body>
    <!-- Search Bar -->
    <div class="search-bar">
        <form action="products.php" method="GET">
            <input type="text" name="search" placeholder="Search for products..."
                value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Sort Bar -->
    <div class="sort-bar">
        <form action="products.php" method="GET">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <select name="sort" onchange="this.form.submit()">
                <option value="asc" <?php echo $sort_order == 'asc' ? 'selected' : ''; ?>>Sort by Price: Low to High
                </option>
                <option value="desc" <?php echo $sort_order == 'desc' ? 'selected' : ''; ?>>Sort by Price: High to Low
                </option>
            </select>
        </form>
    </div>

    <!-- Products Section -->
    <section class="products-section">
        <h2>Available Products</h2>
        <div class="product-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-item">
                        <?php
                        // Get the first image only
                        $image = trim(explode(',', $row['image'])[0]);
                        $imagePath = 'uploads/' . $image;
                        ?>
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        </a>
                        <p><strong>Price: &#8377;</strong> <?php echo htmlspecialchars($row['price']); ?></p>
                        <p><strong>Description:</strong>
                            <?php echo htmlspecialchars(str_replace('<br />', '.', $row['description'])); ?></p>
                        <p><strong>Condition:</strong> <?php echo htmlspecialchars($row['Item_Condition']); ?></p>
                        <p><strong>Negotiable:</strong> <?php echo htmlspecialchars($row['negotiable']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No products found for "<?php echo htmlspecialchars($search); ?>"</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="back-button-container">
            <button class="back-button" onclick="goBack()">Back</button>
        </div>
    </section>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>