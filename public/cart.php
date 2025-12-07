<?php
// Shopping cart page

 require_once __DIR__ . '/../config/database.php';
 require_once __DIR__ . '/../src/controllers/CartController.php';
 require_once __DIR__ . '/../src/helpers/Session.php';
 require_once __DIR__ . '/../src/helpers/Auth.php';

//Start or resume session so cart data can be saved
 Session::start();

 //handle form submissions (add/update/remove/clear)
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $db = getDatabaseConnection();
     $cartController = new CartController($db);

     //Which cart action was requested
     $action = $_POST['action'] ?? '';

     switch($action) {
         case 'add':
             $cartController->addToCart();
             break;
         case 'update':
             $cartController->updateQuantity();
             break;
         case 'remove':
             $cartController->removeItem();
             break;
         case 'clear':
             $cartController->clearCart();
             break;
     }
 }

// For GET requests (or after handling POST) build and display the cart page
 $db = getDatabaseConnection();
 $cartController = new CartController($db);
 $cartController->index();
