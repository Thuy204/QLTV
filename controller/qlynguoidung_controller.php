<?php
include '../model/qlynguoidung_model.php'; // Include model
header('Content-Type: application/json'); // Đảm bảo API trả về JSON

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'qly_thuvien');
$nguoidungModel = new NguoiDungModel($conn); // Khởi tạo model

$request_method = $_SERVER['REQUEST_METHOD']; // Lấy phương thức yêu cầu HTTP

switch ($request_method) {

    case 'GET':
        if (isset($_GET['id'])) {
            get_nguoidung_by_id($nguoidungModel); // Lấy người dùng theo ID
        } else {
            get_all_nguoidung($nguoidungModel); // Lấy tất cả người dùng
        }
        break;

    case 'POST':
        // Lấy dữ liệu JSON từ request
        $data = json_decode(file_get_contents("php://input"), true);

        // Kiểm tra xem có đủ dữ liệu đầu vào không
        if (!isset($data['ten_nguoidung']) || !isset($data['email_nguoidung']) || !isset($data['matkhau_nguoidung']) || !isset($data['vaitro_nguoidung'])) {
            http_response_code(422);
            $data = [
                'status' => 422,
                'message' => 'Thiếu dữ liệu đầu vào',
            ];
            echo json_encode($data);
            break;
        }

        $ten_nguoidung = $data['ten_nguoidung'];
        $email_nguoidung = $data['email_nguoidung'];
        $matkhau_nguoidung = $data['matkhau_nguoidung'];
        $vaitro_nguoidung = $data['vaitro_nguoidung'];

        // Kiểm tra định dạng email
        if (!filter_var($email_nguoidung, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            $data = [
                'status' => 422,
                'message' => 'Email không hợp lệ',
            ];
            echo json_encode($data);
            break;
        }

        // Kiểm tra tính hợp lệ của vai trò
        // if ($vaitro_nguoidung !== "0" && $vaitro_nguoidung !== "1") {
        //     http_response_code(422);
        //     $data = [
        //         'status' => 422,
        //         'message' => 'Vai trò không hợp lệ',
        //     ];
        //     echo json_encode($data);
        //     break;
        // }

        // Thêm người dùng vào cơ sở dữ liệu
        if ($nguoidungModel->add_nguoidung($ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung)) {
            http_response_code(201);
            $data = [
                'status' => 201,
                'message' => 'Người dùng đã được thêm thành công',
            ];
            echo json_encode($data);
        } else {
            http_response_code(500);
            $data = [
                'status' => 500,
                'message' => 'Thêm người dùng thất bại',
            ];
            echo json_encode($data);
        }
        break;

    case 'PUT':
        // Lấy dữ liệu JSON từ request
        $data = json_decode(file_get_contents("php://input"), true);

        // Kiểm tra dữ liệu đầu vào
        if (!isset($data['id_nguoidung']) || !isset($data['ten_nguoidung']) || !isset($data['email_nguoidung']) || !isset($data['matkhau_nguoidung']) || !isset($data['vaitro_nguoidung'])) {
            http_response_code(422);
            $data = [
                'status' => 422,
                'message' => 'Thiếu hoặc sai dữ liệu đầu vào',
            ];
            echo json_encode($data);
            break;
        }

        $id_nguoidung = $data['id_nguoidung'];
        $ten_nguoidung = $data['ten_nguoidung'];
        $email_nguoidung = $data['email_nguoidung'];
        $matkhau_nguoidung = $data['matkhau_nguoidung'];
        $vaitro_nguoidung = $data['vaitro_nguoidung'];

        // Kiểm tra định dạng email
        if (!filter_var($email_nguoidung, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            $data = [
                'status' => 422,
                'message' => 'Email không hợp lệ',
            ];
            echo json_encode($data);
            break;
        }

        // Kiểm tra tính hợp lệ của vai trò
        // if ($vaitro_nguoidung !== "0" && $vaitro_nguoidung !== "1") {
        //     http_response_code(422);
        //     $data = [
        //         'status' => 422,
        //         'message' => 'Vai trò không hợp lệ',
        //     ];
        //     echo json_encode($data);
        //     break;
        // }

        // Cập nhật người dùng trong cơ sở dữ liệu
        if ($nguoidungModel->update_nguoidung($id_nguoidung, $ten_nguoidung, $email_nguoidung, $matkhau_nguoidung, $vaitro_nguoidung)) {
            http_response_code(200);
            $data = [
                'status' => 200,
                'message' => 'Cập nhật người dùng thành công',
            ];
            echo json_encode($data);
        } else {
            http_response_code(500);
            $data = [
                'status' => 500,
                'message' => 'Cập nhật người dùng thất bại',
            ];
            echo json_encode($data);
        }
        break;

    case 'DELETE':
        // Lấy id_nguoidung từ URL
        if (isset($_GET['id_nguoidung'])) {
            $id = $_GET['id_nguoidung'];

            // Kiểm tra ID hợp lệ
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                $data = [
                    'status' => 400,
                    'message' => 'ID người dùng không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            if ($nguoidungModel->delete_nguoidung($id)) {
                http_response_code(200);
                $data = [
                    'status' => 200,
                    'message' => 'Xóa người dùng thành công',
                ];
                echo json_encode($data);
            } else {
                http_response_code(500);
                $data = [
                    'status' => 500,
                    'message' => 'Xóa người dùng thất bại',
                ];
                echo json_encode($data);
            }
        } else {
            http_response_code(400);
            $data = [
                'status' => 400,
                'message' => 'Thiếu ID người dùng',
            ];
            echo json_encode($data);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>
