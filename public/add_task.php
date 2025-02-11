<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;

// Hiển thị lỗi chi tiết
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = trim($_POST['task']);

    if (!empty($task)) {
        try {
            // Kiểm tra xem bảng "tasks" có tồn tại không
            if (!Lazer::table('tasks')) {
                die("⚠️ Bảng 'tasks' chưa tồn tại!");
            }

            // Thêm công việc
            Lazer::table('tasks')->insert([
                'task' => $task,
                'created_at' => date('Y-m-d H:i:s')
            ])->save();

            echo "✅ Thêm công việc thành công!";
            exit; // Thoát để tránh redirect
        } catch (Exception $e) {
            die("❌ Lỗi: " . $e->getMessage());
        }
    } else {
        die("⚠️ Vui lòng nhập tên công việc!");
    }
}

header("Location: dashboard.php");
exit;
