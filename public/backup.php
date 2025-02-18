<?php
require_once __DIR__ . '/../vendor/greg0/lazer-database/src/Classes/Database.php';

use Lazer\Classes\Database;

header('Content-Type: text/plain');

try {
    echo Database::backup();
} catch (Exception $e) {
    echo "Lỗi khi backup: " . $e->getMessage();
} 