<?php
$masterUrl = 'http://master-ip/sync.php';
$logFile = __DIR__ . '/../database/replication.log';

// Lấy log từ Master
$logData = file_get_contents($masterUrl);
$logEntries = json_decode($logData, true);

// Áp dụng các thay đổi từ log
foreach ($logEntries as $logEntry) {
    $log = json_decode($logEntry, true);
    
    if ($log['action'] === 'create') {
        file_put_contents(__DIR__ . '/../database/' . $log['table'] . '.data.json', json_encode($log['data']) . PHP_EOL, FILE_APPEND);
    } elseif ($log['action'] === 'update') {
        // Cập nhật logic update (tùy theo dữ liệu)
    } elseif ($log['action'] === 'delete') {
        // Xóa dữ liệu từ file JSON
    }
}

// Xóa log sau khi đồng bộ
file_put_contents($logFile, '');
?>
