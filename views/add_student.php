<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
</head>
<body>
    <h2>Thêm Sinh Viên Mới</h2>
    <form action="/add_student" method="POST">
        <label for="name">Tên Sinh Viên:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <input type="submit" value="Thêm Sinh Viên">
    </form>
</body>
</html>
