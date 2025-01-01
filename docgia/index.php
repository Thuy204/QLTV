
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
                </tr>
            </thead>
            <tbody id="docgia_table">
            </tbody>
        </table>
    </div>

    <script>
        function load_docgia() {
            fetch('http://localhost/QLTV/controller/qlydocgia_controller.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById('docgia_table');
                    tableBody.innerHTML = ''; // Xóa dữ liệu cũ
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
                    } else {
                        data.forEach(docgia => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${docgia.docgia_id}</td>
                                <td>${docgia.ten_docgia}</td>
                                <td>${docgia.tuoi_docgia}</td>
                                <td>${docgia.gioitinh_docgia === 1 ? 'Nam' : 'Nữ'}</td>
                                <td>${docgia.sdt_docgia}</td>
                                <td><img src='../img/docgia/${docgia.hinhanh_docgia}' alt='img' width='50'></td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        }

        // Gọi hàm load_docgia khi trang được tải
        window.onload = load_docgia;
    </script>
</body>
</html>
