<?php
use Lazer\Classes\Database as Lazer;
use Lazer\Classes\Database as Capsule;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');
require_once __DIR__ . '/../vendor/autoload.php';

try {
    Capsule::table('transactions', [
        'id' => 'integer',  // ID tự tăng
        'type' => 'string', // "Thu nhập" hoặc "Chi tiêu"
        'amount' => 'float', // Số tiền
        'description' => 'string', // Mô tả giao dịch
        'created_at' => 'string' // Ngày tạo
    ]);

    echo "Bảng 'transactions' đã được tạo thành công!";
} catch (Exception $e) {
    echo "Lỗi tạo bảng: " . $e->getMessage();
}

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

