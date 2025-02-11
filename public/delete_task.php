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
    Lazer::table('tasks')->find($id)->delete();
}

header("Location: dashboard.php");
exit;
?>
