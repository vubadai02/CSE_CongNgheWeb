<?php
// Đường dẫn file
$filename = 'Quiz.txt';

// Mảng chứa danh sách câu hỏi sau khi xử lý
$questions = [];

// --- LOGIC ĐỌC VÀ XỬ LÝ FILE DỮ LIỆU ---
if (file_exists($filename)) {
    // Đọc file vào mảng, mỗi dòng là 1 phần tử
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    $current_question = [
        'text' => '',
        'options' => [],
        'answer' => ''
    ];

    foreach ($lines as $line) {
        $line = trim($line);

        if (empty($line)) continue;

        // KIỂM TRA: Nếu dòng bắt đầu bằng "ANSWER:" -> Kết thúc 1 câu hỏi
        if (strpos($line, 'ANSWER:') === 0) {
            $answer_raw = trim(str_replace('ANSWER:', '', $line));
            // Lấy đáp án (chuẩn hóa thành chữ hoa, chỉ lấy ký tự đầu tiên nếu có nhiều đáp án)
            $current_question['answer'] = strtoupper(trim(explode(',', $answer_raw)[0]));
            
            // Lưu câu hỏi hoàn chỉnh vào danh sách
            if (!empty($current_question['text'])) {
                 $questions[] = $current_question;
            }
            
            // Reset biến tạm để chứa câu hỏi mới
            $current_question = [
                'text' => '',
                'options' => [],
                'answer' => ''
            ];
        } 
        // KIỂM TRA: Nếu dòng bắt đầu bằng A., B., C., D. -> Là lựa chọn
        elseif (preg_match('/^[A-D]\./', $line)) {
            $current_question['options'][] = $line;
        } 
        // CÒN LẠI: Là nội dung câu hỏi
        else {
            $current_question['text'] .= $line . " ";
        }
    }
} else {
    die("Không tìm thấy file Quiz.txt");
}

// --- LOGIC XỬ LÝ KẾT QUẢ ---
$is_submitted = isset($_POST['submitted']);
$user_score = 0;
$total_questions = count($questions);

if ($is_submitted) {
    foreach ($questions as $index => $q) {
        $question_id = "q_" . $index;
        $user_answer = isset($_POST[$question_id]) ? $_POST[$question_id] : null;
        
        if ($user_answer && strtoupper($user_answer) === $q['answer']) {
            $user_score++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm Android</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #007bff; margin-bottom: 30px; }
        .quiz-item { 
            margin-bottom: 25px; 
            border: 1px solid #ddd; 
            border-radius: 6px;
            padding: 15px;
            transition: background 0.3s;
        }
        .question-title { 
            font-weight: bold; 
            font-size: 1.1em; 
            color: #333; 
            margin-bottom: 10px; 
        }
        .options label { 
            display: block; 
            margin-bottom: 8px; 
            cursor: pointer; 
            padding: 5px; 
            border-radius: 4px; 
            transition: background 0.2s; 
        }
        .options label:hover { background-color: #e9ecef; }
        
        /* Màu sắc kết quả */
        .correct { background-color: #d4edda; border: 1px solid #28a745; } /* Xanh lá */
        .incorrect { background-color: #f8d7da; border: 1px solid #dc3545; } /* Đỏ */

        /* Hiển thị đáp án đúng */
        .correct-answer-text { color: #28a745; font-weight: bold; margin-top: 10px; } 
        
        /* Nút */
        .btn-check { 
            background-color: #007bff; 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            display: block; 
            margin: 20px auto 10px; 
            font-size: 16px; 
            width: 100%;
            max-width: 300px;
        }
        .btn-check:hover:not(:disabled) { background-color: #0056b3; }
        .btn-check:disabled { background-color: #6c757d; cursor: not-allowed; }

        .btn-reset { 
            background-color: #03e825ff; 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            display: block; 
            margin: 10px auto 20px; 
            font-size: 16px; 
            width: 100%;
            max-width: 300px;
            text-align: center;
            text-decoration: none;
        }
        .btn-reset:hover { background-color: #1c86d6ff; }
        
        /* Hiển thị điểm */
        .score-display { 
            text-align: center; 
            font-size: 1.5em; 
            font-weight: bold; 
            color: #007bff; 
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bài Thi Trắc Nghiệm Android</h1>

    <?php if ($is_submitted): ?>
        <div class="score-display">
            Bạn đã trả lời đúng <?php echo $user_score; ?> câu/ <?php echo $total_questions; ?> câu.
        </div>
    <?php endif; ?>

    <form id="quizForm" method="POST" action="">
        <input type="hidden" name="submitted" value="1">
        <?php foreach ($questions as $index => $q): 
            $question_id = "q_" . $index;
            // Lấy đáp án của người dùng từ POST nếu đã nộp bài
            $user_answer = $is_submitted ? (isset($_POST[$question_id]) ? strtoupper($_POST[$question_id]) : null) : null;
            
            // So sánh đáp án
            $is_correct = ($user_answer === $q['answer']);
            
            // Xác định class tô màu
            $item_class = '';
            if ($is_submitted) {
                $item_class = $is_correct ? 'correct' : 'incorrect';
            }
        ?>
            <div class="quiz-item <?php echo $item_class; ?>">
                <div class="question-title">
                    Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars(trim($q['text'])); ?>
                </div>
                
                <div class="options">
                    <?php foreach ($q['options'] as $opt): 
                        $option_value = substr($opt, 0, 1);
                        $is_checked = ($user_answer === $option_value);
                        $is_disabled = $is_submitted ? 'disabled' : '';
                        
                        // Đánh dấu đáp án đúng trong chế độ xem kết quả
                        $label_class = ($is_submitted && $option_value === $q['answer']) ? 'correct-answer-text' : '';
                    ?>
                        <label class="<?php echo $label_class; ?>">
                            <input 
                                type="radio" 
                                name="<?php echo $question_id; ?>" 
                                value="<?php echo $option_value; ?>"
                                <?php echo $is_checked ? 'checked' : ''; ?>
                                <?php echo $is_disabled; ?>
                            > 
                            <?php echo htmlspecialchars($opt); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($is_submitted && !$is_correct): ?>
                    <div class="correct-answer-text">
                        ❌ Đáp án đúng là: <?php echo $q['answer']; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button 
            type="button" 
            id="submitButton" 
            class="btn-check" 
            <?php echo $is_submitted ? 'disabled' : ''; ?>
        >
            Nộp bài & Xem kết quả
        </button>
    </form>
    
    <?php if ($is_submitted): ?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-reset">Làm lại bài thi</a>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quizForm');
        const submitButton = document.getElementById('submitButton');
        const isSubmitted = <?php echo json_encode($is_submitted); ?>;
        
        if (isSubmitted) {
            // Dừng logic nếu đã nộp bài
            return; 
        }

        submitButton.addEventListener('click', function() {
            // Hiển thị hộp thoại xác nhận (OK hoặc Hủy)
            const confirmation = confirm("Bạn có chắc chắn muốn nộp bài?");

            if (confirmation) {
                // Nếu chọn OK, gửi form (bao gồm input hidden 'submitted' = 1)
                form.submit();
            }
            // Nếu chọn Hủy, không làm gì cả
        });
    });
</script>

</body>
</html>