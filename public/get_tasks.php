<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

try {
    $tasks = Lazer::table('tasks')->findAll()->asArray();

    foreach ($tasks as &$task) {
        $statusMap = [
            'not done' => 'not_done',
            'doing' => 'doing',
            'done' => 'done'
        ];
        $task['status'] = $statusMap[trim($task['status'])] ?? 'not_done';
    }

    echo json_encode($tasks);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
