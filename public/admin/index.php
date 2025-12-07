<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/controllers/AdminController.php';
require_once __DIR__ . '/../../src/helpers/Session.php';

Session::start();

$db = getDatabaseConnection();
$adminController = new AdminController($db);

$adminController->dashboard();