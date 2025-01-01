<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h1>Quản lý sản phẩm</h1>
<button id="loadProducts">Tải sản phẩm</button>
<table id="productsTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<h2>Thêm Sản Phẩm</h2>
<input type="text" id="productName" placeholder="Tên sản phẩm">
<input type="number" id="productPrice" placeholder="Giá">
<button id="addProduct">Thêm sản phẩm</button>

<script>
    document.getElementById('loadProducts').addEventListener('click', function() {
        fetch('http://localhost/my_api/public/index.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('productsTable').querySelector('tbody');
                tableBody.innerHTML = ''; // Xóa dữ liệu cũ
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.price}</td>
                        <td>
                            <button onclick="editProduct(${product.id}, '${product.name}', ${product.price})">Sửa</button>
                            <button onclick="deleteProduct(${product.id})">Xóa</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Lỗi:', error));
    });

    document.getElementById('addProduct').addEventListener('click', function() {
        const name = document.getElementById('productName').value;
        const price = document.getElementById('productPrice').value;

        fetch('http://localhost/my_api/public/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, price })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            document.getElementById('productName').value = '';
            document.getElementById('productPrice').value = '';
            document.getElementById('loadProducts').click(); // Tải lại danh sách sản phẩm
        });
    });

    function editProduct(id, name, price) {
        const newName = prompt("Sửa tên sản phẩm:", name);
        const newPrice = prompt("Sửa giá sản phẩm:", price);
        if (newName && newPrice) {
            fetch('http://localhost/my_api/public/index.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, name: newName, price: newPrice })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById('loadProducts').click(); // Tải lại danh sách sản phẩm
            });
        }
    }

    function deleteProduct(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            fetch('http://localhost/my_api/public/index.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById('loadProducts').click(); // Tải lại danh sách sản phẩm
            });
        }
    }
</script>

</body>
</html>