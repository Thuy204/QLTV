<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Quản lý người dùng</title>
    <style>
        .box h2 {
            float: left; 
            margin: 10px; 
        }
        .box form {
            float: right;
            margin: 10px;
        }
    </style>
</head>
<body>
    <?php
        include '../view/head.php';
        include '../config/db.php';
    ?>
    <div class="container">
        <div class="box">
            <h2>DANH SÁCH NGƯỜI DÙNG</h2>
            <form action="search_nguoidung.php" method="POST">
                <div class="row">
                    <div class="col">
                        <input type="text" placeholder="Search" name="tim_nguoidung" class="form-control">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary" name="timkiem">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT * FROM nguoidung";
                    $result = mysqli_query($conn, $query);
                    $num = 1;

                    if (!$result) {
                        echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                            <tr>
                                <td>" . ($num++) . "</td>
                                <td>" . $row['id_nguoidung'] . "</td>
                                <td>" . $row['ten_nguoidung'] . "</td>
                                <td>" . $row['email_nguoidung'] . "</td>
                                <td>" . ($row['vaitro_nguoidung'] == '1' ? 'Nhân viên' : 'Sinh viên') . "</td>
                                <td><a href='edit_nguoidung.php?id=" . $row['id_nguoidung'] . "' class='btn btn-success'>Sửa</a></td>
                                <td><a onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\");' 
                                       href='delete_nguoidung.php?id=" . $row['id_nguoidung'] . "' class='btn btn-danger'>Xóa</a></td>
                            </tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Thêm mới</button>
        <form action="create_nguoidung.php" method="POST">
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Thêm mới người dùng</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên người dùng</label>
                                <input type="text" name="ten_nguoidung" placeholder="Nhập tên" class="form-control" required>
                                <label>Email</label>
                                <input type="email" name="email_nguoidung" placeholder="Nhập email" class="form-control" required>
                                <label>Mật khẩu</label>
                                <input type="password" name="matkhau_nguoidung" placeholder="Nhập mật khẩu" class="form-control" required>
                                <label>Vai trò</label>
                                <select name="vaitro_nguoidung" class="form-control" required>
                                    <option value="1">Nhân viên</option>
                                    <option value="0">Sinh viên</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <input type="submit" class="btn btn-success" name="them_nguoidung" value="Thêm mới">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
      // Hàm load danh sách người dùng
function load_nguoidung() {
    fetch('http://localhost/QLTV/controller/qlynguoidung_controller.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById('nguoidung_table');
            tableBody.innerHTML = ''; // Xóa dữ liệu cũ

            if (!data.data || !Array.isArray(data.data)) {
                console.error('Dữ liệu trả về không phải là mảng');
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Lỗi dữ liệu</td></tr>';
                return;
            }

            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>';
            } else {
                console.log(data);
                data.data.forEach(nguoidung => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${nguoidung.id_nguoidung}</td>
                        <td>${nguoidung.ten_nguoidung}</td>
                        <td>${nguoidung.email_nguoidung}</td>
                        <td>${nguoidung.matkhau_nguoidung}</td>
                        <td>${nguoidung.vaitro_nguoidung === '1' ? 'Nhân viên' : 'Sinh viên'}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="edit_nguoidung(${nguoidung.id_nguoidung})">Sửa</button>
                            <button class="btn btn-danger btn-sm" onclick="delete_nguoidung(${nguoidung.id_nguoidung})">Xóa</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
        });
}

// Hàm xử lý khi submit form thêm người dùng
document.getElementById('addForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngừng hành động mặc định (reload trang)

    // Tạo đối tượng FormData để gửi dữ liệu
    const formData = new FormData();
    formData.append('ten_nguoidung', document.getElementById('ten_nguoidung').value);
    formData.append('email_nguoidung', document.getElementById('email_nguoidung').value);
    formData.append('matkhau_nguoidung', document.getElementById('matkhau_nguoidung').value);
    formData.append('vaitro_nguoidung', document.querySelector('input[name="vaitro_nguoidung"]:checked')?.value);

    // Gửi dữ liệu đến server qua fetch API
    fetch('http://localhost/QLTV/controller/qlynguoidung_controller.php', {
        method: 'POST',
        body: formData, // Dữ liệu form
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 201) {
            $('#addModal').modal('hide');  // Đóng modal sau khi thêm thành công
            load_nguoidung(); // Tải lại danh sách người dùng
        } else {
            alert(data.message); // Thông báo lỗi
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
    });
});

// Gọi hàm load_nguoidung khi trang được tải
window.onload = load_nguoidung;

    </script>
</body>
</html>
