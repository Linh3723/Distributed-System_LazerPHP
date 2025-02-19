<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

try {
    Lazer::create('finance', [
        'id' => 'integer',
        'type' => 'string',
        'amount' => 'double', 
        'description' => 'string',
        'date' => 'string'
    ]);
    echo "Bảng 'finance' đã được tạo thành công.";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
