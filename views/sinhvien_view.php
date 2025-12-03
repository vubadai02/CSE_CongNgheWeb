<?php 
// Tệp View CHỈ chứa HTML và logic hiển thị (echo, foreach) 
// Tệp View KHÔNG chứa câu lệnh SQL 
?> 
<!DOCTYPE html> 
<html lang="vi"> 
<head> 
 <meta charset="UTF-8"> 
 <title>PHT Chương 5 - MVC</title> 
 <style> 
 table { width: 100%; border-collapse: collapse; } 
 th, td { border: 1px solid #ddd; padding: 8px; } 
 th { background-color: #f2f2f2; } 
 </style> 
</head> 
<body> 
 <h2>Thêm Sinh Viên Mới (Kiến trúc MVC)</h2> 
 <form action = "index.php" method = "POST"> 
    <label for="ten_sinh_vien">Tên Sinh Viên:</label><br>
    <input type="text" id="ten_sinh_vien" name="ten_sinh_vien" required><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit">Thêm sinh viên</button>
 </form>
 <h2>Danh Sách Sinh Viên (Kiến trúc MVC)</h2> 
 <table> 
 <tr> 
 <th>ID</th> 
 <th>Tên Sinh Viên</th> 
 <th>Email</th> 
 <th>Ngày Tạo</th> 
 </tr> 
 <?php 
 // TODO 4: Dùng vòng lặp foreach để duyệt qua biến $danh_sach_sv 
 // (Biến $danh_sach_sv này sẽ được Controller truyền sang) 
 // Gợi ý: foreach ($danh_sach_sv as $sv) { ... } 
if (isset($danh_sach_sv) && is_array($danh_sach_sv)) {
    foreach ($danh_sach_sv as $sv) {
 // TODO 5: In (echo) các dòng <tr> và <td> chứa dữ liệu $sv 
 // Gợi ý: echo "<tr><td>" . htmlspecialchars($sv['id']) . 
        echo"<tr>";
        echo"<td>" . htmlspecialchars($sv['id']) . "</td>";
        echo"<td>" . htmlspecialchars($sv['ten_sinh_vien']) . "</td>";
        echo"<td>" . htmlspecialchars($sv['email']) . "</td>";
        echo"<td>" . htmlspecialchars($sv['ngay_tao']) . "</td>";
        echo"</tr>";

    }
 
 // Đóng vòng lặp 
 }
 else {
    echo "<tr><td colspan='4'>Không có sinh viên nào.</td></tr>";
 }
 ?> 
 </table> 
</body> 
</html>