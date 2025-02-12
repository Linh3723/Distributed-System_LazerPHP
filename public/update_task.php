<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $taskName = $_POST['task'] ?? null;
    $deadline = $_POST['deadline'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$id) {
        echo json_encode(["error" => "Thiếu ID"]);
        exit;
    }

    try {
        $task = Lazer::table('tasks')->find($id);

        if (isset($_POST['save']) && $_POST['save'] === "true") {
            if ($taskName) $task->task = $taskName;
            if ($deadline) $task->deadline = $deadline;
            
            $valid_statuses = ["doing", "not_done", "done"];
            if ($status && in_array($status, $valid_statuses)) {
                $task->status = $status;
            }

            $task->save();
            echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
        }

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
