<?php
// Hiển thị trang danh sách sinh viên
if ($_SERVER['REQUEST_URI'] == '/students') {
    include 'views/students.php';
}

// Hiển thị form thêm sinh viên
if ($_SERVER['REQUEST_URI'] == '/add_student') {
    include 'views/add_student.php';
}

// Xử lý việc thêm sinh viên
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Đọc file students.json
    $students = json_decode(file_get_contents('database/students.json'), true);

    // Thêm sinh viên mới vào mảng
    $new_student = [
        'name' => $name,
        'email' => $email
    ];
    $students[] = $new_student;

    // Lưu lại vào file
    file_put_contents('database/students.json', json_encode($students, JSON_PRETTY_PRINT));

    // Chuyển hướng về trang danh sách sinh viên sau khi thêm thành công
    header('Location: /students');
    exit();
}
?>
