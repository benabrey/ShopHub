<?php
// Single product detail page

// Load database config, product controller and session helper
 require_once __DIR__ . '/../config/database.php';
 require_once __DIR__ . '/../src/controllers/ProductController.php';
 require_once __DIR__ . '/../src/helpers/Session.php';

// Start or resume the session so flash messages or user data are available
 Session::start();

// Create a DB connection and product controller to handle product lookup
 $db = getDatabaseConnection();
 $productController = new ProductController($db);
// Get product id from query string
 $productId = $_GET['id'] ?? null;

// Validate the product id: if missing or not numeric, set an error and redirect
if (!$productId || !is_numeric($productId)) {
     Session::flash('error', 'Invalid product ID');
     header('Location: /Ecommerce_final_project/public/products.php');
     exit();
 }

 $pageTitle = "Product Details - ShopHub";

// Delegate to the controller to fetch product data and render the page
 $productController->show($productId);

