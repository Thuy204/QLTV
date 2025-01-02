<?php
include('../config/db.php');
include('../model/qlytacgia_model.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, PUT, DELETE, GET");
header("Access-Control-Allow-Origin: *");

$request_method = $_SERVER['REQUEST_METHOD'];
$tacgia = new TacGia($conn);

switch ($request_method) {
    case 'GET':
        echo $tacgia->readTacGiaList();
        break;

    case 'POST':
        if (isset($_POST['ten_tacgia']) && isset($_POST['tuoi_tacgia']) && isset($_POST['gioitinh_tacgia']) && isset($_POST['sdt_tacgia'])) {
            $ten_tacgia = $_POST['ten_tacgia'];
            $tuoi_tacgia = $_POST['tuoi_tacgia'];
            $gioitinh_tacgia = $_POST['gioitinh_tacgia'];
            $sdt_tacgia = $_POST['sdt_tacgia'];

            // Kiểm tra giá trị của gioitinh_tacgia
            if ($gioitinh_tacgia !== "0" && $gioitinh_tacgia !== "1") {
                $data = [
                    'status' => 422,
                    'message' => 'Dữ liệu giới tính không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            // Xử lý upload hình ảnh
            $hinhanh_tacgia = null; // Gán mặc định là null
            if (isset($_FILES['hinhanh_tacgia']) && $_FILES['hinhanh_tacgia']['error'] == 0) {
                $target_dir = "../img/tacgia/";
                $target_file = $target_dir . basename($_FILES["hinhanh_tacgia"]["name"]);
                if (move_uploaded_file($_FILES["hinhanh_tacgia"]["tmp_name"], $target_file)) {
                    $hinhanh_tacgia = basename($_FILES["hinhanh_tacgia"]["name"]);
                } else {
                    $data = [
                        'status' => 500,
                        'message' => 'Lỗi khi tải lên hình ảnh',
                    ];
                    echo json_encode($data);
                    exit;
                }
            }

            $inputdata = [
                'ten_tacgia' => $ten_tacgia,
                'tuoi_tacgia' => $tuoi_tacgia,
                'gioitinh_tacgia' => $gioitinh_tacgia,
                'sdt_tacgia' => $sdt_tacgia,
                'hinhanh_tacgia' => $hinhanh_tacgia,
            ];

            echo $tacgia->insertTacGia($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'Thiếu thông tin',
            ];
            echo json_encode($data);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        if (isset($_PUT['tacgia_id']) && isset($_PUT['ten_tacgia']) && isset($_PUT['tuoi_tacgia']) && isset($_PUT['gioitinh_tacgia']) && isset($_PUT['sdt_tacgia'])) {
            $tacgia_id = $_PUT['tacgia_id'];
            $ten_tacgia = $_PUT['ten_tacgia'];
            $tuoi_tacgia = $_PUT['tuoi_tacgia'];
            $gioitinh_tacgia = $_PUT['gioitinh_tacgia'];
            $sdt_tacgia = $_PUT['sdt_tacgia'];

            // Kiểm tra giá trị của gioitinh_tacgia
            if ($gioitinh_tacgia !== "0" && $gioitinh_tacgia !== "1") {
                $data = [
                    'status' => 422,
                    'message' => 'Dữ liệu giới tính không hợp lệ',
                ];
                echo json_encode($data);
                exit;
            }

            // Xử lý upload hình ảnh
            if (isset($_FILES['hinhanh_tacgia']) && $_FILES['hinhanh_tacgia']['error'] == 0) {
                $target_dir = "../img/tacgia/";
                $target_file = $target_dir . basename($_FILES["hinhanh_tacgia"]["name"]);
                move_uploaded_file($_FILES["hinhanh_tacgia"]["tmp_name"], $target_file);
                $hinhanh_tacgia = basename($_FILES["hinhanh_tacgia"]["name"]);
            } else {
                $hinhanh_tacgia = null; // Nếu không có ảnh được tải lên
            }

            $inputdata = [
                'tacgia_id' => $tacgia_id,
                'ten_tacgia' => $ten_tacgia,
                'tuoi_tacgia' => $tuoi_tacgia,
                'gioitinh_tacgia' => $gioitinh_tacgia,
                'sdt_tacgia' => $sdt_tacgia,
                'hinhanh_tacgia' => $hinhanh_tacgia,
            ];

            echo $tacgia->updateTacGia($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'Dữ liệu thiếu hoặc không hợp lệ',
            ];
            echo json_encode($data);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        if (isset($_DELETE['id'])) {
            $tacgia_id = $_DELETE['id'];
            $inputdata = [
                'tacgia_id' => $tacgia_id,
            ];
            echo $tacgia->deleteTacGia($inputdata);
        } else {
            $data = [
                'status' => 422,
                'message' => 'ID không hợp lệ hoặc thiếu',
            ];
            echo json_encode($data);
        }
        break;

    default:
        $data = [
            'status' => 405,
            'message' => 'Phương thức không được phép',
        ];
        echo json_encode($data);
        break;
}
?>
