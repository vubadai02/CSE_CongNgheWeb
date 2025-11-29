<?php
// Đường dẫn file và cấu hình
$filename = '65HTTT_Danh_sach_diem_danh.csv'; // Đảm bảo tên file dữ liệu của bạn khớp
$delimiter = ','; // Dấu phân cách của tệp CSV

$data = [];
$error_message = '';

// Định nghĩa tiêu đề cột mong muốn hiển thị và ánh xạ sang tiêu đề trong file
// CHÚ Ý: Key phải khớp với tiêu đề cột trong file CSV gốc (vd: 'lastname', 'course1')
$display_map = [
    'username' => 'Username',
    'password' => 'Password',
    'lastname' => 'Họ đệm (Lastname)',
    'firstname' => 'Tên (Firstname)',
    'city' => 'Lớp/Thành phố (City)',
    'email' => 'Email',
    'course1' => 'Khóa học (Course1)'
];

$header_indices = []; // Lưu trữ vị trí (index) của các cột cần thiết

if (file_exists($filename)) {
    if (($handle = fopen($filename, "r")) !== FALSE) {
        
        // 1. Đọc dòng tiêu đề (Header)
        $file_headers = fgetcsv($handle, 1000, $delimiter);
        
        // Loại bỏ các ký tự đặc biệt/ẩn (như BOM) thường gây lỗi ở cột đầu tiên
        if (isset($file_headers[0])) {
            $file_headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $file_headers[0]); 
        }

        // 2. Chuẩn hóa tiêu đề gốc thành chữ thường để dễ dàng ánh xạ
        $normalized_headers = array_map('strtolower', $file_headers);
        
        // 3. Tìm vị trí của các cột cần thiết trong tệp
        foreach ($display_map as $key => $display_name) {
            $index = array_search(strtolower($key), $normalized_headers);
            if ($index !== FALSE) {
                $header_indices[$key] = $index;
            }
            // Bỏ qua các cột không tìm thấy trong file
        }
        
        // 4. Đọc dữ liệu từng dòng
        if (empty($error_message)) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $filtered_row = [];
                
                // Lọc dữ liệu theo header_indices và $display_map
                foreach ($display_map as $key => $display_name) {
                    $index = $header_indices[$key] ?? null;
                    if ($index !== null && isset($row[$index])) {
                        // Lưu dữ liệu vào mảng bằng tên key (username, password, ...)
                        $filtered_row[$key] = $row[$index];
                    } else if ($index !== null) {
                         // Nếu cột tồn tại nhưng dữ liệu trống
                         $filtered_row[$key] = ''; 
                    }
                    // Bỏ qua nếu cột không được yêu cầu trong display_map
                }

                if (!empty($filtered_row)) {
                    $data[] = $filtered_row;
                }
            }
        }
        
        fclose($handle);
    } else {
        $error_message = "Lỗi: Không thể mở tệp $filename. Vui lòng kiểm tra quyền truy cập.";
    }
} else {
    $error_message = "Lỗi: Không tìm thấy tệp $filename. Vui lòng đặt đúng tên file.";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Tài Khoản (CSV)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tùy chỉnh nhỏ */
        body { background-color: #f8f9fa; }
        .container { max-width: 1200px; }
        .table-responsive { max-height: 70vh; overflow-y: auto; }
        .table thead th { position: sticky; top: 0; background-color: #343a40; z-index: 10; }
        .text-blue { color: #007bff !important; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-blue">Danh sách tài khoản (Từ file CSV)</h2>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php elseif (!empty($data)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <?php 
                        // Hiển thị tiêu đề theo thứ tự mong muốn từ $display_map
                        foreach ($display_map as $key => $display_name): 
                            // Chỉ hiển thị tiêu đề nếu cột đó thực sự tồn tại trong file
                            if (isset($header_indices[$key])): 
                        ?>
                            <th><?php echo htmlspecialchars($display_name); ?></th>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $student): ?>
                        <tr>
                            <?php 
                            // Hiển thị dữ liệu theo đúng thứ tự của $display_map
                            foreach ($display_map as $key => $display_name): 
                                if (isset($header_indices[$key])): 
                            ?>
                                <td><?php echo htmlspecialchars($student[$key] ?? ''); ?></td>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="alert alert-info mt-3">
            <strong>Tổng số dòng dữ liệu:</strong> <?php echo count($data); ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Không có dữ liệu hợp lệ hoặc lỗi đọc file.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>