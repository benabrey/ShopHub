<?php
// Registration page

// Load database config, user controller and session helper
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/helpers/Session.php';

// Start or resume session so flash messages and session state can be used
Session::start();

// Create DB connection and user controller to handle registration logic
$db = getDatabaseConnection();
$userController = new UserController($db);

// If the form was submitted, attempt to register the user.
// Otherwise display the registration form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->register();
} else {
    //render the registration form
    $userController->showRegisterForm();
}


