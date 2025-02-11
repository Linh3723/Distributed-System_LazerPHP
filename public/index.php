<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiyoList</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            text-align: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
            color: #666;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chào mừng đến với Hệ Thống</h2>
        <p>Vui lòng Đăng ký hoặc Đăng nhập để tiếp tục</p>
        <div class="button-container">
            <a href="register.php"><button>Đăng Ký</button></a>
            <a href="login.php"><button>Đăng Nhập</button></a>
        </div>
    </div>
</body>
</html>
