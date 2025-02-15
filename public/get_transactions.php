<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

try {
    $transactions = Lazer::table('transactions')->findAll()->asArray();
    echo json_encode($transactions);
} catch (Exception $e) {
    echo json_encode(["error" => "Lỗi khi lấy dữ liệu!"]);
}
?>
