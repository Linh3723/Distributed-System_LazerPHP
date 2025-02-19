<?php
require __DIR__ . '/../vendor/autoload.php';
define('LAZER_DATA_PATH', __DIR__ . '/../database/');
use Lazer\Classes\Database as Lazer;

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($keyword)) {
    try {
        $tasks = Lazer::table('tasks')->findAll();
        foreach ($tasks as $task) {
            if (
                stripos($task->task, $keyword) !== false ||
                stripos($task->deadline, $keyword) !== false ||
                stripos($task->status, $keyword) !== false
            ) {
                $results[] = $task;
            }
        }
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm công việc</title>
</head>
<body>
    <center>
        <h2 style="margin-bottom: 10px;">Tìm kiếm</h2>
        <form method="GET" style="display: flex; justify-content: center; gap: 10px;">
            <input type="text" name="q" placeholder="Nhập từ khóa..." 
                value="<?php echo htmlspecialchars($keyword); ?>" 
                style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
            <button type="submit" 
                    style="padding: 10px 15px; background-color: #007BFF; color: white; 
                        border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                Tìm kiếm
            </button>
        </form>
        <br>
    </center>

    <?php if (!empty($results)): ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-not_done {
            color: red;
            font-weight: bold;
        }
        .status-doing {
            color: orange;
            font-weight: bold;
        }
        .status-done {
            color: green;
            font-weight: bold;
        }
    </style>

    <table>
        <tr>
            <th>Task</th>
            <th>Deadline</th>
            <th>Status</th>
        </tr>
        <?php foreach ($results as $task): ?>
            <tr>
                <td><?php echo htmlspecialchars($task->task); ?></td>
                <td>
                    <?php 
                        $date = DateTime::createFromFormat('Y-m-d', $task->deadline);
                        echo $date ? $date->format('d/m/Y') : htmlspecialchars($task->deadline); 
                    ?>
                </td>
                <td class="status-<?php echo htmlspecialchars($task->status); ?>">
                    <?php 
                        $statusMap = [
                            'not_done' => 'Chưa hoàn thành',
                            'done' => 'Đã hoàn thành',
                            'doing' => 'Đang làm'
                        ];
                        echo isset($statusMap[$task->status]) ? $statusMap[$task->status] : htmlspecialchars($task->status);
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php elseif ($keyword !== ''): ?>
        <p>Không tìm thấy kết quả nào.</p>
    <?php endif; ?>

</body>
</html>
