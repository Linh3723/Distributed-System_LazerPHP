<?php
use Lazer\Classes\Database as Lazer;

require_once __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = trim($_GET['query']);
    
    try {
        $tasks = Lazer::table('tasks')->findAll();
        $results = [];
        
        foreach ($tasks as $task) {
            if (
                stripos($task->task, $query) !== false || 
                stripos($task->deadline, $query) !== false || 
                stripos($task->status, $query) !== false
            ) {
                $results[] = $task;
            }
        }
        
        echo json_encode(['success' => true, 'data' => $results]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
