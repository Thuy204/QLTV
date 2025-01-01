<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhà Xuất Bản</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #333;
            text-align: left;
        }

        input[type="text"],
        textarea,
        button {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 15px;
            color: green;
            text-align: center;
        }

        .error {
            margin-bottom: 15px;
            color: red;
            text-align: center;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            text-align: center;
            width: 100%;
        }

        .back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="background-blur"></div>
    <div class="container mt-5">
        <h1>Sửa Nhà Xuất Bản</h1>
        <?php
        require_once '../config/db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $ten_nxb = $_POST["ten_nxb"];
            $thongtin_nxb = $_POST["thongtin_nxb"];

            $sql = "UPDATE nhaxuatban SET ten_nxb='$ten_nxb', thongtin_nxb='$thongtin_nxb' WHERE nxb_id=$id";

            if ($conn->query($sql) === TRUE) {
                header("Location: index.php"); // Chuyển hướng về trang quản lý nhà xuất bản sau khi cập nhật thành công
                exit();
            } else {
                echo "Lỗi: " . $conn->error;
            }
        }

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $sql = "SELECT * FROM nhaxuatban WHERE nxb_id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <form method="post" action="edit.php">
                    <input type="hidden" name="id" value="<?php echo $row['nxb_id']; ?>">
                    <div class="mb-3">
                        <label for="ten_nxb" class="form-label">Tên Nhà Xuất Bản</label>
                        <input type="text" class="form-control" id="ten_nxb" name="ten_nxb" value="<?php echo $row['ten_nxb']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="thongtin_nxb" class="form-label">Thông Tin Nhà Xuất Bản</label>
                        <textarea class="form-control" id="thongtin_nxb" name="thongtin_nxb" rows="3" required><?php echo $row['thongtin_nxb']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </form>
                <?php
            } else {
                echo "Không tìm thấy nhà xuất bản.";
            }
        }

        $conn->close();
        ?>
        <a href="index.php" class="back-link">Quay lại</a>
    </div>
</body>

</html>
