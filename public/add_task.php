<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task = isset($_POST['task']) ? trim($_POST['task']) : null;
    $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    if (!$task || !$deadline || !$status) {
        echo json_encode(["error" => "Dữ liệu không hợp lệ"]);
        exit;
    }

    // Chuyển đổi định dạng ngày (fix lỗi data type)
    $deadlineFormatted = date("Y-m-d", strtotime($deadline));

    // Đọc dữ liệu từ file JSON
    $jsonFile = "tasks.json";
    $tasks = json_decode(file_get_contents($jsonFile), true);
    $newId = $tasks["last_id"] + 1;

    // Thêm task mới
    $tasks["last_id"] = $newId;
    $tasks["tasks"][] = [
        "id" => $newId,
        "task" => $task,
        "deadline" => $deadlineFormatted,
        "status_doing" => $status === "doing",
        "status_not_done" => $status === "not_done",
        "status_done" => $status === "done",
        "created_at" => date("Y-m-d H:i:s")
    ];

    // Lưu lại file JSON
    file_put_contents($jsonFile, json_encode($tasks, JSON_PRETTY_PRINT));

    echo json_encode(["success" => true, "id" => $newId]);
} else {
    echo json_encode(["error" => "Phương thức không hợp lệ"]);
}
?>
