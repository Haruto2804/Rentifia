<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getConnection();
    $version = $db->query('SELECT VERSION()')->fetchColumn();
    echo "SUCCESS: MySQL connected successfully! Version: " . $version . PHP_EOL;
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
