<?php
include 'db.php';
try {
    $stmt = $conn->prepare("SELECT * FROM flowers");
    $stmt->execute();
    $flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'guest';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>14 Loại hoa tuyệt đẹp dịp Xuân Hè</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        .nav { text-align: center; margin-bottom: 20px; }
        .nav a { margin: 0 10px; text-decoration: none; color: #fff; background: #007bff; padding: 10px 20px; border-radius: 4px; }
        .nav a:hover { background: #0056b3; }
        
        /* Style cho GUEST MODE (Dạng bài viết) */
        .flower-post { margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 20px; }
        .flower-post h2 { color: #d63384; }
        .flower-post img { max-width: 100%; height: auto; display: block; margin: 10px 0; border-radius: 5px; }
        .flower-post p { font-size: 16px; text-align: justify; }

        /* Style cho ADMIN MODE (Dạng bảng) */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #343a40; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .thumbnail { width: 100px; height: auto; object-fit: cover; }
        .btn-edit { color: blue; cursor: pointer; margin-right: 10px; }
        .btn-delete { color: red; cursor: pointer; }
        .add-btn { display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; margin-bottom: 10px;}
    </style>
</head>
<body>

<div class="container">
    <h1>Quản lý và Hiển thị các loài hoa</h1>
    
    <div class="nav">
        <a href="?mode=guest">Xem dạng Khách (Bài viết)</a>
        <a href="?mode=admin">Xem dạng Quản trị (Admin)</a>
    </div>

    <hr>

    <?php if ($mode == 'guest'): ?>
        <div class="guest-view">
            <?php foreach ($flowers as $index => $flower): ?>
                <div class="flower-post">
                    <h2><?php echo ($index + 1) . '. ' . $flower['name']; ?></h2>
                    <img src="<?php echo $flower['image']; ?>" alt="<?php echo $flower['name']; ?>">
                    <p><?php echo $flower['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($mode == 'admin'): ?>
        <div class="admin-view">
            <a href="#" class="add-btn">+ Thêm loài hoa mới</a>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên hoa</th>
                        <th>Mô tả</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flowers as $index => $flower): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><strong><?php echo $flower['name']; ?></strong></td>
                            <td><?php echo $flower['description']; ?></td>
                            <td><img src="<?php echo $flower['image']; ?>" class="thumbnail" alt="Thumb"></td>
                            <td>
                                <a href="#" class="btn-edit">Sửa</a>
                                <a href="#" class="btn-delete">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

</body>
</html>