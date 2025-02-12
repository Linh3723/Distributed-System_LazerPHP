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
        $existingUser = Lazer::table('users')->where('email', '=', $email)->findAll();
        if (count($existingUser) > 0) {
            echo "❌ Email đã tồn tại. Vui lòng chọn email khác!";
            exit; 
        }

        $user = Lazer::table('users');
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->created_at = $created_at;
        $user->save(); 

        header("Location: login.php");
        exit; 
    } catch (Exception $e) {
        echo "❌ Lỗi khi lưu dữ liệu: " . $e->getMessage();
    }
}
?>
