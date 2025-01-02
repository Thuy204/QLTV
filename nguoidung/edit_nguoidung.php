<?php 
require_once '../config/db.php'; 

// Khởi tạo session để kiểm tra người dùng đã đăng nhập chưa
session_start();
if (!isset($_SESSION['id_nguoidung'])) {
    header("Location: login.php");
    exit();
}

$id_nguoidung = $_SESSION['id_nguoidung'];  // Lấy ID người dùng từ session

// Kiểm tra xem có ID người dùng nào được truyền vào URL hay không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn thông tin người dùng theo ID
    $query = "SELECT * FROM nguoidung WHERE id_nguoidung = '$id' AND id_nguoidung = '$id_nguoidung'";  // Kiểm tra người dùng có phải chủ sở hữu
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        // Nếu không tìm thấy người dùng hoặc người dùng không phải chủ sở hữu
        echo "Bạn không có quyền sửa thông tin này.";
        exit;
    } else {
        $row = mysqli_fetch_assoc($result); // Lấy dữ liệu người dùng từ cơ sở dữ liệu
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin người dùng</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <div class="edit">
        <div class="container" style="justify-content: center;">
            <form method="POST">
                <h3 class="text-center mt-2 mb-3">Sửa thông tin người dùng</h3>
                <div class="mt-2 mb-3">
                    <label>ID Người Dùng</label>
                    <input type="number" name="id_nguoidung" class="form-control" value="<?php echo isset($row['id_nguoidung']) ? $row['id_nguoidung'] : ''; ?>" disabled>
                </div>
                <div class="mt-2 mb-3">
                    <label>Tên Người Dùng</label>
                    <input type="text" name="ten_nguoidung" class="form-control" value="<?php echo isset($row['ten_nguoidung']) ? $row['ten_nguoidung'] : ''; ?>" required>
                </div>
                <div class="mt-2 mb-3">
                    <label>Email</label>
                    <input type="email" name="email_nguoidung" class="form-control" value="<?php echo isset($row['email_nguoidung']) ? $row['email_nguoidung'] : ''; ?>" required>
                </div>
                <div class="mt-2 mb-3">
                    <label>Mật khẩu</label>
                    <input type="password" name="matkhau_nguoidung" class="form-control" value="<?php echo isset($row['matkhau_nguoidung']) ? $row['matkhau_nguoidung'] : ''; ?>" required>
                </div>
                <div class="mt-2 mb-3">
                    <label>Vai trò</label>
                    <div class="radio">
                        <label style="margin-left: 10px;">
                            <input type="radio" name="vaitro_nguoidung" value="nhanvien" <?php echo ($row['vaitro_nguoidung'] == 'nhanvien') ? 'checked' : ''; ?>> Nhân viên
                        </label>
                        <label style="margin: 10px;">
                            <input type="radio" name="vaitro_nguoidung" value="sinhvien" <?php echo ($row['vaitro_nguoidung'] == 'sinhvien') ? 'checked' : ''; ?>> Sinh viên
                        </label>
                    </div>
                </div>
                <div class="text-center mb-2">
                    <a href="index.php" class="btn btn-secondary" style="margin-right: 200px;">HỦY</a>
                    <input type="submit" class="btn btn-success" name="sua_nguoidung" value="Cập nhật">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php    
if (isset($_POST['sua_nguoidung'])) {
    $ten_nguoidung = $_POST['ten_nguoidung'];
    $email_nguoidung = $_POST['email_nguoidung'];
    $matkhau_nguoidung = $_POST['matkhau_nguoidung'];
    $vaitro_nguoidung = $_POST['vaitro_nguoidung'];
    
    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $matkhau_hash = password_hash($matkhau_nguoidung, PASSWORD_DEFAULT);
    
    // Cập nhật thông tin người dùng
    $query = "UPDATE nguoidung SET ten_nguoidung = '$ten_nguoidung', email_nguoidung = '$email_nguoidung', matkhau_nguoidung = '$matkhau_hash', vaitro_nguoidung = '$vaitro_nguoidung' WHERE id_nguoidung = '$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Lỗi cập nhật thông tin người dùng.";
    } else {
        echo "<script type='text/javascript'>
            alert('Cập nhật dữ liệu thành công!');
            window.location.href='index.php';
            </script>";
    }
}
?>
