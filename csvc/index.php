<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Quản lý cơ sở vật chất</title>
    <style>
        .box h2{
            float:left; 
            margin: 10px; 
        }
        .box form{
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
    <div class="container" >
        <div class="box">
            <h2>DANH SÁCH CƠ SỞ VẬT CHẤT</h2>
            <form action="search_csvc.php" method="POST">
            <div class="row"><script type="module"></script>
                <div class="col">
                    <input type="text" placeholder="Search" name="tim_csvc">
                </div>
                <div class="col">
                    <button class="btn btn-primary" name="timkiem">Tìm kiếm</button>
                </div>
            </div>
            </form>
        </div>
    <table class="table table-striped table-hover">
        <thead>
            <th>STT</th>
            <th>Mã</th>
            <th>Tên</th>
            <th>Số lượng</th>
            <th>Tình trạng</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </thead>
        <tbody>
            <?php
                $query="SELECT* FROM cosovatchat";
                $result= mysqli_query($conn, $query);
                $num=1;
                if(!$result){
                    echo "Không có dữ liệu ";
                }else{
                    while($row= mysqli_fetch_assoc($result)){
                        echo"
                        <tr>
                        <td>".($num++)."</td>
                        <td>".$row["csvc_id"]."</td>
                        <td>".$row["ten_csvc"]."</td>
                        <td>".$row["soluong_csvc"]."</td>
                        <td>".($row["tinhtrang_csvc"] == 1 ? 'Mới' : 'Cũ')."</td>
                        <td><a href='edit_csvc.php?id=".$row["csvc_id"]."' class='btn btn-success'>Sửa</a></td>
                        <td><a onclick ='return confirm(\"Bạn có chắc chắn muốn xóa không?\");' 
                        href='delete_csvc.php?id=".$row['csvc_id']."' class='btn btn-danger'>Xóa</a></td>
                        </tr>";


                    }
                    echo "</tbody>
                            </table>";
                }
            ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Thêm mới</button>
            <form action="create_csvc.php" method="POST">
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Thêm mới cơ sở vật chất</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            <div class="form-group">
                                <label>ID</label>
                                <input type="number" name="id_csvc" placeholder="Enter ID" class="form-control" required>
                                <label>Tên CSVC</label>
                                <input type="text" name="ten_csvc" placeholder="Enter Name" class="form-control" required>
                                <label>Số Lượng</label>
                                <input type="number" name="sl_csvc" placeholder="Enter number" class="form-control" required>
                                <label>Tình Trạng</label>
                                <div class="radio">
                                        <label><input type="radio" name="tt_csvc" checked="checked" value="1">Mới</label>
                                        <label><input type="radio" name="tt_csvc" value="0">Cũ</label>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <input type="submit" class="btn btn-success" name="them_csvc" value="Thêm mới">
                    </div>
                    </div>
                </div>
                </div>
                </form>
            
    </div>  
</body>
</html>