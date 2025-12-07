<?php
// Order confirmation page - displays the details for a completed order

// Load database config, controller, and helper classes

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/CartController.php';
require_once __DIR__ . '/../src/helpers/Session.php';
require_once __DIR__ . '/../src/helpers/Auth.php';

// Require user to be logged in to view order confirmation
Session::start();
Auth::requireAuth();

// Create DB connection and controller used to fetch order details
$db = getDatabaseConnection();
$cartController = new CartController($db);

// Get the order id from the query string and validate it
$orderId = $_GET['id'] ?? null;
if (!$orderId || !is_numeric($orderId)) {
    // If the id is missing or invalid, redirect back to the homepage
    header('Location: /Ecommerce_final_project/public/index.php');
    exit();
}
// Delegate to the controller to render the order confirmation page
$cartController->showOrderConfirmation($orderId);

