<?php
include 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['submit'])) {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
        $file = $_FILES['csvFile']['tmp_name'];
        if (($handle = fopen($file, "r")) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ",");
            $sql = "INSERT INTO students (username, password, lastname, firstname, city, email, course1) 
                    VALUES (:username, :password, :lastname, :firstname, :city, :email, :course1)";
            try {
                $stmt = $conn->prepare($sql);
                $conn->beginTransaction();
                $count = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) < 7) continue;
                    $username = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]);
                    $password  = $data[1];
                    $lastname  = $data[2];
                    $firstname = $data[3];
                    $city      = $data[4];
                    $email     = $data[5];
                    $course    = $data[6];
                    $stmt->execute([
                        ':username'  => $username,
                        ':password'  => $password,
                        ':lastname'  => $lastname,
                        ':firstname' => $firstname,
                        ':city'      => $city,
                        ':email'     => $email,
                        ':course1'   => $course
                    ]);
                    $count++;
                }
                $conn->commit();
                $message = "Thành công! Đã import $count sinh viên vào CSDL.";
                $messageType = "success";
            } catch (PDOException $e) {
                $conn->rollBack();
                $message = "Lỗi khi import: " . $e->getMessage();
                $messageType = "error";
            }
            
            fclose($handle);
        }
    } else {
        $message = "Vui lòng chọn file CSV hợp lệ.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV (PDO)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="container">
    <h1>Upload Danh sách Sinh viên (PDO)</h1>
    <?php if($message): ?>
        <div class="alert <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label><strong>Chọn file CSV:</strong></label><br>
        <input type="file" name="csvFile" accept=".csv" required style="margin: 10px 0;">
        <br>
        <button type="submit" name="submit" style="padding: 8px 15px; cursor: pointer;">Upload & Import</button>
    </form>
    
    <hr>
    
    <h3>Danh sách sinh viên hiện có trong Database:</h3>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Họ tên</th>
                <th>Lớp/Thành phố</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $conn->prepare("SELECT * FROM students ORDER BY id DESC");
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($students) > 0) {
                    foreach ($students as $sv) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($sv['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['lastname'] . ' ' . $sv['firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['city']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['email']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center'>Chưa có dữ liệu</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Lỗi tải dữ liệu: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html><?php
include 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['submit'])) {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
        $file = $_FILES['csvFile']['tmp_name'];
        if (($handle = fopen($file, "r")) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ",");
            $sql = "INSERT INTO students (username, password, lastname, firstname, city, email, course1) 
                    VALUES (:username, :password, :lastname, :firstname, :city, :email, :course1)";
            try {
                $stmt = $conn->prepare($sql);
                $conn->beginTransaction();
                $count = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) < 7) continue;
                    $username = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]);
                    $password  = $data[1];
                    $lastname  = $data[2];
                    $firstname = $data[3];
                    $city      = $data[4];
                    $email     = $data[5];
                    $course    = $data[6];
                    $stmt->execute([
                        ':username'  => $username,
                        ':password'  => $password,
                        ':lastname'  => $lastname,
                        ':firstname' => $firstname,
                        ':city'      => $city,
                        ':email'     => $email,
                        ':course1'   => $course
                    ]);
                    $count++;
                }
                $conn->commit();
                $message = "Thành công! Đã import $count sinh viên vào CSDL.";
                $messageType = "success";
            } catch (PDOException $e) {
                $conn->rollBack();
                $message = "Lỗi khi import: " . $e->getMessage();
                $messageType = "error";
            }
            
            fclose($handle);
        }
    } else {
        $message = "Vui lòng chọn file CSV hợp lệ.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV (PDO)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="container">
    <h1>Upload Danh sách Sinh viên (PDO)</h1>
    <?php if($message): ?>
        <div class="alert <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label><strong>Chọn file CSV:</strong></label><br>
        <input type="file" name="csvFile" accept=".csv" required style="margin: 10px 0;">
        <br>
        <button type="submit" name="submit" style="padding: 8px 15px; cursor: pointer;">Upload & Import</button>
    </form>
    
    <hr>
    
    <h3>Danh sách sinh viên hiện có trong Database:</h3>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Họ tên</th>
                <th>Lớp/Thành phố</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $conn->prepare("SELECT * FROM students ORDER BY id DESC");
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($students) > 0) {
                    foreach ($students as $sv) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($sv['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['lastname'] . ' ' . $sv['firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['city']) . "</td>";
                        echo "<td>" . htmlspecialchars($sv['email']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center'>Chưa có dữ liệu</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Lỗi tải dữ liệu: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>