<?php

use Lazer\Classes\Database as Lazer;
use Lazer\Classes\Helpers\Validate;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');
require_once __DIR__ . '/../vendor/autoload.php';

// Tạo bảng users nếu chưa có
if (!file_exists(LAZER_DATA_PATH . 'users.json')) {
    Lazer::create('users', [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'created_at' => 'string'
    ]);
    echo "Table 'users' created successfully!<br>";
}

try {
    if (!Validate::table('tasks')->exists()) {
        Lazer::create('tasks', [
            'id' => ['type' => 'integer', 'auto_increment' => true, 'primary' => true],
            'task' => ['type' => 'string'],
            'created_at' => ['type' => 'string']
        ]);
        echo "✅ Bảng 'tasks' đã được tạo thành công!";
    } else {
        echo "⚠️ Bảng 'tasks' đã tồn tại.";
    }
} catch (Exception $e) {
    die("❌ Lỗi khi tạo bảng: " . $e->getMessage());
}
?>
