<?php
session_start();
include 'config.php';

// Check if the user is logged in and whether they are a seller
$is_seller = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $seller_check_query = "SELECT is_seller FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $seller_check_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $seller_result = mysqli_stmt_get_result($stmt);
    if ($seller_row = mysqli_fetch_assoc($seller_result)) {
        $is_seller = (bool) $seller_row['is_seller'];
    }
    mysqli_stmt_close($stmt);
}

// Fetch dynamic stats
$active_users_query = "SELECT COUNT(*) as active_users FROM users WHERE is_active = 1";
$active_users_result = mysqli_query($conn, $active_users_query);
$active_users = mysqli_fetch_assoc($active_users_result)['active_users'];

$items_listed_query = "SELECT COUNT(*) as items_listed FROM products";
$items_listed_result = mysqli_query($conn, $items_listed_query);
$items_listed = mysqli_fetch_assoc($items_listed_result)['items_listed'];

$recent_product_query = "SELECT name, created_at FROM products ORDER BY created_at DESC LIMIT 1";
$recent_product_result = mysqli_query($conn, $recent_product_query);

if ($recent_product = mysqli_fetch_assoc($recent_product_result)) {
    $recent_product_name = $recent_product['name'];
    $recent_product_time = $recent_product['created_at'];
} else {
    $recent_product_name = "No recent product";
    $recent_product_time = "";
}

function time_ago($datetime)
{
    $time = strtotime($datetime);
    $time_difference = time() - $time;
    if ($time_difference < 1)
        return 'just now';
    $condition = array(
        12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Cart</title>
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/index2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="chatbot.js" rel="chatbot"></script>
    <style>
        /* Container for both sections */
        .dual-section-container {
            display: flex;
            gap: 20px;
            /* Space between sections */
            justify-content: space-between;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        /* Individual section styling */
        .messaging-section,
        .share-items {
            flex: 1;
            /* Equal width for both sections */
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Section titles */
        .section-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        /* Button styling for "Open Chat" and "Share" */
        .btn-chat,
        .share-items button {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
        }

        .btn-chat:hover,
        .share-items button:hover {
            background-color: #0056b3;
        }

        /* Icon styling */
        .btn-chat i {
            margin-right: 8px;
        }
    </style>
    <script>
        // Function to toggle the visibility of the profile dropdown menu
        function toggleMenu() {
            const menu = document.getElementById('profileDropdown');
            // Toggle the visibility
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    </script>
    <style>
        .logo img {
            width: 90px;
            height: 90px;
            margin-right: 8px;
            border-radius: 100px;
        }
    </style>
</head>

<body>
    <!-- Navbar with logo -->
    <header>
        <div class="navbar">
            <div class="logo-container">
                <a href="index.php" class="logo">
                    <img src="images/Weblogo.png" alt="Campus Cart Logo" class="uni-logo">
                </a>
                <h1>PU_CART</h1>
            </div>

            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="products.php"><i class="fas fa-box-open"></i> Items</a></li>

                    <?php if ($is_seller): ?>
                        <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Add Items</a></li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="become_seller.php"><i class="fas fa-store"></i> Sell</a></li>
                        <!-- Profile Dropdown -->
                        <li class="profile-icon">
                            <i class="fas fa-user-circle"></i>
                            <span class="username">
                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo htmlspecialchars($_SESSION['username']);
                                } else {
                                    echo "Guest"; // If not logged in
                                }
                                ?>
                            </span>

                            <!-- Menu Button to Toggle Dropdown -->
                            <!-- Menu Button -->
                            <!-- Three-Lined Menu Button -->

                            <button class="menu-button" onclick="toggleMenu()">&#9776;</button>





                            <!-- Profile Dropdown Menu -->
                            <ul class="profile-dropdown" id="profileDropdown">
                                <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                                <li><a href="update_password.php"><i class="fas fa-lock"></i> Update Password</a></li>
                                <li><a href="settings.php"><i class="fas fa-cogs"></i> Account Settings</a></li>
                            </ul>
                        </li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login/Signup</a></li>
                    <?php endif; ?>
                    <li><a href="Quick_requirements.php"><i class="fas fa-bolt"></i> Quick Needs</a></li>

                </ul>
            </nav>
        </div>
    </header>

    <!-- Campus-Wide Announcements -->

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <?php if (!isset($_SESSION['user_id'])): ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="featured-categories">
        <h2 class="section-title">Popular Categories</h2>
        <div class="category-grid">
            <!-- Category Cards -->
            <div class="category-card"><i class="fas fa-book category-icon"></i>
                <h3>Textbooks</h3>
            </div>
            <div class="category-card"><i class="fas fa-laptop category-icon"></i>
                <h3>Electronics</h3>
            </div>
            <div class="category-card"><i class="fas fa-chair category-icon"></i>
                <h3>Furniture</h3>
            </div>
            <div class="category-card"><i class="fas fa-flask category-icon"></i>
                <h3>Lab Equipment</h3>
            </div>
            <div class="category-card"><i class="fas fa-tshirt category-icon"></i>
                <h3>Clothing</h3>
            </div>
            <div class="category-card"><i class="fas fa-basketball-ball category-icon"></i>
                <h3>Sports Gear</h3>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $active_users; ?></div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $items_listed; ?></div>
                <div class="stat-label">Items Listed</div>
            </div>
        </div>
    </section>

    <!-- Recent Activity Section -->
    <section class="recent-activity">
        <h2 class="section-title">Recent Activity</h2>
        <div class="activity-list">
            <div class="activity-item">
                <i class="fas fa-check-circle activity-icon"></i>
                <div>
                    <strong>New Listing</strong>
                    <p><?php echo htmlspecialchars($recent_product_name); ?> listed
                        <?php echo time_ago($recent_product_time); ?> ago
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Messaging and Chat Section -->
    <div class="dual-section-container">
        <!-- Instant Messaging Section -->
        <section class="messaging-section">
            <h2 class="section-title">Instant Messaging</h2>
            <p>Chat with sellers directly for quicker responses and better deals.</p>
            <a href="chat.php" class="btn-chat"><i class="fas fa-comments"></i> Open Chat</a>
        </section>

        <!-- Share with Friends Section -->
        <section class="share-items">
            <h2 class="section-title">Share with Friends</h2>
            <p>Find something interesting? Share it with friends on campus!</p>
            <button onclick="shareItem()">Share this page</button>
        </section>
    </div>


    <!-- How It Works Section -->
    <section class="how-it-works">
        <h2 class="section-title">How It Works</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Create Account</h3>
                    <p>Sign up with your university email</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>List or Browse</h3>
                    <p>Post items for sale or browse listings</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Connect</h3>
                    <p>Message sellers and arrange meetups</p>
                </div>
            </div>
        </div>
    </section>


    <!-- //chatbot -->
    <div class="chatbot-container">
        <!-- Chatbot icon/button -->
        <button class="chatbot-icon" onclick="toggleChatWindow()">
            <i class="fas fa-robot"></i>
        </button>
        <!-- Chatbot window -->
        <div class="chat-window" id="chatWindow">
            <h3>Campus Cart Assistant</h3>

            <!-- Predefined questions -->

            <!-- Messages container -->
            <div class="messages" id="messagesContainer"></div>

            <!-- Chat form -->
            <form class="chat-form" onsubmit="event.preventDefault(); sendMessage();">
                <input type="text" id="chatInput" placeholder="Type your message here..." required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>





    <!-- Footer -->
    <footer class="enhanced-footer">
        <div class="footer-grid">
            <div class="footer-section">
                <h3>About PU Cart</h3>
                <ul class="footer-links">
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="how_it_works.html">How It Works</a></li>
                    <li><a href="terms.html">Terms of Service</a></li>
                    <li><a href="privacy.html">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>For Users</h3>
                <ul class="footer-links">
                    <li><a href="register.php">Create Account</a></li>
                    <!-- <li><a href="#">Become a Seller</a></li> -->
                    <li><a href="safety_tips.html">Safety Tips</a></li>
                    <!-- <li><a href="#">FAQs</a></li> -->
                </ul>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="help_center.php">Help Center</a></li>
                    <li><a href="report_issue.php">Report an Issue</a></li>
                    <!-- <li><a href="feedback.php">Feedback</a></li> -->
                </ul>
            </div>

            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com" target="_blank"><img src="images/facebook_logo.jpeg"
                            alt="Facebook"></a>
                    <a href="https://www.twitter.com" target="_blank"><img src="images/twitter_logo.jpeg"
                            alt="Twitter"></a>
                    <a href="https://www.instagram.com" target="_blank"><img src="images/instagram_logo.jpeg"
                            alt="Instagram"></a>
                </div>
            </div>
        </div>
        </div>
    </footer>
    <footer class="footer">
        <p style="text-align: center;">&copy; <?php echo date("Y"); ?> Campus Cart. All rights reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function shareItem() {
            if (navigator.share) {
                navigator.share({
                    title: 'Campus Cart',
                    url: window.location.href
                }).catch(console.error);
            } else {
                alert("Sharing not supported on this browser.");
            }
        }
    </script>
</body>

</html>