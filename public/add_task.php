<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

function logReplication($action, $table, $data) {
    $logFile = __DIR__ . '/../database/replication.log';
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action, // create, update, delete
        'table' => $table,
        'data' => $data
    ];
    file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = isset($_POST['task']) ? trim($_POST['task']) : null;
    $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : null;

    if (empty($taskName) || empty($deadline)) {
        echo json_encode(["error" => "Dữ liệu gửi lên bị rỗng!", "task" => $taskName, "deadline" => $deadline]);
        exit;
    }

    $date = DateTime::createFromFormat('Y-m-d', $deadline);
    if (!$date) {
        echo json_encode(["error" => "Định dạng ngày không hợp lệ!", "input" => $deadline]);
        exit;
    }
    $formattedDeadline = $date->format('Y-m-d');

    try {
        $task = Lazer::table('tasks');
        $task->task = $taskName;
        $task->deadline = $formattedDeadline;
        $task->status = 'not_done';

        $task->save();

        logReplication('create', 'tasks', [
            'id' => $task->id,
            'task' => $taskName,
            'deadline' => $formattedDeadline,
            'status' => 'not_done'
        ]);

        echo json_encode(["success" => true, "task" => $taskName, "deadline" => $formattedDeadline]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>