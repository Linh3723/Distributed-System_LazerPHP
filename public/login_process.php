<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

use Lazer\Classes\Database as Lazer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kiểm tra nếu email và password không trống
    if (empty($email) || empty($password)) {
        echo "❌ Vui lòng điền đầy đủ email và mật khẩu!";
        exit;
    }

    try {
        // Tìm người dùng theo email
        $user = Lazer::table('users')->where('email', '=', $email)->find();

        if ($user) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user->password)) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];

                // Chuyển hướng sau khi đăng nhập thành công
                header("Location: dashboard.php");
                exit;
            } else {
                echo "❌ Mật khẩu không đúng!";
            }
        } else {
            echo "❌ Email không tồn tại!";
        }
    } catch (Exception $e) {
        echo "❌ Lỗi: " . htmlspecialchars($e->getMessage());
    }
}
?>
