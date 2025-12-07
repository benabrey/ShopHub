<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';
require_once __DIR__ . '/../../src/helpers/Session.php';

Session::start();

$db = getDatabaseConnection();
$adminController = new AdminController($db);

$productId = $_GET['id'] ?? null;

if (!$productId) {
    Session::flash('error', 'Product ID is required');
    header('Location: /Ecommerce_final_project/public/admin/products.php');
    exit();
}

$adminController->editProduct($productId);