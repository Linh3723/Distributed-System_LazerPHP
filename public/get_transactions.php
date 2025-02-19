<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

try {
    $transactions = Lazer::table('finance')->findAll();
    $income = [];
    $expense = [];

    foreach ($transactions as $transaction) {
        $data = [
            'type' => $transaction->type,
            'amount' => number_format($transaction->amount, 0, ',', '.') . ' VNĐ', 
            'description' => $transaction->description
        ];

        if ($transaction->type == "Thu nhập") {
            $income[] = $data;
        } else {
            $expense[] = $data;
        }
    }

    echo json_encode(["success" => true, "income" => $income, "expense" => $expense]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
