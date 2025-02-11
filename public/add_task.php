<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'] ?? '';
    $deadline = $_POST['deadline'] ?? '';

    if (empty($task) || empty($deadline)) {
        echo json_encode(["error" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    try {
        $task = new Lazer('tasks');
        $task->task = $taskName;
        $task->deadline = $deadline;
        $task->status = 'not done';
        $task->save();

        echo json_encode(["success" => "Thêm công việc thành công!", "task" => $task, "deadline" => $deadline]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Lỗi: " . $e->getMessage()]);
    }
}
?>
