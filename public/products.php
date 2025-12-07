<?php
// Products listing page

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/ProductController.php';
require_once __DIR__ . '/../src/helpers/Session.php';

// Start or resume the session so flash messages or user data are available
Session::start();

// Create a DB connection and product controller to fetch products
$db = getDatabaseConnection();
$productController = new ProductController($db);

// Set page title
$pageTitle = "Products - ShopHub";

// Show products listing
$productController->index();

