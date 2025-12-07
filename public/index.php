<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Product.php';
require_once __DIR__ . '/../src/helpers/Session.php';
require_once __DIR__ . '/../src/helpers/Auth.php';


// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    Auth::logout();
    exit();
}

// Get database connection
$db = getDatabaseConnection();

// Create Product model with DB connection
$productModel = new Product($db);

// Fetch featured products
$featuredProducts = $productModel->getFeatured(8);

//Get all categories for Navigation
$categories = $productModel->getAllCategories();

// Set page title for header
$pageTitle = "Home - ShopHub";
$pageDescription = "Welcome to ShopHub, your one-stop store for amazing products.";

require_once __DIR__ . '/../src/views/header.php';
require_once __DIR__ . '/../src/views/navbar.php';
require_once __DIR__ . '/../src/views/home.php';
require_once __DIR__ . '/../src/views/footer.php';
