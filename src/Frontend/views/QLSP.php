<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/auth/admin_check.php'; // Kiểm tra bảo mật
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';      // Kết nối CSDL

// Kiểm tra và thêm cột SOLUONG nếu chưa có
$check_col = $conn->query("SHOW COLUMNS FROM SAN_PHAM LIKE 'SOLUONG'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE SAN_PHAM ADD COLUMN SOLUONG INT DEFAULT 10");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 shadow">
    <div class="container">
        
        <a href="QTHT.php" class="btn btn-outline-light btn-sm fw-bold shadow-sm me-3" title="Về trang chủ">
            <i class="fa-solid fa-house"></i>
        </a>

        <span class="navbar-brand mb-0 h1 fw-bold">
            <i class="fa-solid fa-gauge-high me-2"></i>Quản lý sản phẩm
        </span>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex text-white align-items-center mt-2 mt-lg-0">
                <span class="me-3">Xin chào, <b class="text-warning"><?php echo $_SESSION['admin_id']; ?></b></span>
                <a href="../../Backend/auth/LogoutUser.php" class="btn btn-sm btn-light text-danger fw-bold shadow-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>

    </div>
</nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách sản phẩm</h2>
            <a href="../../Backend/controllers/addSP.php" class="btn btn-success"><i class="fa fa-plus"></i> Thêm mới sản phẩm</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="col-2">Mã SP</th>
                            <th class="col-2">Hình ảnh</th>
                            <th class="col-2">Tên sản phẩm</th>
                            <th class="col-1">Số lượng</th>
                            <th class="col-2">Giá tiền</th>
                            <th class="col-2">Danh mục</th>
                            <th class="col-2">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Lấy sản phẩm và tên danh mục tương ứng
                        $sql = "SELECT SAN_PHAM.*, DANH_MUC.TENDM 
                                FROM SAN_PHAM 
                                JOIN DANH_MUC ON SAN_PHAM.MADM = DANH_MUC.MADM
                                ORDER BY MASP DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Xử lý ảnh BLOB
                                if ($row['HINHANHSP']) {
                                    $imgData = base64_encode($row['HINHANHSP']);
                                    $src = 'data:image/jpeg;base64,'.$imgData;
                                } else { $src = 'https://via.placeholder.com/150'; }
                        ?>
                            <tr>
                                <td><?php echo $row['MASP']; ?></td>
                                <td><img src="<?php echo $src; ?>" style="height: 60px;"></td>
                                <td class="text-start"><?php echo $row['TENSP']; ?></td>
                                <td class="fw-bold"><?php echo isset($row['SOLUONG']) ? $row['SOLUONG'] : 0; ?></td>
                                <td class="fw-bold text-danger"><?php echo number_format($row['DONGIA']); ?>đ</td>
                                <td><span class="badge bg-info text-dark"><?php echo $row['TENDM']; ?></span></td>
                                <td>
                                    <a href="../../Backend/controllers/changeSP.php?id=<?php echo $row['MASP']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="../../Backend/controllers/deleteSP.php?id=<?php echo $row['MASP']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6'>Chưa có sản phẩm nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bắt đầu footer -->
    <footer class="bg-white border-top py-3">
        <div class="container text-center">
            <small class="text-muted">&copy;2025. Đồ án cơ sở ngành. Thực hiện bởi Phạm Thị Hồng Phương</small>
        </div>
    </footer>
    <!-- Kết thúc footer -->

</body>
</html>