<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $task = trim($_POST['task']);

    if (!empty($task)) {
        $taskRow = Lazer::table('tasks')->find($id);
        $taskRow->task = $task;
        $taskRow->save();
    }
}

header("Location: dashboard.php");
exit;
?>
