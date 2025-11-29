<?php
require_once 'db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ Form
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password)) {
        $message = "Vui lòng nhập Tên đăng nhập và Mật khẩu.";
        $message_type = 'danger';
    } else {
        try {
            $sql = "INSERT INTO users (username, password, firstname, email) 
                    VALUES (:username, :password, :firstname, :email)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':username' => $username,
                ':password' => $password, // LƯU Ý: Nên mã hóa mật khẩu trong thực tế!
                ':firstname' => $firstname,
                ':email' => $email,
            ]);

            $message = "Thêm người dùng **$username** thành công vào CSDL.";
            $message_type = 'success';
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                 $message = "Lỗi: Tên đăng nhập **$username** đã tồn tại.";
            } else {
                 $message = "Lỗi CSDL: " . $e->getMessage();
            }
            $message_type = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4 text-primary">Thêm Người Dùng Mới vào CSDL</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow">
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập (Username)*</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu (Password)*</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="firstname" class="form-label">Tên (Firstname)</label>
                <input type="text" name="firstname" id="firstname" class="form-control">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100">Thêm Người Dùng</button>
        </form>
        <a href="index_hoa.php" class="btn btn-secondary mt-3">Xem Danh Sách Đã Lưu</a>
    </div>
</div>
</body>
</html>