<?php
use Lazer\Classes\Database as Lazer;
use Lazer\Classes\Capsule;

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

?>
