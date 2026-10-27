<?php

use App\Controllers\Auth\AuthController;

require_once __DIR__ . '/../../Controllers/Auth/AuthController.php';

$authController = new AuthController();
$authController->logout();
