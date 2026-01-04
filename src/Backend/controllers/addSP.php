<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';
require_once __DIR__ . '/../auth/admin_check.php';

// Xử lý khi nhấn nút Lưu
if (isset($_POST['btn-save'])) {
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $soluong = isset($_POST['soluong']) ? $_POST['soluong'] : 0;
    $dongia = $_POST['dongia'];
    $madm = $_POST['madm'];
    $motasp = $_POST['motasp'];
    $thongso = $_POST['thongso']; // Thêm trường thông số kỹ thuật
    
    // Xử lý upload ảnh (Lưu dạng BLOB)
    $hinh_data = NULL;
    if (isset($_FILES['hinh']) && $_FILES['hinh']['tmp_name'] != "") {
        $hinh_data = addslashes(file_get_contents($_FILES['hinh']['tmp_name']));
    }

    // Lấy mã admin đang đăng nhập (nếu có cột MAQT trong bảng SP)
    // $maqt = $_SESSION['admin_id']; 

    $sql = "INSERT INTO SAN_PHAM (MASP, MADM, TENSP, SOLUONG, DONGIA, MOTASP, THONGSOKYTHUAT, HINHANHSP) 
            VALUES ('$masp', '$madm', '$tensp', '$soluong', '$dongia', '$motasp', '$thongso', '$hinh_data')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm thành công!'); window.location.href='../../Frontend/views/QLSP.php';</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">THÊM SẢN PHẨM</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã Sản Phẩm</label>
                                <input type="text" name="masp" class="form-control" required placeholder="VD: ML005">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên Sản Phẩm</label>
                                <input type="text" name="tensp" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số Lượng</label>
                                <input type="number" name="soluong" class="form-control" required value="10" min="0">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Giá Tiền</label>
                                    <input type="number" name="dongia" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Danh Mục</label>
                                    <select name="madm" class="form-select">
                                        <?php
                                        // Lấy danh mục từ DB đổ vào select box
                                        $dm_sql = "SELECT * FROM DANH_MUC";
                                        $dm_res = $conn->query($dm_sql);
                                        while ($dm = $dm_res->fetch_assoc()) {
                                            echo "<option value='" . $dm['MADM'] . "'>" . $dm['TENDM'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình Ảnh</label>
                                <input type="file" name="hinh" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Thông Số Kỹ Thuật</label>
                                <textarea name="thongso" class="form-control" rows="3" placeholder="Công suất: 1HP..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô Tả Chi Tiết</label>
                                <textarea name="motasp" class="form-control" rows="5"></textarea>
                            </div>

                            <button type="submit" name="btn-save" class="btn btn-primary">Lưu Sản Phẩm</button>
                            <a href="../../Frontend/views/QLSP.php" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>