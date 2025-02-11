<?php
require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $created_at = date('Y-m-d H:i:s');

    try {
        // Kiểm tra xem email đã tồn tại chưa
        $existingUser = Lazer::table('users')->where('email', '=', $email)->findAll();
        if (count($existingUser) > 0) {
            echo "❌ Email đã tồn tại. Vui lòng chọn email khác!";
            exit; // Dừng chương trình nếu email đã tồn tại
        }

        // Nếu email chưa tồn tại, thêm mới
        $user = Lazer::table('users');
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->created_at = $created_at;
        $user->save(); // Lưu dữ liệu

        echo "✅ Đăng ký thành công!";
    } catch (Exception $e) {
        echo "❌ Lỗi khi lưu dữ liệu: " . $e->getMessage();
    }
}
?>
