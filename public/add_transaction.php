<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

header('Content-Type: application/json');

// Ghi log khi có giao dịch
function logReplication($action, $table, $data) {
    $logFile = __DIR__ . '/../database/replication.log';
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action,
        'table' => $table,
        'data' => $data
    ];
    file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
}

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? trim($_POST['type']) : null;
    $amount = isset($_POST['amount']) ? trim($_POST['amount']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    // Kiểm tra dữ liệu đầu vào
    if (empty($type) || empty($amount) || empty($description)) {
        echo json_encode(["error" => "Dữ liệu gửi lên bị rỗng!"]);
        exit;
    }

    // Kiểm tra xem số tiền có phải là số hợp lệ không
    if (!is_numeric($amount) || $amount <= 0) {
        echo json_encode(["error" => "Số tiền không hợp lệ!", "input" => $amount]);
        exit;
    }

    try {
        // Thêm vào database
        $transaction = Lazer::table('finance');
        $transaction->type = $type;  // "Thu nhập" hoặc "Chi tiêu"
        $transaction->amount = floatval($amount);
        $transaction->description = $description;

        $transaction->save();

        // Ghi log giao dịch
        logReplication('create', 'finance', [
            'id' => $transaction->id,
            'type' => $type,
            'amount' => number_format($amount, 0, ',', '.') . ' VNĐ',
            'description' => $description
        ]);

        // Phản hồi JSON
        echo json_encode([
            "success" => true,
            "message" => "Giao dịch đã được thêm thành công!",
            "data" => [
                'id' => $transaction->id,
                'type' => $type,
                'amount' => number_format($amount, 0, ',', '.') . ' VNĐ',
                'description' => $description
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
