<?php
require 'database.php'; // Đảm bảo file kết nối CSDL

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $taskId = intval($_POST['id']);
        $status = $_POST['status'];

        // Xác định trạng thái công việc (chỉ 1 trạng thái là true, còn lại false)
        $status_doing = ($status === 'doing') ? 1 : 0;
        $status_not_done = ($status === 'not_done') ? 1 : 0;
        $status_done = ($status === 'done') ? 1 : 0;

        // Cập nhật trạng thái trong database
        $sql = "UPDATE tasks SET status_doing = ?, status_not_done = ?, status_done = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $status_doing, $status_not_done, $status_done, $taskId);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Cập nhật thành công"]);
        } else {
            echo json_encode(["error" => "Cập nhật thất bại"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Thiếu dữ liệu"]);
    }
} else {
    echo json_encode(["error" => "Phương thức không hợp lệ"]);
}

$conn->close();
?>
