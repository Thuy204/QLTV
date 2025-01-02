<?php
class NguoiDungModel {
    private $conn;
    private $table = "nguoidung";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm người dùng
    public function add_nguoidung($ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung) {
        $query = "INSERT INTO $this->table (ten_nguoidung, email_nguoidung, matkhau_nguoidung, vaitro_nguoidung) 
                  VALUES ('$ten_nguoidung', '$email_nguoidung', '$matkhau_nguoidung', '$vaitro_nguoidung')";
        return mysqli_query($this->conn, $query);
    }

    // Cập nhật người dùng
    public function update_nguoidung($id_nguoidung, $ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung) {
        $query = "UPDATE $this->table 
                  SET ten_nguoidung='$ten_nguoidung', email_nguoidung='$email_nguoidung', matkhau_nguoidung='$matkhau_nguoidung', vaitro_nguoidung='$vaitro_nguoidung' 
                  WHERE id_nguoidung=$id_nguoidung";
        return mysqli_query($this->conn, $query);
    }

    // Xóa người dùng
    public function delete_nguoidung($id_nguoidung) {
        $query = "DELETE FROM $this->table WHERE id_nguoidung=$id_nguoidung";
        return mysqli_query($this->conn, $query);
    }

    // Lấy tất cả người dùng
    public function get_all_nguoidung() {
        $query = "SELECT * FROM $this->table";
        $result = mysqli_query($this->conn, $query);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    // Lấy người dùng theo ID
    public function get_nguoidung_by_id($id_nguoidung) {
        $query = "SELECT * FROM $this->table WHERE id_nguoidung=$id_nguoidung";
        $result = mysqli_query($this->conn, $query);
        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

// Hàm thêm người dùng
function add_nguoidung($nguoidungModel) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['ten_nguoidung'], $data['email_nguoidung'], $data['matkhau_nguoidung'], $data['vaitro_nguoidung'])) {
        echo json_encode(["message" => "Invalid input"]);
        return;
    }

    $ten_nguoidung = $data['ten_nguoidung'];
    $email_nguoidung = $data['email_nguoidung'];
    $matkhau_nguoidung = password_hash($data['matkhau_nguoidung'], PASSWORD_BCRYPT);
    $vaitro_nguoidung = $data['vaitro_nguoidung'];

    if ($nguoidungModel->add_nguoidung($ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung)) {
        echo json_encode(["message" => "Người dùng đã được thêm"]);
    } else {
        echo json_encode(["message" => "Thêm người dùng thất bại"]);
    }
}

// Hàm cập nhật người dùng
function update_nguoidung($nguoidungModel) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['id_nguoidung'], $data['ten_nguoidung'], $data['email_nguoidung'], $data['matkhau_nguoidung'], $data['vaitro_nguoidung'])) {
        echo json_encode(["message" => "Invalid input"]);
        return;
    }

    $id_nguoidung = $data['id_nguoidung'];
    $ten_nguoidung = $data['ten_nguoidung'];
    $email_nguoidung = $data['email_nguoidung'];
    $matkhau_nguoidung = $data['matkhau_nguoidung'];
    $vaitro_nguoidung = $data['vaitro_nguoidung'];

    if ($nguoidungModel->update_nguoidung($id_nguoidung, $ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung)) {
        echo json_encode(["message" => "Người dùng đã được cập nhật"]);
    } else {
        echo json_encode(["message" => "Cập nhật người dùng thất bại"]);
    }
}

// Hàm xóa người dùng
function delete_nguoidung($nguoidungModel) {
    if (isset($_GET['id_nguoidung'])) {
        $id_nguoidung = $_GET['id_nguoidung'];
        if ($nguoidungModel->delete_nguoidung($id_nguoidung)) {
            echo json_encode(["message" => "Người dùng đã được xóa"]);
        } else {
            echo json_encode(["message" => "Xóa người dùng thất bại"]);
        }
    } else {
        echo json_encode(["message" => "Thiếu ID người dùng"]);
    }
}

// Hàm lấy tất cả người dùng
function get_all_nguoidung($nguoidungModel) {
    $nguoidungs = $nguoidungModel->get_all_nguoidung();
    if (count($nguoidungs) > 0) {
        echo json_encode($nguoidungs);
    } else {
        echo json_encode(["message" => "Không tìm thấy người dùng nào"]);
    }
}

// Hàm lấy người dùng theo ID
function get_nguoidung_by_id($nguoidungModel) {
    if (isset($_GET['id'])) {
        $id_nguoidung = $_GET['id'];
        $nguoidung = $nguoidungModel->get_nguoidung_by_id($id_nguoidung);
        if ($nguoidung) {
            echo json_encode($nguoidung);
        } else {
            echo json_encode(["message" => "Không tìm thấy người dùng với ID: $id_nguoidung"]);
        }
    } else {
        echo json_encode(["message" => "Thiếu ID người dùng"]);
    }
}
?>
