<?php
require __DIR__ . '/../vendor/autoload.php';

if (!defined('LAZER_DATA_PATH')) {
    define('LAZER_DATA_PATH', __DIR__ . '/../database/');
}

use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

try {
    $tasks = Lazer::table('tasks')->findAll()->asArray();
    echo json_encode($tasks);
} catch (Exception $e) {
    echo json_encode(["error" => "Lỗi tải công việc: " . $e->getMessage()]);
}
?>
