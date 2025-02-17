<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

function logReplication($action, $table, $data) {
    $logFile = __DIR__ . '/../database/replication.log';
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action, // "create", "update", "delete"
        'table' => $table,
        'data' => $data
    ];
    file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    try {
        // Lấy thông tin task trước khi xóa
        $task = Lazer::table('tasks')->find($id);
        $taskData = [
            'id' => $task->id,
            'name' => $task->name ?? 'Unknown',
            'description' => $task->description ?? '',
            'status' => $task->status ?? 'Unknown'
        ];

        // Xóa task
        $task->delete();

        // Ghi log khi xóa task
        logReplication('delete', 'tasks', $taskData);
    } catch (Exception $e) {
        error_log("Lỗi khi xóa task: " . $e->getMessage());
    }
}

header("Location: dashboard.php");
exit;
?>
