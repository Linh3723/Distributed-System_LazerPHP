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
            width: 200px;
            background: #f4f4f4;
            padding: 20px;
        }
        .sidebar p {
            cursor: pointer;
            padding: 10px;
            margin: 5px 0;
            transition: 0.3s;
        }
        .sidebar p.active {
            font-weight: bold;
            color: #007bff;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
            height: 34px;
        }
        .add-task {
            cursor: pointer;
            font-weight: bold;
        }
        .radio-container {
            display: flex;
            justify-content: center;
        }
        input[type="radio"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid gray;
            background-color: white;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
        }

        input[value="doing"]:checked {
            background-color: blue;
            border-color: blue;
        }

        input[value="not_done"]:checked {
            background-color: red;
            border-color: red;
        }

        input[value="done"]:checked {
            background-color: green;
            border-color: green;
        }
        .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .delete-icon {
            width: 30px;
            height: 30px;
        }
        .edit-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .edit-icon {
            width: 30px;
            height: 30px;
        }
        .save-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .save-icon {
            width: 30px;
            height: 30px;
        }
    </style>
    <script>
        function showTab(tabId, element) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.sidebar p').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
            if (tabId === 'task-list') {
                fetchTasks();
            }
        }

        function showAddTask() {
            document.getElementById("add-row").style.display = "table-row";
        }

        function formatDate(isoDate) {
            if (!isoDate || isoDate === "null") return "";
            
            const dateObj = new Date(isoDate);
            const day = String(dateObj.getDate()).padStart(2, '0');
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const year = dateObj.getFullYear();

            return `${day}/${month}/${year}`;
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
                    fetchTasks(); 
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
                            <td><span id="task-name-${task.id}">${task.task}</span> 
                                <input type="text" id="edit-task-${task.id}" value="${task.task}" style="display:none;">
                            </td>
                            <td><span id="task-deadline-${task.id}">${formattedDate}</span> 
                                <input type="date" id="edit-deadline-${task.id}" value="${task.deadline}" style="display:none;">
                            </td>
                            <td><input type="radio" name="status_${task.id}" value="doing" ${task.status === 'doing' ? 'checked' : ''} disabled></td>
                            <td><input type="radio" name="status_${task.id}" value="not_done" ${task.status === 'not_done' ? 'checked' : ''} disabled></td>
                            <td><input type="radio" name="status_${task.id}" value="done" ${task.status === 'done' ? 'checked' : ''} disabled></td>
                            <td class="task-actions">
                                <button onclick="editTask(${task.id})" class="edit-btn">
                                    <img src="https://cdn2.iconfinder.com/data/icons/ui-web-thin/128/edit-change-pen-write-512.png" alt="Sửa" class="edit-icon">
                                </button>
                                <button onclick="deleteTask(${task.id})" class="delete-btn">
                                    <img src="https://e7.pngegg.com/pngimages/862/47/png-clipart-rubbish-bins-waste-paper-baskets-computer-icons-recycling-waste-miscellaneous-angle.png" alt="Xóa" class="delete-icon">
                                </button>
                            </td>
                        `;
                    });

                    table.innerHTML += `
                        <tr id="add-row" style="display: none;">
                            <td><input type="text" id="new-task" placeholder="Tên công việc"></td>
                            <td><input type="date" id="new-deadline"></td>
                            <td colspan="3"></td>
                            <td>
                                <button onclick="saveTask()" class="save-btn">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4856/4856668.png" alt="Lưu" class="save-icon">
                                </button>
                            </td>
                        </tr>
                        <tr><td colspan="6" class="add-task" onclick="showAddTask()">+ Thêm công việc</td></tr>
                    `;
                })
                .catch(error => console.error("Lỗi tải danh sách:", error));
        }

        function saveTask() {
            const taskName = document.getElementById("new-task").value.trim();
            const deadline = document.getElementById("new-deadline").value;

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
                    fetchTasks(); 
                }
            })
            .catch(error => console.error("Lỗi khi gửi yêu cầu:", error));
        }

        function editTask(id) {
            const radios = document.querySelectorAll(`input[name="status_${id}"]`);
            radios.forEach(radio => {
                radio.disabled = false;
            });

            const editButton = document.querySelector(`button[onclick="editTask(${id})"]`);
            editButton.innerHTML = `<img src="https://cdn-icons-png.flaticon.com/512/4856/4856668.png" alt="Lưu" class="save-icon">`;
            editButton.setAttribute("onclick", `saveEdit(${id})`);
        }

        function saveEdit(id) {
            const status = document.querySelector(`input[name="status_${id}"]:checked`).value;

            fetch('update_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${encodeURIComponent(status)}&save=true`
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Lỗi: " + data.error);
                } else {
                    const radios = document.querySelectorAll(`input[name="status_${id}"]`);
                    radios.forEach(radio => {
                        radio.disabled = true;
                    });

                    const saveButton = document.querySelector(`button[onclick="saveEdit(${id})"]`);
                    saveButton.innerHTML = `<img src="https://cdn2.iconfinder.com/data/icons/ui-web-thin/128/edit-change-pen-write-512.png" alt="Sửa" class="edit-icon">`;
                    saveButton.setAttribute("onclick", `editTask(${id})`);
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
        function addTransaction() {
    const type = document.getElementById("trans-type").value;
    const amount = document.getElementById("trans-amount").value;
    const description = document.getElementById("trans-desc").value;

    if (!amount || !description) {
        alert("Vui lòng nhập số tiền và mô tả!");
        return;
    }

    fetch("add_transaction.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `type=${encodeURIComponent(type)}&amount=${encodeURIComponent(amount)}&description=${encodeURIComponent(description)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Lỗi: " + data.error);
        } else {
            alert("Giao dịch đã thêm thành công!");
            fetchTransactions();
        }
    })
    .catch(error => console.error("Lỗi khi gửi dữ liệu:", error));
}

        function fetchTransactions() {
            fetch("get_transactions.php")
                .then(response => response.json())
                .then(transactions => {
                    const table = document.getElementById("finance-table");
                    table.innerHTML = `
                        <tr>
                            <th>Loại</th>
                            <th>Số tiền</th>
                            <th>Mô tả</th>
                        </tr>
                    `;
                    transactions.forEach(tran => {
                        const row = table.insertRow(-1);
                        row.innerHTML = `
                            <td>${tran.type}</td>
                            <td>${tran.amount}</td>
                            <td>${tran.description}</td>
                        `;
                    });
                })
                .catch(error => console.error("Lỗi tải danh sách:", error));
        }

        window.onload = fetchTransactions;
        window.onload = fetchTasks;
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="user-info">
            <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/non_2x/user-icon-on-transparent-background-free-png.png" alt="User Avatar" width="120" height="70">
            <p>Người dùng</p>
        </div>
        <p onclick="showTab('search', this)" class="active">Tìm kiếm</p>
        <p onclick="showTab('task-list', this)">Danh sách công việc</p>
        <p onclick="showTab('calender', this)">Lịch</p>
        <p onclick="showTab('finance', this)">Quản lý tài chính</p>
        <p><a href="logout.php">Đăng xuất</a></p>
    </div>
    <div class="content">
        <div id="search" class="tab-content active">
            <h2>Tìm kiếm</h2>
            <input type="text" placeholder="Nhập từ khóa...">
            <button>Tìm kiếm</button>   
        </div>
        <div id="task-list" class="tab-content">
            <h2>Danh sách công việc</h2>
            <table id="task-table"></table>
        </div>
        <div id="calender" class="tab-content">
            <h2>To do list</h2>           
            <iframe src="tasks.php" width="100%" height="600px" style="border: none;"></iframe>
        </div>

        <div id="finance" class="tab-content">
            <h2>Quản lý tài chính</h2>
            <div class="finance-form">
                <select id="trans-type">
                    <option value="Thu nhập">Thu nhập</option>
                    <option value="Chi tiêu">Chi tiêu</option>
                </select>
                <input type="number" id="trans-amount" placeholder="Số tiền">
                <input type="text" id="trans-desc" placeholder="Mô tả giao dịch">
                <button onclick="addTransaction()">Thêm giao dịch</button>
            </div>
            <table id="finance-table" border="1" style="margin-top: 20px; width: 100%; text-align: center;">
                <tr>
                    <th>Loại</th>
                    <th>Số tiền</th>
                    <th>Mô tả</th>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>