<?php
include('../config/db.php');
include('../model/qlycsvc_model.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, PUT, DELETE, GET");
header("Access-Control-Allow-Origin: *");

$request_method = $_SERVER['REQUEST_METHOD'];
$csvc = new Csvc($conn);

switch ($request_method) {
    case 'GET':
        // Lấy danh sách hoặc chi tiết cơ sở vật chất
        if (isset($_GET['id'])) {
            echo $csvc->getServiceById($_GET['id']);
        } else {
            echo $csvc->readServiceList();
        }
        break;

    case 'POST':
        // Thêm cơ sở vật chất mới
        if (isset($_POST['ten_csvc']) && isset($_POST['soluong_csvc']) && isset($_POST['tinhtrang_csvc'])) {
            $ten = $_POST['ten_csvc'];
            $quantity = $_POST['soluong_csvc'];
            $status = $_POST['tinhtrang_csvc'];

            // Kiểm tra tính hợp lệ của số lượng
            if (!is_numeric($quantity) || $quantity < 0) {
                $data = [
                    'status' => 422,
                    'message' => 'Số lượng không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            // Kiểm tra tính hợp lệ của tình trạng
            if ($status !== "0" && $status !== "1") {
                $data = [
                    'status' => 422,
                    'message' => 'Tình trạng không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            $inputdata = [
                'ten_csvc' => $ten,
                'soluong_csvc' => $quantity,
                'tinhtrang_csvc' => $status,
            ];
            echo $csvc->insertService($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'Thiếu dữ liệu đầu vào',
            ];
            echo json_encode($data);
        }
        break;

    case 'PUT':
        // Cập nhật thông tin cơ sở vật chất
        parse_str(file_get_contents("php://input"), $_PUT);
        if (isset($_PUT['csvc_id']) && isset($_PUT['ten_csvc']) && isset($_PUT['soluong_csvc']) && isset($_PUT['tinhtrang_csvc'])) {
            $id = $_PUT['csvc_id'];
            $ten = $_PUT['ten_csvc'];
            $quantity = $_PUT['soluong_csvc'];
            $status = $_PUT['tinhtrang_csvc'];

            // Kiểm tra tính hợp lệ của số lượng
            if (!is_numeric($quantity) || $quantity < 0) {
                $data = [
                    'status' => 422,
                    'message' => 'Số lượng không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            // Kiểm tra tính hợp lệ của tình trạng
            if ($status !== "0" && $status !== "1") {
                $data = [
                    'status' => 422,
                    'message' => 'Tình trạng không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            $inputdata = [
                'csvc_id' => $id,
                'ten_csvc' => $ten,
                'soluong_csvc' => $quantity,
                'tinhtrang_csvc' => $status,
            ];
            echo $csvc->updateService($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'Thiếu hoặc sai dữ liệu đầu vào',
            ];
            echo json_encode($data);
        }
        break;

    case 'DELETE':
        // Xóa cơ sở vật chất
        parse_str(file_get_contents("php://input"), $_DELETE);
        if (isset($_DELETE['csvc_id'])) {
            $id = $_DELETE['csvc_id'];
            $inputdata = [
                'csvc_id' => $id,
            ];
            echo $csvc->deleteService($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'Thiếu hoặc sai ID',
            ];
            echo json_encode($data);
        }
        break;

    default:
        $data = [
            'status' => 405,
            'message' => 'Method Not Allowed',
        ];
        echo json_encode($data);
        break;
}
?>
