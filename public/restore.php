<?php
require_once __DIR__ . '/../vendor/greg0/lazer-database/src/Classes/Database.php';

use Lazer\Classes\Database;

header('Content-Type: text/plain');

if (!isset($_POST['timestamp'])) {
    echo "Thiếu timestamp để restore.";
    exit;
}

try {
    echo Database::restore($_POST['timestamp']);
} catch (Exception $e) {
    echo "Lỗi khi restore: " . $e->getMessage();
}