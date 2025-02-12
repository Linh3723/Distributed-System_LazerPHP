<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ frontend
    $taskName = isset($_POST['task']) ? trim($_POST['task']) : null;
    $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : null;

    // Kiểm tra nếu giá trị null
    if (empty($taskName) || empty($deadline)) {
        echo json_encode(["error" => "Dữ liệu gửi lên bị rỗng!", "task" => $taskName, "deadline" => $deadline]);
        exit;
    }

    // Chuyển đổi định dạng ngày tháng nếu có
    $date = DateTime::createFromFormat('Y-m-d', $deadline);
    if (!$date) {
        echo json_encode(["error" => "Định dạng ngày không hợp lệ!", "input" => $deadline]);
        exit;
    }
    $formattedDeadline = $date->format('Y-m-d');

    try {
        // Tạo bản ghi mới
        $task = Lazer::table('tasks');
        $task->task = $taskName;
        $task->deadline = $formattedDeadline;
        $task->status = 'not_done';

        // Lưu bản ghi
        $task->save();
        if (empty($taskName) || empty($formattedDeadline)) {
            echo json_encode(["error" => "Dữ liệu không hợp lệ"]);
            exit;
        }
        


        echo json_encode(["success" => true, "task" => $taskName, "deadline" => $formattedDeadline]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
