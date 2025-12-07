<?php
// User profile page

// Load database config, user controller and helper classes
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/helpers/Session.php';
require_once __DIR__ . '/../src/helpers/Auth.php';

// Start or resume session so user data and flash messages are available
Session::start();

// Create DB connection and controller for user operations
$db = getDatabaseConnection();
$userController = new UserController($db);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_profile':
            $userController->updateProfile();
            break;
        case 'change_password':
            $userController->changePassword();
            break;
        case 'delete_account':
            $userController->deleteAccount();
            break;
        default:
            //If action is unrecognized, redirect back to the profile page
            header('Location: /Ecommerce_final_project/public/profile.php');
            exit();
    }
} else {
    // For GET requests, show the profile page with current user data
    $userController->showProfile();
}
