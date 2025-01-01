<?php
class Docgia {
    private $conn;
    private $table = "docgia";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readStoryList() {
        $query = "SELECT * FROM docgia";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_all(MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Story List Fetched Successfully!',
                'data' => $res,
            ];
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No data found',
            ];
            return json_encode($data);
        }
    }

    public function getStoryById($id) {
        $query = "SELECT * FROM docgia WHERE docgia_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_assoc();
            $data = [
                'status' => 200,
                'message' => 'Story Fetched Successfully!',
                'data' => $res,
            ];
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No data found',
            ];
            return json_encode($data);
        }
    }

    public function insertStory($truyenInput) {
        $name = $truyenInput["ten_docgia"];
        $age = $truyenInput["tuoi_docgia"];
        $gender = $truyenInput["gioitinh_docgia"];
        $phone = $truyenInput["sdt_docgia"];
        $image = $truyenInput["hinhanh_docgia"];
    
        if (empty($name) || empty($age) || !isset($gender) || empty($phone)) {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu thiếu hoặc không hợp lệ',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của gioitinh_docgia
        if ($gender !== "0" && $gender !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu giới tính không hợp lệ',
            ];
            return json_encode($data);
        }
    
        $sql = "INSERT INTO docgia (ten_docgia, tuoi_docgia, gioitinh_docgia, sdt_docgia, hinhanh_docgia) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisss", $name, $age, $gender, $phone, $image);
    
        if ($stmt->execute()) {
            $data = [
                'status' => 201,
                'message' => 'Thêm thành công',
            ];
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Lỗi máy chủ',
            ];
            return json_encode($data);
        }
    }

    public function updateStory($truyenupdate) {
        $id = $truyenupdate["docgia_id"];
        $name = $truyenupdate["ten_docgia"];
        $gender = $truyenupdate["gioitinh_docgia"];
        $phone = $truyenupdate["sdt_docgia"];
        $image = $truyenupdate["hinhanh_docgia"];

        if (empty($id) || empty($name) || !isset($gender) || empty($phone)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing Input Data',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của gioitinh_docgia
        if ($gender !== "0" && $gender !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu giới tính không hợp lệ',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM docgia WHERE docgia_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Story Not Found',
            ];
            return json_encode($data);
        }

        $sql = "UPDATE docgia SET ten_docgia = ?, gioitinh_docgia = ?, sdt_docgia = ?, hinhanh_docgia = ? WHERE docgia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $gender, $phone, $image, $id);

        if ($stmt->execute()) {
            $data = [
                'status' => 200,
                'message' => 'Updated successfully',
            ];
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            return json_encode($data);
        }
    }

    public function deleteStory($truyenInput) {
        $id = $truyenInput["docgia_id"];

        if (empty($id)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing ID',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM docgia WHERE docgia_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Story Not Found',
            ];
            return json_encode($data);
        }

        $sql = "DELETE FROM docgia WHERE docgia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $data = [
                'status' => 200,
                'message' => 'Story Deleted Successfully',
            ];
            return json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            return json_encode($data);
        }
    }
}
?>