<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with the current URL as a redirect parameter
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Get the product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details and seller info
$query = "SELECT products.*, users.email AS seller_email, users.username AS seller_name, users.mobile AS seller_mobile 
          FROM products 
          JOIN users ON products.seller_id = users.id 
          WHERE products.id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Campus Cart</title>
    <link rel="stylesheet" href="Styles/product_detail.css">
</head>

<body>

    <!-- Product Details -->
    <div class="product-detail">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>

        <!-- Sliding Product Images -->
        <div class="image-slider">
            <?php
            // Assuming images are stored as a comma-separated string in the 'image' column
            $images = explode(',', $product['image']);
            ?>
            <div class="slides">
                <?php foreach ($images as $image): ?>
                    <div class="slide">
                        <img src="uploads/<?php echo htmlspecialchars(trim($image)); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image"
                            onclick="openModal(this.src)">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="prev" onclick="changeSlide(-1)">❮</button>
            <button class="next" onclick="changeSlide(1)">❯</button>
        </div>

        <p><strong>Price:</strong> <?php echo htmlspecialchars($product['price']); ?></p>
        <p><strong>Description:</strong></p>
        <?php
        // Replace <br /> tags with actual line breaks in HTML
        $description = str_replace('<br />', '.', $product['description']);
        echo nl2br(htmlspecialchars($description));
        ?>

        <p><strong>Condition:</strong> <?php echo htmlspecialchars($product['Item_Condition']); ?></p>
        <p><strong>Negotiable:</strong> <?php echo htmlspecialchars($product['negotiable']); ?></p>



        <h3>Seller Information</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
        <p><strong>Email:</strong> <a
                href="mailto:<?php echo htmlspecialchars($product['seller_email']); ?>"><?php echo htmlspecialchars($product['seller_email']); ?></a>
        </p>
        <p><strong>Mobile:</strong>
            <a href="tel:<?php echo htmlspecialchars($product['seller_mobile']); ?>">Call Seller</a> |
            <a href="sms:<?php echo htmlspecialchars($product['seller_mobile']); ?>">Message Seller</a>
        </p>
    </div>

    <!-- Modal for Image Viewing -->
    <div class="modal" id="imageModal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage" />
    </div>

    <script>
        let currentSlide = 0;

        function showSlides() {
            const slides = document.querySelectorAll('.slide');
            slides.forEach((slide, index) => {
                slide.style.display = index === currentSlide ? 'block' : 'none';
            });
        }

        function changeSlide(direction) {
            const slides = document.querySelectorAll('.slide');
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            showSlides();
        }

        // Initialize the slider
        showSlides();

        // Open modal for image viewing
        function openModal(imageSrc) {
            const modal = document.getElementById("imageModal");
            const modalImage = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImage.src = imageSrc;
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>
</body>

</html>