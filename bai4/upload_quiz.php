<?php
include 'db.php';

$message = "";

if (isset($_POST['submit'])) {
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
        $lines = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $current_question = ['text' => '', 'options' => []];
        $count = 0;
        try {
            $sql = "INSERT INTO questions (question_content, option_a, option_b, option_c, option_d, correct_answer) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($lines as $line) {
                if (empty($line)) continue;
                if (strpos($line, 'ANSWER:') === 0) {
                    $ans = trim(str_replace('ANSWER:', '', $line));
                    $optA = $current_question['options'][0] ?? '';
                    $optB = $current_question['options'][1] ?? '';
                    $optC = $current_question['options'][2] ?? '';
                    $optD = $current_question['options'][3] ?? '';
                    $stmt->execute([
                        $current_question['text'], 
                        $optA, 
                        $optB, 
                        $optC, 
                        $optD, 
                        $ans
                    ]);
                    $count++;
                    $current_question = ['text' => '', 'options' => []];
                } 
                elseif (preg_match('/^[A-D]\./', $line)) {
                    $current_question['options'][] = $line;
                } 
                else {
                    $current_question['text'] .= $line . " ";
                }
            }
            $message = "Thành công! Đã thêm $count câu hỏi bằng PDO.";

        } catch (PDOException $e) {
            $message = "Lỗi khi lưu dữ liệu: " . $e->getMessage();
        }

    } else {
        $message = "Vui lòng chọn file .txt hợp lệ.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload Quiz (PDO)</title>
    <style>body { font-family: sans-serif; padding: 20px; }</style>
</head>
<body>
    <h1>Upload Quiz (Phiên bản PDO)</h1>
    <?php if($message) echo "<h3 style='color:blue'>$message</h3>"; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Chọn file Quiz.txt:</label>
        <input type="file" name="fileUpload" accept=".txt" required>
        <br><br>
        <button type="submit" name="submit">Upload & Lưu CSDL</button>
    </form>
</body>
</html>