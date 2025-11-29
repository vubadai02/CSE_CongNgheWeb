<?php
session_start();

// Xử lý đăng xuất nếu có ?logout=1
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.html');
    exit;
}

// Kiểm tra nếu chưa login, redirect về login
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
}

$loggedInUser = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chào mừng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #2e8b57;
        }
        img {
            max-width: 300px;
            margin: 20px 0;
            border-radius: 10px;
        }
        .content {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px #aaa;
        }
        a.logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f04e30;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.logout:hover {
            background-color: #c03c25;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Chào mừng trở lại, <?php echo $loggedInUser; ?>!</h1>
        <p>Bạn đã đăng nhập thành công.</p>
        <img src="welcome.jpg" alt="Welcome Image">
        <p>Hôm nay là một ngày tuyệt vời để học PHP và quản lý session!</p>
        <p>Bạn có thể khám phá các bài học khác, thử nghiệm thêm form và database.</p>

        <!-- Link đăng xuất -->
        <a class="logout" href="welcome.php?logout=1">Đăng xuất</a>
    </div>
</body>
</html>
