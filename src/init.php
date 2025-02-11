<?php

use Lazer\Classes\Database as Lazer;
use Lazer\Classes\LazerException;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');
require_once __DIR__ . '/../vendor/autoload.php';

// Hàm kiểm tra và tạo bảng nếu chưa tồn tại
function createTableIfNotExists($tableName, $schema)
{
    try {
        Lazer::table($tableName)->findAll();
        echo "⚠️ Bảng '$tableName' đã tồn tại.<br>";
    } catch (LazerException $e) {
        if (strpos($e->getMessage(), "Table \"$tableName\" not found") !== false) {
            try {
                Lazer::create($tableName, $schema);
                echo "✅ Bảng '$tableName' đã được tạo thành công!<br>";
            } catch (Exception $ex) {
                die("❌ Lỗi khi tạo bảng '$tableName': " . $ex->getMessage());
            }
        } else {
            die("❌ Lỗi truy vấn bảng '$tableName': " . $e->getMessage());
        }
    }
}

// Tạo bảng users nếu chưa tồn tại
createTableIfNotExists('users', [
    'id' => 'integer',
    'name' => 'string',
    'email' => 'string',
    'password' => 'string',
    'created_at' => 'string'
]);

// Tạo bảng tasks nếu chưa tồn tại
createTableIfNotExists('tasks', [
    'id' => 'integer',
    'task' => 'string',
    'deadline' => 'string',
    'status_doing' => 'boolean',
    'status_not_done' => 'boolean',
    'status_done' => 'boolean',
    'created_at' => 'string'
]);

?>