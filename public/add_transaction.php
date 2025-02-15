<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $description = $_POST['description'];

    if (!$type || !$amount || !$description) {
        echo json_encode(["error" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    try {
        $transaction = Lazer::table('transactions');
        $transaction->type = $type;
        $transaction->amount = $amount;
        $transaction->description = $description;
        $transaction->save();
    
        echo json_encode(["success" => "Giao dịch đã được lưu!"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Lỗi khi lưu giao dịch: " . $e->getMessage()]);
    }
}
?>
