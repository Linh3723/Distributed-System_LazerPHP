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
        function formatDate(isoDate) {
            if (!isoDate || isoDate === "null") return "";
            const parts = isoDate.split("-");
            return `${parts[2]}/${parts[1]}/${parts[0]}`; 
        }
        function updateStatus(id, status) {
            fetch('update_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${encodeURIComponent(status)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                if (data.error) {
                    alert("Lỗi: " + data.error);
                } else {
                    fetchTasks(); // Cập nhật danh sách
                }
            })
            .catch(error => console.error("Lỗi cập nhật trạng thái:", error));
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
                    tasks.forEach(task => {
                        const formattedDate = formatDate(task.deadline); 
                        const row = table.insertRow(-1);
                        row.innerHTML = `
                            <td><span id="task-name-${task.id}">${task.task}</span> <input type="text" id="edit-task-${task.id}" value="${task.task}" style="display:none;"></td>
                            <td><span id="task-deadline-${task.id}">${task.deadline}</span> <input type="date" id="edit-deadline-${task.id}" value="${task.deadline}" style="display:none;"></td>
                            <td><input type="radio" name="status_${task.id}" value="doing" ${task.status === 'doing' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'doing')"></td>
                            <td><input type="radio" name="status_${task.id}" value="not_done" ${task.status === 'not_done' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'not_done')"></td>
                            <td><input type="radio" name="status_${task.id}" value="done" ${task.status === 'done' ? 'checked' : ''} onclick="updateStatus(${task.id}, 'done')"></td>
                            <td class="task-actions">
                                <button onclick="editTask(${task.id})">Sửa</button>
                                <button onclick="deleteTask(${task.id})">Xóa</button>
                                <button onclick="saveEdit(${task.id})" style="display:none;">Lưu</button>
                            </td>
                        `;
                    });
                    
                    table.innerHTML += `
                        <tr id="add-row" style="display: none;">
                            <td><input type="text" id="new-task" placeholder="Tên công việc"></td>
                            <td><input type="date" id="new-deadline"></td>
                            <td colspan="3"></td>
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

            // Debug: Kiểm tra xem dữ liệu lấy từ input có đúng không
            console.log("Task:", taskName, "Deadline:", deadline);

            if (!taskName || !deadline) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            fetch('add_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `task=${encodeURIComponent(taskName)}&deadline=${encodeURIComponent(deadline)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                if (data.error) {
                    alert("Lỗi: " + data.error);
                } else {
                    fetchTasks(); // Cập nhật danh sách công việc
                }
            })
            .catch(error => console.error("Lỗi khi gửi yêu cầu:", error));
        }


        function editTask(id) {
            document.getElementById(`task-name-${id}`).style.display = "none";
            document.getElementById(`edit-task-${id}`).style.display = "inline-block";
            document.getElementById(`task-deadline-${id}`).style.display = "none";
            document.getElementById(`edit-deadline-${id}`).style.display = "inline-block";
            document.querySelector(`button[onclick='editTask(${id})']`).style.display = "none";
            document.querySelector(`button[onclick='saveEdit(${id})']`).style.display = "inline-block";
        }

        function saveEdit(id) {
            const task = document.getElementById(`edit-task-${id}`).value.trim();
            const deadline = document.getElementById(`edit-deadline-${id}`).value;
            const status = document.querySelector(`input[name="status_${id}"]:checked`).value;

            if (!task || !deadline) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            fetch('update_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&task=${encodeURIComponent(task)}&deadline=${encodeURIComponent(deadline)}&status=${encodeURIComponent(status)}&save=true`
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                if (data.error) {
                    alert("Lỗi: " + data.error);
                } else {
                    fetchTasks(); // Cập nhật danh sách công việc
                }
            })
            .catch(error => console.error("Lỗi cập nhật:", error));
        }


        function deleteTask(id) {
            if (!confirm("Bạn có chắc muốn xóa công việc này?")) return;
            fetch('delete_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            })
            .then(() => fetchTasks())
            .catch(error => console.error("Lỗi xóa công việc:", error));
        }

        window.onload = fetchTasks;
    </script>
</head>
<body>
    <div class="sidebar">
        <p><b></b></p>
        <p>Tìm kiếm</p>
        <p>Danh sách công việc</p>
        <p>Quản lý tài chính</p>
        <p><a href="logout.php">Đăng xuất</a></p>
    </div>
    <div class="content">
        <h2>Danh sách công việc</h2>
        <table id="task-table"></table>
    </div>
</body>
</html>