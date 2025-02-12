<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

use Lazer\Classes\Database as Lazer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $user = Lazer::table('users')->where('email', '=', $email)->find();

        if ($user) {
            if (password_verify($password, $user->password)) {
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];

                header("Location: dashboard.php");
                exit;
            } else {
                echo "❌ Mật khẩu không đúng!";
            }
        } else {
            echo "❌ Email không tồn tại!";
        }
    } catch (Exception $e) {
        echo "❌ Lỗi: " . $e->getMessage();
    }
}
