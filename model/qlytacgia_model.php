<?php
class TacGia {
    private $conn;
    private $table = "tacgia";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readTacGiaList() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_all(MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'List of Authors Fetched Successfully!',
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

    public function getTacGiaById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE tacgia_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_assoc();
            $data = [
                'status' => 200,
                'message' => 'Author Fetched Successfully!',
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

    public function insertTacGia($tacgiaInput) {
        $name = $tacgiaInput["ten_tacgia"];
        $age = $tacgiaInput["tuoi_tacgia"];
        $gender = $tacgiaInput["gioitinh_tacgia"];
        $phone = $tacgiaInput["sdt_tacgia"];
        $image = $tacgiaInput["hinhanh_tacgia"];
    
        if (empty($name) || empty($age) || !isset($gender) || empty($phone)) {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu thiếu hoặc không hợp lệ',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của gioitinh_tacgia
        if ($gender !== "0" && $gender !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu giới tính không hợp lệ',
            ];
            return json_encode($data);
        }
    
        $sql = "INSERT INTO " . $this->table . " (ten_tacgia, tuoi_tacgia, gioitinh_tacgia, sdt_tacgia, hinhanh_tacgia) 
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

    public function updateTacGia($tacgiaUpdate) {
        $id = $tacgiaUpdate["tacgia_id"];
        $name = $tacgiaUpdate["ten_tacgia"];
        $gender = $tacgiaUpdate["gioitinh_tacgia"];
        $phone = $tacgiaUpdate["sdt_tacgia"];
        $image = $tacgiaUpdate["hinhanh_tacgia"];

        if (empty($id) || empty($name) || !isset($gender) || empty($phone)) {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu không hợp lệ hoặc thiếu',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của gioitinh_tacgia
        if ($gender !== "0" && $gender !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu giới tính không hợp lệ',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM " . $this->table . " WHERE tacgia_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Tác giả không tồn tại',
            ];
            return json_encode($data);
        }

        $sql = "UPDATE " . $this->table . " SET ten_tacgia = ?, gioitinh_tacgia = ?, sdt_tacgia = ?, hinhanh_tacgia = ? WHERE tacgia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $gender, $phone, $image, $id);

        if ($stmt->execute()) {
            $data = [
                'status' => 200,
                'message' => 'Cập nhật thành công',
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

    public function deleteTacGia($tacgiaInput) {
        $id = $tacgiaInput["tacgia_id"];

        if (empty($id)) {
            $data = [
                'status' => 422,
                'message' => 'ID không hợp lệ hoặc thiếu',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM " . $this->table . " WHERE tacgia_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Tác giả không tồn tại',
            ];
            return json_encode($data);
        }

        $sql = "DELETE FROM " . $this->table . " WHERE tacgia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $data = [
                'status' => 200,
                'message' => 'Xóa tác giả thành công',
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
}
?>
