<?php include'../config/db.php'; ?>
<?php
    if(isset($_GET['id'])){
        $id=$_GET['id'];
    }
    $query= "DELETE FROM docgia WHERE docgia_id='$id'";
    $result=mysqli_query($conn, $query);
    if(! $result){
        echo"Lỗi xóa ";
    }
    else{
        header('Location:index.php');
    }
?>