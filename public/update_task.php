<?php
require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$id || !$status) {
        echo json_encode(["error" => "Thiếu dữ liệu!"]);
        exit;
    }

    try {
        $task = Lazer::table('tasks')->find($id);
        $task->status = $status; // Cập nhật trạng thái
        $task->save();
        echo json_encode(["success" => "Cập nhật thành công"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Lỗi: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Phương thức không hợp lệ!"]);
}
?>
