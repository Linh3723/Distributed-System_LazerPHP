<?php
require __DIR__ . '/../vendor/autoload.php';

if (!defined('LAZER_DATA_PATH')) {
    define('LAZER_DATA_PATH', __DIR__ . '/../database/');
}

use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = isset($_POST['task']) ? trim($_POST['task']) : null;
    $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : null;

    if (!$task || !$deadline) {
        echo json_encode(["error" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    try {
        Lazer::table('tasks')->insert([
            'task' => $task,
            'deadline' => $deadline,
            'status' => 'not_done',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode([
            "success" => "Thêm công việc thành công!",
            "task" => $task,
            "deadline" => $deadline
        ]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Lỗi: " . $e->getMessage()]);
    }
}
?>
