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
        function showAddTask() {
            document.getElementById("add-row").style.display = "table-row";
        }
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

                    if (!Array.isArray(tasks)) {
                        console.error("Dữ liệu không hợp lệ:", tasks);
                        table.innerHTML += `<tr><td colspan="6">Lỗi tải danh sách!</td></tr>`;
                        return;
                    }

                    if (tasks.length === 0) {
                        table.innerHTML += `<tr><td colspan="6">Không có công việc nào!</td></tr>`;
                    } else {
                        tasks.forEach(task => {
                            const row = table.insertRow(-1);
                            row.innerHTML = `
                                <td>${task.task}</td>
                                <td>${task.deadline}</td>
                                <td><input type="radio" name="status_${task.id}" value="doing" ${task.status === 'doing' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'doing')"></td>
                                <td><input type="radio" name="status_${task.id}" value="not_done" ${task.status === 'not_done' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'not_done')"></td>
                                <td><input type="radio" name="status_${task.id}" value="done" ${task.status === 'done' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'done')"></td>
                                <td><button onclick="deleteTask(${task.id})">Xóa</button></td>
                            `;
                        });
                    }

                    // Luôn thêm dòng "+ Thêm công việc"
                    table.innerHTML += `
                        <tr id="add-row" style="display: none;">
                            <td><input type="text" id="new-task" placeholder="Tên công việc"></td>
                            <td><input type="date" id="new-deadline"></td>
                            <td>
                                <input type="radio" name="new-status" value="doing"> Đang làm
                            </td>
                            <td>
                                <input type="radio" name="new-status" value="not_done" checked> Chưa làm
                            </td>
                            <td>
                                <input type="radio" name="new-status" value="done"> Hoàn thành
                            </td>
                            <td><button onclick="saveTask()">Lưu</button></td>
                        </tr>
                        <tr><td colspan="6" class="add-task" onclick="showAddTask()">+ Thêm công việc</td></tr>
                    `;

                })
                .catch(error => console.error("Lỗi tải danh sách:", error));
        }

        function saveTask() {
            const taskName = document.getElementById("new-task").value.trim();
            const deadline = document.getElementById("new-deadline").value;

            if (!taskName || !deadline) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            const formData = new URLSearchParams();
            formData.append("task", taskName);
            formData.append("deadline", deadline);

            console.log("Dữ liệu gửi đi:", taskName, deadline);

            fetch("add_task.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData.toString(),
            })
            .then(response => response.json())
            .then(data => {
                console.log("Phản hồi từ server:", data);
                if (data.error) {
                    alert("❌ " + data.error);
                } else {
                    fetchTasks();
                }
            })
            .catch(error => console.error("Lỗi thêm công việc:", error));
        }



        function updateStatus(taskId, status) {
            fetch('update_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${taskId}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("❌ " + data.error);
                } else {
                    fetchTasks(); // Tải lại danh sách sau khi cập nhật
                }
            })
            .catch(error => console.error("Lỗi cập nhật:", error));
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
