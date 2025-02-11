<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý công việc</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background: #f4f4f4;
            padding: 20px;
        }
        .content {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #000;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .task-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .add-task {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
    <script>
        function fetchTasks() {
            fetch('get_tasks.php')
                .then(response => response.json())
                .then(tasks => {
                    const table = document.getElementById("task-table");
                    table.innerHTML = `
                        <tr>
                            <th>Tên công việc</th>
                            <th>Ngày hết hạn</th>
                            <th>Đang làm</th>
                            <th>Chưa làm</th>
                            <th>Đã hoàn thành</th>
                            <th>Hành động</th>
                        </tr>
                    `;
                    tasks.forEach(task => {
                        const row = table.insertRow(-1);
                        row.innerHTML = `
                            <td>${task.task}</td>
                            <td>${task.deadline}</td>
                            <td><input type="radio" name="status_${task.id}" value="doing" ${task.status_doing ? 'checked' : ''} onclick="updateStatus(${task.id}, 'doing', this)"></td>
                            <td><input type="radio" name="status_${task.id}" value="not_done" ${task.status_not_done ? 'checked' : ''} onclick="updateStatus(${task.id}, 'not_done', this)"></td>
                            <td><input type="radio" name="status_${task.id}" value="done" ${task.status_done ? 'checked' : ''} onclick="updateStatus(${task.id}, 'done', this)"></td>

                            <td class="task-actions">
                                <button onclick="editTask(${task.id})">Sửa</button>
                                <button onclick="deleteTask(${task.id})">Xóa</button>
                            </td>
                        `;
                    });
                    const addRow = table.insertRow(-1);
                    addRow.innerHTML = `<td colspan="6" class="add-task" onclick="addTaskRow()">+ Thêm công việc</td>`;
                })
                .catch(error => console.error('Lỗi:', error));
        }

        function updateStatus(taskId, status) {
            fetch('update_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${taskId}&status=${status}`
            })
            .then(response => response.text())
            .then(data => console.log("Cập nhật thành công:", data))
            .catch(error => console.error("Lỗi cập nhật:", error));
        }

        function addTaskRow() {
            const table = document.getElementById("task-table");
            const newRow = table.insertRow(table.rows.length - 1);
            newRow.innerHTML = `
                <td><input type="text" name="task" placeholder="Tên công việc" required></td>
                <td><input type="date" name="deadline" required></td>
                <td><input type="radio" name="new_status" value="doing" required></td>
                <td><input type="radio" name="new_status" value="not_done" required></td>
                <td><input type="radio" name="new_status" value="done" required></td>
                <td class="task-actions">
                    <button onclick="saveTask(this)">Lưu</button>
                </td>
            `;
        }


        function saveTask(button) {
            const row = button.closest("tr");
            const taskName = row.querySelector("input[name='task']").value.trim();
            const deadline = row.querySelector("input[name='deadline']").value;
            const status = row.querySelector("input[name='new_status']:checked")?.value;

            if (!taskName || !deadline || !status) {
                alert("❌ Vui lòng nhập đầy đủ thông tin và chọn trạng thái!");
                return;
            }

            console.log("🔄 Đang gửi request...");
            
            fetch('add_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `task=${encodeURIComponent(taskName)}&deadline=${encodeURIComponent(deadline)}&status=${encodeURIComponent(status)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log("✅ Phản hồi từ server:", data);
                if (data.error) {
                    alert(`❌ Lỗi khi thêm công việc: ${data.error}`);
                } else {
                    alert("✅ Thêm công việc thành công!");
                    fetchTasks();
                }
            })
            .catch(error => {
                console.error("❌ Lỗi request:", error);
                alert("⚠ Không thể kết nối đến máy chủ!");
            });
        }




        window.onload = fetchTasks;
    </script>
</head>
<body>
    <div class="sidebar">
        <p><b>Ảnh đại diện + tên</b></p>
        <p>Tìm kiếm</p>
        <p>Danh sách công việc</p>
        <p>Quản lý tài chính</p>
        <p><a href="logout.php">Đăng xuất</a></p>
    </div>
    <div class="content">
        <h2>Danh sách công việc</h2>
        <table id="task-table">
        </table>
    </div>
</body>
</html>
