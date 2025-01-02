<?php
    include '../view/head.php';
    include '../config/db.php';
?>
<?php
    if(isset($_POST['timkiem'])){
        $timnguoidung = $_POST['tim_nguoidung'];
    }
?>
<div class="container">
    <h4>Kết quả tìm kiếm người dùng:</h4>
    <table class="table table-striped table-hover">
        <thead>
            <th>STT</th>
            <th>ID Người Dùng</th>
            <th>Tên Người Dùng</th>
            <th>Email</th>
            <th>Vai trò</th>
        </thead>
        <tbody>
<?php
    // Truy vấn tìm kiếm theo tên người dùng hoặc email
    $query = "SELECT * FROM nguoidung WHERE ten_nguoidung LIKE '%$timnguoidung%' OR email_nguoidung LIKE '%$timnguoidung%'";
    $result = mysqli_query($conn, $query);
    
    if(!$result){
        echo "Không tìm thấy thông tin người dùng!";
    } else {
        $num = 1;
        while($row = mysqli_fetch_array($result)){
            echo "
                <tr>
                    <td>" . ($num++) . "</td>
                    <td>" . $row["id_nguoidung"] . "</td>
                    <td>" . $row["ten_nguoidung"] . "</td>
                    <td>" . $row["email_nguoidung"] . "</td>
                    <td>" . ($row["vaitro_nguoidung"] == 'nhanvien' ? 'Nhân viên' : 'Sinh viên') . "</td>
                </tr>";
        }
        echo "</tbody>
            </table>";
    }
?>
<a href="index.php" class="btn btn-warning">Quay lại</a>
</div>
