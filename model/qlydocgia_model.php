<?php
    class docgia{
    private $conn;
    private $table = "docgia";

    public function __construct($db) {
        $this->conn = $db;
    }
}
    require('../config/db.php');
    function readStoryList() {
        global $conn;
        $query = "SELECT * FROM docgia";
        $query_run = mysqli_query($conn,$query);

        if($query_run) {
            if(mysqli_num_rows($query_run) >0) {

                $res = mysqli_fetch_all($query_run,MYSQLI_ASSOC);
                $data = [
                    'status' => 200,
                    'message' => 'Story List Fetched Successfully!',
                    'data' => $res,
                ];
                header("HTTP/1.0 200 Success ");
                return json_encode($data);
            } 
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
    function insertStory($truyenInput) {
        global $conn;
        $id = $truyenInput["docgia_id"];
        $name = $truyenInput["ten_docgia"];
        $author = $truyenInput["gioitinh_docgia"];
        $note = $truyenInput["sdt_docgia"];
        $hihi= $truyenInput["hinhanh_docgia"];


        if ( empty($name) || empty($author) || empty($note)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing Input Data',
            ];
            header("HTTP/1.0 422 Unprocessable Entity");
            return json_encode($data);
        }
        $checksql = "SELECT docgia_id FROM docgia WHERE docgia_id = $id";
        $checktrungid = mysqli_query($conn, $checksql);

        if (mysqli_num_rows($checktrungid) > 0) {
            $data = [
                'status' => 409,
                'message' => 'Conflict',
            ];
            header("HTTP/1.0 409 Conflict");
            return json_encode($data);
        }

        $sql = "INSERT INTO Truyen(docgia_id,ten_docgia,gioitinh_docgia,sdt_docgia,hinhanh_docgia)values ('$id',n'$name',n'$author',n'$note',n'$hihi')";  
        $result = mysqli_query($conn,$sql);

        if ($result) {
            $data = [
                'status' => 201,
                'message' => 'Create successfully',
            ];
            header("HTTP/1.0 201 Created");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

    }
    function updateStory($truyenupdate){
        global $conn;
        $id = $truyenupdate["docgia_id"];
        $name = $truyenupdate["ten_docgia"];
        $author = $truyenupdate["gioitinh_docgia"];
        $note = $truyenupdate["sdt_docgia"];
        $hihi= $truyenupdate["hinhanh_docgia"];


        if (empty($id) || empty($name) || empty($author) || empty($note)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing Input Data',
            ];
            header("HTTP/1.0 422 Unprocessable Entity");
            return json_encode($data);
        }
        $checkSql = "SELECT * FROM docgia WHERE docgia_id = $id";
        $checkid = mysqli_query($conn, $checkSql);
        if (mysqli_num_rows($checkid) === 0) {
            $data = [
                'status' => 404,
                'message' => 'Story Not Found',
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }

        $sql = "UPDATE docgia SET ten_docgia = n'$name',  gioitinh_docgia=n'$author',sdt_docgia=n'$note, hinhanh_docgia=n'$hihi' WHERE docgia_id = '$id'";  
        $result = mysqli_query($conn,$sql);

        if ($result) {
            $data = [
                'status' => 201,
                'message' => 'Update successfully',
            ];
            header("HTTP/1.0 201 Created");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

    }
    function deleteStory($truyenInput) {
        global $conn;
    
        $id = $truyenInput["docgia_id"];

        if (empty($id)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing ID',
            ];
            header("HTTP/1.0 422 Unprocessable Entity");
            return json_encode($data);
        }
        $checkSql = "SELECT * FROM docgia WHERE docgia = $id";
        $checkid = mysqli_query($conn, $checkSql);
        if (mysqli_num_rows($checkid) === 0) {
            $data = [
                'status' => 404,
                'message' => 'Story Not Found',
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    
        $sql = "DELETE FROM docgia WHERE docgia_id = $id";
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            $data = [
                'status' => 200,
                'message' => 'Story Deleted Successfully',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
?>