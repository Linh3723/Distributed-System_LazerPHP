<?php

use Lazer\Classes\Database as Lazer;

define('LAZER_DATA_PATH', __DIR__ . '/../database/'); // Định nghĩa thư mục chứa database

require_once __DIR__ . '/../vendor/autoload.php'; // Nạp Composer autoload

// Kiểm tra nếu bảng "students" chưa tồn tại, thì tạo mới
if (!file_exists(LAZER_DATA_PATH . 'students.json')) {
    Lazer::create('students', [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'created_at' => 'string'
    ]);
    echo "Table 'students' created successfully!\n";
} else {
    echo "Table 'students' already exists.\n";
}
