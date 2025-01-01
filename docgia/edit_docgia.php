<?php
        require_once '../config/db.php';
?>
<?php
        if(isset($_GET['id'])){
            $id =$_GET['id'];
        
                $query="SELECT* FROM docgia WHERE docgia_id= '$id'";

                $result= mysqli_query($conn, $query);
                if(!$result){
                    echo "Không có dữ liệu";
                }else{
                    $row= mysqli_fetch_assoc($result);   
                }       
        }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin đọco giả</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    <link rel="stylesheet" href="../view/style.css">

</head>
<body>
<div class="edit">
    <div class="container" style="justify-content: center;" >
    <form method="POST" enctype="multipart/form-data">
    <h3 class="text-center mt-2 mb-3">Sửa thông tin độc giả</h3>
        <div class="form-group">
                <label>ID</label>
                    <input type="number" name="docgia_id" placeholder="Enter ID" class="form-control" 
                    value="<?php echo isset($row['docgia_id']) ? $row['docgia_id'] : ''; ?>" disabled>
            </div>
            <div class="mt-2 mb-3">
                <label>Img</label>
                    <input type="file" name="docgia_img" placeholder="Enter Img" class="form-control"
                    value="<?php echo isset($row['hinhanh_docgia']) ? $row['hinhanh_docgia'] : ''; ?>" required>
            </div>
            <div class="mt-2 mb-3">
                <label>Name</label>
                    <input type="text" name="ten_docgia" placeholder="Enter Name" class="form-control"
                    value="<?php echo isset($row['ten_docgia']) ? $row['ten_docgia'] : ''; ?>" required>
            </div>
            <div class="mt-2 mb-3">
                    <label>Age</label>
                    <input type="number" name="tuoi_docgia" placeholder="Enter Age" class="form-control"
                    value="<?php echo isset($row['tuoi_docgia']) ? $row['tuoi_docgia'] : ''; ?>" required >
            </div>
            <div class="mt-2 mb-3">
                    <label>Gender</label>
                <div class="radio">
                    <label><input type="radio" name="gioitinh_docgia" checked="checked" value="1">Nam</label>
                    <label><input type="radio" name="gioitinh_docgia" value="0">Nữ</label>
                </div>
            </div>
            <div class="mt-2 mb-3">
                <label>SDT</label>
                    <input type="phone" name="sdt_docgia" placeholder="Enter Phone" class="form-control"
                    value="<?php echo isset($row['sdt_docgia']) ? $row['sdt_docgia'] : ''; ?>" required>
        </div>
        <div class="text-center mb-2">
            <a href="index.php" class="btn btn-secondary" style="margin-right: 200px;">HỦY</a>
            <input type="submit" class="btn btn-success" name="sua_docgia" value="UPDATE">
        </div>    
    </form>
    </div>

    </div>
</body>
</html>

    <?php
        if(isset($_POST['sua_docgia'])){
            $ten=$_POST['ten_docgia'];
            $tuoi= $_POST['tuoi_docgia'];
            $gioitinh= $_POST['gioitinh_docgia'];
            $sdt= $_POST['sdt_docgia'];
            $file_name='';
            if(isset($_FILES['docgia_img'])){
                $file= $_FILES['docgia_img'];
                $file_name= $file['name'];
                move_uploaded_file($file['tmp_name'],'../img/docgia/'.$file_name); 
            }
            $query="UPDATE docgia SET ten_docgia = '$ten', tuoi_docgia ='$tuoi', gioitinh_docgia= '$gioitinh',sdt_docgia= '$sdt', hinhanh_docgia= '$file_name' 
            WHERE docgia_id= '$id' ";
            $result= mysqli_query($conn, $query);
                if(!$result){
                    echo "Lỗi cập nhật thông tin" .mysqli_error($conn);
                }else{
                    echo "<script type='text/javascript'>
                        alert('Cập nhật dữ liệu thành công!');
                        window.location.href='index.php';
                        </script>";
                }
    }
    ?>
    