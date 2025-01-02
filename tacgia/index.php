<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tác giả</title>
    <!-- Liên kết Bootstrap CSS từ CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Script jQuery từ CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <!-- Script Popper.js từ CDN -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Liên kết Bootstrap JS từ CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <!-- CSS tùy chỉnh -->
    <style>
        .box h2 {
            float: left;
            margin: 10px;
        }
        .box form {
            float: right;
            margin: 10px;
        }
        .img {
            width: 5rem;
            height: 6rem;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Bao gồm phần header và cấu hình -->
    <?php include '../view/head.php'; ?>
    <?php include '../config/db.php'; ?>

    <div class="container">
        <div class="box">
            <h2>DANH SÁCH TÁC GIẢ</h2>
            <!-- Form tìm kiếm -->
            <form action="search.php" method="POST">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" name="tim_tacgia">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary" type="submit" name="timkiem">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Giới tính</th>
                    <th>Thông tin</th>
                    <th>Hình ảnh</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Truy vấn SQL để lấy danh sách tác giả
                    $query = "SELECT * FROM tacgia";
                    $result = mysqli_query($conn, $query);
                    $num = 1;

                    // Kiểm tra và hiển thị dữ liệu từ cơ sở dữ liệu
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                            <tr>
                                <td>".$num++."</td>
                                <td>".$row["tacgia_id"]."</td>
                                <td>".$row["ten_tacgia"]."</td>
                                <td>".($row["gioitinh_tacgia"] == 1 ? 'Nam' : 'Nữ')."</td>
                                <td>".$row["thongtin_tacgia"]."</td>
                                <td><img src='../img/tacgia/".$row["hinhanh_tacgia"]."' alt='img' width='50'></td>
                                <td><a href='edit.php?id=".$row["tacgia_id"]."' class='btn btn-success'>Sửa</a></td>
                                <td><a onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\");' href='delete.php?id=".$row['tacgia_id']."' class='btn btn-danger'>Xóa</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <!-- Nút mở Modal để thêm mới tác giả -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
            Thêm mới tác giả
        </button>

        
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Thêm mới tác giả</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form thêm mới tác giả -->
                        <form action="create.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="tacgia_id">ID</label>
                                <input type="number" class="form-control" id="tacgia_id" name="tacgia_id" placeholder="Nhập ID">
                            </div>
                            <div class="form-group">
                                <label for="tacgia_img">Hình ảnh</label>
                                <input type="file" class="form-control-file" id="tacgia_img" name="tacgia_img">
                            </div>
                            <div class="form-group">
                                <label for="ten_tacgia">Tên</label>
                                <input type="text" class="form-control" id="ten_tacgia" name="ten_tacgia" placeholder="Nhập Tên">
                            </div>
                            <div class="form-group">
                                <label>Giới tính</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gioitinh_tacgia" id="gioitinh_nam" value="1" checked>
                                    <label class="form-check-label" for="gioitinh_nam">Nam</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gioitinh_tacgia" id="gioitinh_nu" value="0">
                                    <label class="form-check-label" for="gioitinh_nu">Nữ</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="thongtin_tacgia">Thông tin</label>
                                <input type="text" class="form-control" id="thongtin_tacgia" name="thongtin_tacgia" placeholder="Nhập Thông tin">
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success" name="them_tacgia">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<script>
    // Hàm load dữ liệu tác giả
    function loadAuthors() {
        fetch('http://localhost/QLTV/controller/qlytacgia_controller.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('tacgia_table');
                tableBody.innerHTML = ''; // Xóa dữ liệu cũ

                if (data.status === 200) {
                    data.data.forEach((author, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${author.tacgia_id}</td>
                            <td>${author.ten_tacgia}</td>
                            <td>${author.gioitinh_tacgia === 1 ? 'Nam' : 'Nữ'}</td>
                            <td>${author.thongtin_tacgia}</td>
                            <td><img src='../img/tacgia/${author.hinhanh_tacgia}' alt='img' width='50'></td>
                            <td><button class="btn btn-warning btn-sm">Sửa</button></td>
                            <td><button class="btn btn-danger btn-sm">Xóa</button></td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="8">Không có dữ liệu</td></tr>';
                }
            })
            .catch(error => console.error('Lỗi khi tải dữ liệu:', error));
    }

    // Hàm xử lý khi submit form thêm tác giả
    document.getElementById('addAuthorForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Ngừng hành động mặc định (reload trang)

        // Tạo đối tượng FormData để gửi dữ liệu
        const formData = new FormData();
        formData.append('ten_tacgia', document.getElementById('ten_tacgia').value);
        formData.append('gioitinh_tacgia', document.querySelector('input[name="gioitinh_tacgia"]:checked').value);
        formData.append('thongtin_tacgia', document.getElementById('thongtin_tacgia').value);
        formData.append('hinhanh_tacgia', document.getElementById('hinhanh_tacgia').files[0]);

        // Gửi dữ liệu đến server qua fetch API
        fetch('http://localhost/QLTV/controller/qlytacgia_controller.php', {
            method: 'POST',
            body: formData, // Dữ liệu form
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 201) {
                $('#exampleModalCenter').modal('hide');  // Đóng modal sau khi thêm thành công
                loadAuthors(); // Tải lại danh sách tác giả
            } else {
                alert(data.message); // Thông báo lỗi
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
        });
    });

    // Gọi hàm loadAuthors khi trang được tải
    window.onload = loadAuthors;
</script>
