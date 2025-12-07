<?php
// Checkout page

 require_once __DIR__ . '/../config/database.php';
 require_once __DIR__ . '/../src/controllers/CartController.php';
 require_once __DIR__ . '/../src/helpers/Session.php';
 require_once __DIR__ . '/../src/helpers/Auth.php';

 //Start/resume session so user and cart data are available
 Session::start();

 //Require authentication from user
 Auth::requireAuth();

 //Create DB connection and controller to handle cart/checkout functinoality
 $db = getDatabaseConnection();
 $cartController = new CartController($db);

// If the form was submitted, process the checkout; otherwise show the form/page
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $cartController->processCheckout();
 } else {
     $cartController->showCheckout();
 }

//Layout views
require_once __DIR__ . '/../src/views/header.php';
require_once __DIR__ . '/../src/views/navbar.php';
require_once __DIR__ . '/../src/views/home.php';
require_once __DIR__ . '/../src/views/footer.php';