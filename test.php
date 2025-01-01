<?php
header("Content-Type: application/json; charset=UTF-8");

// Kết nối đến cơ sở dữ liệu
$host = 'localhost';
$db_name = 'my_api';
$username = 'root'; // Thay đổi nếu cần
$password = ''; // Thay đổi nếu cần

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Lấy phương thức HTTP
$request_method = $_SERVER["REQUEST_METHOD"];

// Xử lý các phương thức khác nhau
switch ($request_method) {
    case 'GET':
        // Lấy tất cả sản phẩm
        $stmt = $conn->prepare("SELECT * FROM docgia");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
        break;

    case 'POST':
        // Thêm sản phẩm mới
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->name) && isset($data->price)) {
            $stmt = $conn->prepare("INSERT INTO docgia (tuoi_docgia, ten_docgia ) VALUES (:name, :price)");
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':price', $data->price);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Product added successfully."]);
            } else {
                echo json_encode(["message" => "Failed to add product."]);
            }
        } else {
            echo json_encode(["message" => "Invalid input."]);
        }
        break;

    case 'PUT':
        // Cập nhật sản phẩm
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id) && isset($data->name) && isset($data->price)) {
            $stmt = $conn->prepare("UPDATE docgia SET tuoi_docgia = :name, ten_docgia = :price WHERE docgia_id = :id");
            $stmt->bindParam(':id', $data->id);
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':price', $data->price);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Product updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update product."]);
            }
        } else {
            echo json_encode(["message" => "Invalid input."]);
        }
        break;

    case 'DELETE':
        // Xóa sản phẩm
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id)) {
            $stmt = $conn->prepare("DELETE FROM docgia WHERE docgia_id = :id");
            $stmt->bindParam(':id', $data->id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Product deleted successfully."]);
            } else {
                echo json_encode(["message" => "Failed to delete product."]);
            }
        } else {
            echo json_encode(["message" => "Invalid input."]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method."]);
        break;
}

$conn = null; // Đóng kết nối
?>