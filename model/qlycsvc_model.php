<?php
class Csvc {
    private $conn;
    private $table = "csvc";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readServiceList() {
        $query = "SELECT * FROM cosovatchat";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_all(MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Service List Fetched Successfully!',
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

    public function getServiceById($id) {
        $query = "SELECT * FROM csvc WHERE csvc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $res = $result->fetch_assoc();
            $data = [
                'status' => 200,
                'message' => 'Service Fetched Successfully!',
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

    public function insertService($csvcInput) {
        $name = $csvcInput["ten_csvc"];
        $quantity = $csvcInput["soluong_csvc"];
        $status = $csvcInput["tinhtrang_csvc"];
    
        if (empty($name) || empty($quantity) || !isset($status)) {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu thiếu hoặc không hợp lệ',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của tinhtrang_csvc
        if ($status !== "0" && $status !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu tình trạng không hợp lệ',
            ];
            return json_encode($data);
        }
    
        $sql = "INSERT INTO csvc (ten_csvc, soluong_csvc, tinhtrang_csvc) 
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $name, $quantity, $status);
    
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

    public function updateService($csvcUpdate) {
        $id = $csvcUpdate["csvc_id"];
        $name = $csvcUpdate["ten_csvc"];
        $quantity = $csvcUpdate["soluong_csvc"];
        $status = $csvcUpdate["tinhtrang_csvc"];

        if (empty($id) || empty($name) || empty($quantity) || !isset($status)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing Input Data',
            ];
            return json_encode($data);
        }

        // Kiểm tra giá trị của tinhtrang_csvc
        if ($status !== "0" && $status !== "1") {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu tình trạng không hợp lệ',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM csvc WHERE csvc_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Service Not Found',
            ];
            return json_encode($data);
        }

        $sql = "UPDATE csvc SET ten_csvc = ?, soluong_csvc = ?, tinhtrang_csvc = ? WHERE csvc_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $name, $quantity, $status, $id);

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

    public function deleteService($csvcInput) {
        $id = $csvcInput["csvc_id"];

        if (empty($id)) {
            $data = [
                'status' => 422,
                'message' => 'Invalid or Missing ID',
            ];
            return json_encode($data);
        }

        $checkSql = "SELECT * FROM csvc WHERE csvc_id = ?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $data = [
                'status' => 404,
                'message' => 'Service Not Found',
            ];
            return json_encode($data);
        }

        $sql = "DELETE FROM csvc WHERE csvc_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $data = [
                'status' => 200,
                'message' => 'Service Deleted Successfully',
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
