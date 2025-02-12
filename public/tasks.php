<?php

define('DATA_FILE', __DIR__ . '/../database/tasks.data.json');

// Đọc dữ liệu từ JSON
$tasksData = json_decode(file_get_contents(DATA_FILE), true) ?? [];

// Khởi tạo danh sách công việc
$tasks = [
    'completed' => [],
    'past' => [],
    'today' => [],
    'future' => []
];

$today = date('Y-m-d');

foreach ($tasksData as $task) {
    $taskDate = date('Y-m-d', strtotime($task['deadline']));
    $isCompleted = ($task['status'] === 'done');
    $isPast = ($taskDate < $today);
    
    if ($isCompleted) {
        $tasks['completed'][] = $task; // Công việc đã hoàn thành
    } elseif ($isPast) {
        $tasks['past'][] = $task; // Công việc quá hạn
    } elseif ($taskDate === $today) {
        $tasks['today'][] = $task; // Công việc hôm nay
    } else {
        $tasks['future'][] = $task; // Công việc sắp tới
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách công việc</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .task-list { margin: 20px; }
        .task-date { font-weight: bold; margin-top: 20px; font-size: 18px; }
        .task-item { padding: 5px 10px; border-bottom: 1px solid #ddd; }
        .no-task { color: gray; font-style: italic; }
    </style>
</head>
<body>
    <div class="task-list">
        
        <!-- Công việc đã hoàn thành -->
        <?php if (!empty($tasks['completed'])): ?>
            <div class="task-date">✅ Công việc đã hoàn thành</div>
            <?php foreach ($tasks['completed'] as $task): ?>    
                <div class="task-item">
                    <strong><?= htmlspecialchars($task['task']); ?></strong> - Đã hoàn thành đúng hạn: <?= date('d/m/Y', strtotime($task['deadline'])); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- Công việc quá hạn -->
        <?php if (!empty($tasks['past'])): ?>
            <div class="task-date">🔴 Công việc quá hạn</div>
            <?php foreach ($tasks['past'] as $task): ?>
                <div class="task-item">
                    <strong><?= htmlspecialchars($task['task']); ?></strong> - Hết hạn ngày <?= date('d/m/Y', strtotime($task['deadline'])); ?> - ❌ Chưa hoàn thành
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- Công việc hôm nay -->
        <div class="task-date">📅 Hôm nay (<?= date('d/m/Y'); ?>)</div>
        <?php if (!empty($tasks['today'])): ?>
            <?php foreach ($tasks['today'] as $task): ?>
                <div class="task-item">
                    <strong><?= htmlspecialchars($task['task']); ?></strong> - ⏳ Đang làm
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="task-item no-task">Không có công việc nào</div>
        <?php endif; ?>
        
        <!-- Công việc sắp tới -->
        <?php if (!empty($tasks['future'])): ?>
            <div class="task-date">🟢 Công việc sắp tới</div>
            <?php foreach ($tasks['future'] as $task): ?>
                <div class="task-item">
                    <strong><?= htmlspecialchars($task['task']); ?></strong> - Hạn chót: <?= date('d/m/Y', strtotime($task['deadline'])); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
