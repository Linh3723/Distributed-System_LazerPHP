<?php
header('Content-Type: application/json');

// Định nghĩa đường dẫn tệp JSON
define('TASKS_FILE', __DIR__ . '/../database/tasks.data.json');

// Kiểm tra nếu tệp tồn tại
if (!file_exists(TASKS_FILE)) {
    echo json_encode([]);
    exit;
}

// Đọc dữ liệu từ tệp
$data = file_get_contents(TASKS_FILE);
$tasks = json_decode($data, true);

// Trả về dữ liệu dưới dạng JSON
echo json_encode($tasks);
?>
