<?php
// Login page

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/helpers/Session.php';

Session::start();

//creating connection and user controller to handle authentication
$db = getDatabaseConnection();
$userController = new UserController($db);

// If the form was submitted, attempt to log the user in.
// Otherwise display the login form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->login();
} else {
    $userController->showLoginForm();
}

