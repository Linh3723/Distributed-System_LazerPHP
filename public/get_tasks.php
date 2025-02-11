<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

try {
    $tasks = Lazer::table('tasks')->findAll()->asArray();  

    if (empty($tasks)) {
        echo json_encode([]);  // Trả về mảng rỗng nếu không có công việc
    } else {
        echo json_encode($tasks);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);  // In lỗi ra JSON
}
?>
