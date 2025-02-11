<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "Bạn chưa đăng nhập!";
    exit;
}
echo "Xin chào, " . $_SESSION['user']['name'];

require __DIR__ . '/../vendor/autoload.php';
use Lazer\Classes\Database as Lazer;

define('LAZER_DATA_PATH', __DIR__ . '/../database/');

// Lấy danh sách công việc từ database
$tasks = Lazer::table('tasks')->findAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý công việc</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
        table { width: 80%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #f4f4f4; }
        form { display: inline-block; margin: 5px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
    <h2>Quản lý công việc</h2>
    <a href="logout.php">Đăng xuất</a>

    <!-- Form thêm công việc -->
    <form action="add_task.php" method="POST">
        <input type="text" name="task" placeholder="Tên công việc" required>
        <button type="submit">Thêm</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Công việc</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($tasks as $task) : ?>
        <tr>
            <td><?= $task->id ?></td>
            <td><?= htmlspecialchars($task->task) ?></td>
            <td>
                <form action="edit_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $task->id ?>">
                    <input type="text" name="task" value="<?= htmlspecialchars($task->task) ?>" required>
                    <button type="submit">Sửa</button>
                </form>
                <form action="delete_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $task->id ?>">
                    <button type="submit">Xóa</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
