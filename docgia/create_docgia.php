<?php
    require_once '../config/db.php';
    if(isset($_POST['them_docgia'])){
        $id= $_POST['docgia_id'];
        $ten= $_POST['ten_docgia'];
        $tuoi= $_POST['tuoi_docgia'];
        $gioitinh= $_POST['gioitinh_docgia'];
        $sdt= $_POST['sdt_docgia'];
        if(isset($_FILES['docgia_img'])){
            $file= $_FILES['docgia_img'];
            $file_name= $file['name'];
            move_uploaded_file($file['tmp_name'],'../img/docgia/'.$file_name); 
        }
        if($id==""|| empty($id)){
            header('Location:index.php');
            exit();
        }
        else{
            $query= "INSERT INTO docgia (docgia_id, ten_docgia, tuoi_docgia, gioitinh_docgia, sdt_docgia, hinhanh_docgia) VALUES ('$id', '$ten', '$tuoi', '$gioitinh', '$sdt','$file_name')";
            $result=mysqli_query($conn, $query);
            if(!$result){
                echo'Dữ liệu không hợp lệ!';
            }
            else{
                header('Location: index.php');           
            }
        }
    }
?>