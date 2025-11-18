<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHT Chương 2 - PHP Căn Bản</title>
</head>
<body>
    <h1>Kết quả PHP Căn Bản</h1>
    <?php 

    //todo 1
        $ho_ten = "Vũ Bá Đài <br>"; 
        $diem_tb = 9.0;
        $co_di_hoc_chuyen_can = true;
    //todo 2 
        echo "Họ tên : $ho_ten", "Điểm : $diem_tb";
    //todo3
        if($diem_tb >= 8.5 && $co_di_hoc_chuyen_can == true){
            echo "<br> Xếp loại: giỏi";
        } else if ($diem_tb >= 6.5 && $diem_tb < 8.0 && $co_di_hoc_chuyen_can == true){
            echo "<br> Xếp loại: khá";
        }
        else if ($diem_tb >= 5.0 && $diem_tb < 6.5 && $co_di_hoc_chuyen_can == true){
            echo "<br> Xếp loại: trung bình";
        } else {
            echo "<br> Xếp loại: yếu (Cần cố gắng thêm!";
        }

    //todo 4
        function chao_Mung(){
            echo "<br> Chúc mừng bạn đã hoàn thành PHT Chương 2!";
        }
    //todo 5
        chao_Mung();
     ?>
    
</body>
</html>