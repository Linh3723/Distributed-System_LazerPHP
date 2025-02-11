<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên</title>
</head>
<body>
    <h2>Danh Sách Sinh Viên</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $students = json_decode(file_get_contents('database/students.json'), true);
            foreach ($students as $student) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                echo "<td>" . htmlspecialchars($student['email']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
