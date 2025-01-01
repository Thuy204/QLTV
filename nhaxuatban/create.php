<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhà Xuất Bản</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Add some transparency */
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            text-align: left;
        }

        input[type="text"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
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
    <div class="container">
        <h1>Thêm Nhà Xuất Bản</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $conn = mysqli_connect("localhost", "root", "", "qly_thuvien");
            if (!$conn) {
                die("Kết nối không thành công: " . mysqli_connect_error());
            }
            $nxb_id = $_POST['nxb_id'];
            $ten_nxb = $_POST['ten_nxb'];
            $thongtin_nxb = $_POST['thongtin_nxb'];
            $query = "INSERT INTO nhaxuatban (nxb_id, ten_nxb, thongtin_nxb) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $nxb_id, $ten_nxb, $thongtin_nxb);

            if ($stmt->execute()) {
                echo "<p class='message'>Thêm Nhà Xuất Bản thành công</p>";
            } else {
                echo "<p class='error'>Lỗi: " . $stmt->error . "</p>";
            }

            $stmt->close();
            mysqli_close($conn);
        }
        ?>
        <form method="POST">
            <label for="nxb_id">ID:</label>
            <input type="text" id="nxb_id" name="nxb_id" required><br>
            <label for="ten_nxb">Tên NXB:</label>
            <input type="text" id="ten_nxb" name="ten_nxb" required><br>
            <label for="thongtin_nxb">Thông Tin:</label>
            <input type="text" id="thongtin_nxb" name="thongtin_nxb" required><br>
            <!-- <label for="hinhanh_nxb">Hình ảnh:</label>
            <input type="file" i="hinhanh_nxb" name="hinhanh_nxb" placeholder="Enter Img" class="form-control"> -->
            <button type="submit" name="btnSave">Lưu</button>
        </form>
        <a href="index.php" class="back-link">Quay lại</a>
    </div>
</body>

</html>