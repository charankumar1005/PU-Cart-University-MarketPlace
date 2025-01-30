<?php
// reply.php
header("Content-Type: application/json");

// Simulate a database of replies or use NLP for more advanced replies
$replies = [
    "hello" => "Hi there! How can I help you?",
    "hi" => "Hello! How can I assist you today?",

    // Registration & Login
    "register" => "To create an account, click on 'Sign Up' and fill in the required fields. Once registered, you can log in to access more features.",
    "log in" => "Click on 'Login' at the top of the page and enter your credentials to access your account.",
    "forgot password" => "Click on 'Forgot Password' on the login page to reset your password via email.",

    // Adding & Managing Products
    "add product" => "To add a product, go to your dashboard, select 'Add Product,' and fill in the product details such as name, description, price, and upload images.",
    "edit product" => "Go to 'My Products' in your dashboard, find the product you want to edit, and select 'Edit.' Make your changes and save.",
    "delete product" => "In 'My Products,' find the product you wish to delete, click 'Delete,' and confirm the deletion.",

    // Messaging & Chat
    "message seller" => "To message a seller, click on 'Chat' on the product page, select the seller, and start a conversation.",
    "messaging" => "In Campus Cart, you can chat directly with sellers to ask about products. Go to the 'Chat' section, select a user, and send a message!",

    // About the Platform
    "campus cart" => "Campus Cart is a marketplace platform for university students to buy, sell, and exchange items within the campus community.",
    "who can use" => "Campus Cart is intended for university students to create a safe and easy-to-use platform for buying and selling products on campus.",
    "search products" => "Use the search bar on the homepage or browse categories to find products available for sale.",
    "product categories" => "On the homepage, you'll find categories such as electronics, books, and more to easily browse through items.",

    // Student Support & Common Questions
    "contact support" => "If you need help, go to the 'Support' section on our website or reach out through our contact form.",
    "report problem" => "To report an issue with a product or user, go to the 'Report' option on the respective page, fill out the form, and submit.",
    "student deals" => "Campus Cart frequently features popular deals and categories. Check the homepage for the best deals on campus!",

    // General Campus-Related Queries
    "library" => "For most campuses, the library is located centrally. Please check your campus map for the exact location.",
    "study resources" => "Many students post study resources in the Books section on Campus Cart. You can also check the campus library or online databases.",
    "second-hand books" => "Visit the Books category on Campus Cart to find second-hand books posted by other students.",
    "save on textbooks" => "Consider buying second-hand textbooks from other students or browsing the Books section on Campus Cart for cheaper options.",

    // Additional FAQs
    "outside campus" => "Campus Cart is intended for use within your campus community to maintain safety and convenience.",
    "safe exchange" => "Arrange to meet in public spaces on campus and ensure both parties confirm the details before exchanging items.",
    "delete account" => "If you wish to delete your account, go to your account settings, and select 'Delete Account.' This action is permanent.",
    "update profile" => "Go to 'Profile Settings' on your dashboard to update your personal details like email, phone number, or profile picture.",
    "Bye" => "Bye Take care.",
    // Default Reply
    "default" => "I'm sorry, I didn't understand that. Can you please rephrase or ask something specific about Campus Cart?"
];

// Get the user's message from the AJAX request
$message = strtolower(trim($_POST['message'] ?? ''));

// Look for partial matches in the user message
$response = $replies["default"];
foreach ($replies as $keyword => $reply) {
    if (strpos($message, $keyword) !== false) {
        $response = $reply;
        break;
    }
}

// Return the response as JSON
echo json_encode(["reply" => $response]);
?>