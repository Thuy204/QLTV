<?php
include('../config/db.php');
include('../model/qlydocgia_model.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, PUT, DELETE, GET");
header("Access-Control-Allow-Origin: *");

$request_method = $_SERVER['REQUEST_METHOD'];
$docgia = new Docgia($conn);

switch ($request_method) {
    case 'GET':
        echo $docgia->readStoryList();
        break;
    
        case 'POST':
            // Lấy dữ liệu từ AJAX
            if (isset($_POST['ten_docgia']) && isset($_POST['tuoi_docgia']) && isset($_POST['gioitinh_docgia']) && isset($_POST['sdt_docgia'])) {
                $ten_docgia = $_POST['ten_docgia'];
                $tuoi_docgia = $_POST['tuoi_docgia'];
                $gioitinh_docgia = $_POST['gioitinh_docgia'];
                $sdt_docgia = $_POST['sdt_docgia'];
                
                // Kiểm tra giá trị của gioitinh_docgia
                if ($gioitinh_docgia !== "0" && $gioitinh_docgia !== "1") {
                    $data = [
                        'status' => 422,
                        'message' => 'Dữ liệu giới tính không hợp lệ',
                    ];
                    echo json_encode($data);
                    exit;
                }
        
                // Xử lý upload hình ảnh
                $hinhanh_docgia = null; // Gán mặc định là null
                if (isset($_FILES['hinhanh_docgia']) && $_FILES['hinhanh_docgia']['error'] == 0) {
                    $target_dir = "../img/docgia/";
                    $target_file = $target_dir . basename($_FILES["hinhanh_docgia"]["name"]);
                    if (move_uploaded_file($_FILES["hinhanh_docgia"]["tmp_name"], $target_file)) {
                        $hinhanh_docgia = basename($_FILES["hinhanh_docgia"]["name"]);
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
                    'ten_docgia' => $ten_docgia,
                    'tuoi_docgia' => $tuoi_docgia,
                    'gioitinh_docgia' => $gioitinh_docgia,
                    'sdt_docgia' => $sdt_docgia,
                    'hinhanh_docgia' => $hinhanh_docgia,
                ];
        
                echo $docgia->insertStory($inputdata);
            }
             else {
                $data = [
                    'status' => 422,
                    'message' => 'hahah',
                ];
                echo json_encode($data);
            }
            break;
    
            case 'PUT':
                parse_str(file_get_contents("php://input"), $_PUT);
                if (isset($_PUT['docgia_id']) && isset($_PUT['ten_docgia']) && isset($_PUT['tuoi_docgia']) && isset($_PUT['gioitinh_docgia']) && isset($_PUT['sdt_docgia'])) {
                    $docgia_id = $_PUT['docgia_id'];
                    $ten_docgia = $_PUT['ten_docgia'];
                    $tuoi_docgia = $_PUT['tuoi_docgia'];
                    $gioitinh_docgia = $_PUT['gioitinh_docgia'];
                    $sdt_docgia = $_PUT['sdt_docgia'];
        
                    // Kiểm tra giá trị của gioitinh_docgia
                    if ($gioitinh_docgia !== "0" && $gioitinh_docgia !== "1") {
                        $data = [
                            'status' => 422,
                            'message' => 'Dữ liệu giới tính không hợp lệ',
                        ];
                        echo json_encode($data);
                        exit;
                    }
        
                    // Xử lý upload hình ảnh
                    if (isset($_FILES['hinhanh_docgia']) && $_FILES['hinhanh_docgia']['error'] == 0) {
                        $target_dir = "../img/docgia/";
                        $target_file = $target_dir . basename($_FILES["hinhanh_docgia"]["name"]);
                        move_uploaded_file($_FILES["hinhanh_docgia"]["tmp_name"], $target_file);
                        $hinhanh_docgia = basename($_FILES["hinhanh_docgia"]["name"]);
                    } else {
                        $hinhanh_docgia = null; // Nếu không có ảnh được tải lên
                    }
        
                    $inputdata = [
                        'docgia_id' => $docgia_id,
                        'ten_docgia' => $ten_docgia,
                        'tuoi_docgia' => $tuoi_docgia,
                        'gioitinh_docgia' => $gioitinh_docgia,
                        'sdt_docgia' => $sdt_docgia,
                        'hinhanh_docgia' => $hinhanh_docgia,
                    ];
        
                    echo $docgia->updateStory($inputdata);
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
                    $docgia_id = $_DELETE['id'];
                    $inputdata = [
                        'docgia_id' => $docgia_id,
                    ];
                    echo $docgia->deleteStory($inputdata);
                } else {
                    $data = [
                        'status' => 422,
                        'message' => 'Invalid or Missing ID',
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