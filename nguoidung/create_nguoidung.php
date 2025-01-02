<?php
require_once '../config/db.php';

if (isset($_POST['them_nguoidung'])) {
    $id = $_POST['id_nguoidung']; 
    $ten = $_POST['ten_nguoidung']; 
    $email = $_POST['email_nguoidung'];
    $matkhau = $_POST['matkhau_nguoidung'];  
    $vaitro = $_POST['vaitro_nguoidung']; 
    
    if ($id == "" || empty($id)) {
        header('Location:index.php');
        exit();
    } else {
        // Kiểm tra email có hợp lệ không
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Email không hợp lệ';
            exit();
        }

        // Thực thi câu lệnh INSERT vào bảng người dùng
        $query = "INSERT INTO nguoidung (id_nguoidung, ten_nguoidung, email_nguoidung, matkhau_nguoidung, vaitro_nguoidung) 
                  VALUES ('$id', '$ten', '$email', '$matkhau', '$vaitro')";
        
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            echo 'Dữ liệu không hợp lệ';
        } else {
            header('Location: index.php');
        }
    }
}
?>
