<?php
$logFile = __DIR__ . '/../database/replication.log';

// Trả về log để các Slave đồng bộ
if (file_exists($logFile)) {
    header('Content-Type: application/json');
    echo json_encode(file($logFile, FILE_IGNORE_NEW_LINES));
} else {
    echo json_encode([]);
}
?>
