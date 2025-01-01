<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Quản lý độc giả</title>
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
    <?php
        include '../view/head.php';
        include '../config/db.php';
        include '../model/qlydocgia_model.php';
    ?>
    <div class="container">
        <div class="box">
            <h2>DANH SÁCH ĐỘC GIẢ</h2>
            <form action="search_docgia.php" method="POST">
                <div class="row">
                    <div class="col">
                        <input type="text" placeholder="Search" name="tim_docgia" class="form-control">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary" name="timkiem">Tìm kiếm</button>
                    </div>
                </div>
            </form>
            <button class="btn btn-success mt-3" data-toggle="modal" data-target="#addModal">Thêm mới độc giả</button>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>SĐT</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="docgia_table">
            </tbody>
        </table>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm mới độc giả</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="ten_docgia">Tên độc giả</label>
                            <input type="text" class="form-control" id="ten_docgia" required>
                        </div>
                        <div class="form-group">
                            <label for="tuoi_docgia">Tuổi</label>
                            <input type="number" class="form-control" id="tuoi_docgia" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_gioitinh_docgia">Giới tính</label>
                            <div>
                                <label>
                                <input type="radio" name="gioitinh_docgia" value="0" id="edit_gioitinh_docgia_nam">
                                    Nam
                                
                                </label>
                                <label>
                                <input type="radio" name="gioitinh_docgia" value="1" id="edit_gioitinh_docgia_nu">
                                    Nữ
                                   
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sdt_docgia">SĐT</label>
                            <input type="text" class="form-control" id="sdt_docgia" required>
                        </div>
                        <div class="form-group">
                            <label for="hinhanh_docgia">Hình ảnh</label>
                            <input type="file" class="form-control" id="hinhanh_docgia" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm độc giả</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
// Hàm load dữ liệu độc giả
function load_docgia() {
    fetch('http://localhost/QLTV/controller/qlydocgia_controller.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ${response.status}');
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById('docgia_table');
            tableBody.innerHTML = ''; // Xóa dữ liệu cũ

            if (!data.data || !Array.isArray(data.data)) {
                console.error('Dữ liệu trả về không phải là mảng');
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Lỗi dữ liệu</td></tr>';
                return;
            }

            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
            } else {
                data.data.forEach(docgia => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${docgia.docgia_id}</td>
                        <td>${docgia.ten_docgia}</td>
                        <td>${docgia.tuoi_docgia}</td>
                      <td>${docgia.gioitinh_docgia === 0 ? 'Nam' : 'Nữ'}</td>
                        <td>${docgia.sdt_docgia}</td>
                        <td><img src='../img/docgia/${docgia.hinhanh_docgia}' alt='img' width='50'></td>
                       <td>
    <button class="btn btn-warning btn-sm" onclick="edit_docgia(${docgia.docgia_id})">Sửa</button>
    <button class="btn btn-danger btn-sm" onclick="delete_docgia(${docgia.docgia_id})">Xóa</button>
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

// Hàm xử lý khi submit form thêm độc giả
document.getElementById('addForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngừng hành động mặc định (reload trang)

    // Tạo đối tượng FormData để gửi dữ liệu
    const formData = new FormData();
    formData.append('ten_docgia', document.getElementById('ten_docgia').value);
    formData.append('tuoi_docgia', document.getElementById('tuoi_docgia').value);

    // Lấy giá trị giới tính từ các radio button
    const gioitinh = document.querySelector('input[name="gioitinh_docgia"]:checked')?.value;
    formData.append('gioitinh_docgia', gioitinh); // Kiểm tra giá trị

    formData.append('sdt_docgia', document.getElementById('sdt_docgia').value);
    formData.append('hinhanh_docgia', document.getElementById('hinhanh_docgia').files[0]);

    // Gửi dữ liệu đến server qua fetch API
    fetch('http://localhost/QLTV/controller/qlydocgia_controller.php', {
        method: 'POST',
        body: formData, // Dữ liệu form
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 201) {
            $('#addModal').modal('hide');  // Đóng modal sau khi thêm thành công
            window.location.reload(); // Reload lại trang
        } else {
            alert(data.message); // Thông báo lỗi
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
    });
});
// Gọi hàm load_docgia khi trang được tải
window.onload = load_docgia;
</script>

</body>
</html>

</body>
</html>