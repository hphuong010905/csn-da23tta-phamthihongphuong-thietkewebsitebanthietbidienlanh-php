<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';
require_once __DIR__ . '/../auth/admin_check.php';

// 1. Lấy ID sản phẩm cần sửa
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM SAN_PHAM WHERE MASP = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
} else {
    header("Location: ../../Frontend/views/QLSP.php");
}

// 2. Xử lý khi nhấn nút Cập Nhật
if (isset($_POST['btn-update'])) {
    $tensp = $_POST['tensp'];
    $soluong = isset($_POST['soluong']) ? $_POST['soluong'] : 0;
    $dongia = $_POST['dongia'];
    $madm = $_POST['madm'];
    $motasp = $_POST['motasp'];
    $thongso = $_POST['thongso'];

    // Xử lý ảnh: Nếu có chọn ảnh mới thì cập nhật, không thì giữ nguyên
    if (isset($_FILES['hinh']) && $_FILES['hinh']['tmp_name'] != "") {
        $hinh_data = addslashes(file_get_contents($_FILES['hinh']['tmp_name']));
        // Câu lệnh Update CÓ cập nhật hình ảnh
        $sql_update = "UPDATE SAN_PHAM SET 
                       MADM='$madm', TENSP='$tensp', SOLUONG='$soluong', DONGIA='$dongia', 
                       MOTASP='$motasp', THONGSOKYTHUAT='$thongso', HINHANHSP='$hinh_data' 
                       WHERE MASP='$id'";
    } else {
        // Câu lệnh Update KHÔNG cập nhật hình ảnh (giữ ảnh cũ)
        $sql_update = "UPDATE SAN_PHAM SET 
                       MADM='$madm', TENSP='$tensp', SOLUONG='$soluong', DONGIA='$dongia', 
                       MOTASP='$motasp', THONGSOKYTHUAT='$thongso' 
                       WHERE MASP='$id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='../../Frontend/views/QLSP.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">CẬP NHẬT SẢN PHẨM: <?php echo $id; ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã Sản Phẩm</label>
                                <input type="text" class="form-control" value="<?php echo $row['MASP']; ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên Sản Phẩm</label>
                                <input type="text" name="tensp" class="form-control" value="<?php echo $row['TENSP']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số Lượng</label>
                                <input type="number" name="soluong" class="form-control" value="<?php echo isset($row['SOLUONG']) ? $row['SOLUONG'] : 0; ?>" required min="0">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Giá Tiền</label>
                                    <input type="number" name="dongia" class="form-control" value="<?php echo $row['DONGIA']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Danh Mục</label>
                                    <select name="madm" class="form-select">
                                        <?php
                                        $dm_sql = "SELECT * FROM DANH_MUC";
                                        $dm_res = $conn->query($dm_sql);
                                        while ($dm = $dm_res->fetch_assoc()) {
                                            // Kiểm tra nếu danh mục này trùng với danh mục hiện tại của SP thì 'selected'
                                            $selected = ($dm['MADM'] == $row['MADM']) ? 'selected' : '';
                                            echo "<option value='" . $dm['MADM'] . "' $selected>" . $dm['TENDM'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình Ảnh (Để trống nếu không thay đổi)</label>
                                <input type="file" name="hinh" class="form-control" accept="image/*">
                                <?php if($row['HINHANHSP']): ?>
                                    <div class="mt-2">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['HINHANHSP']); ?>" style="height: 100px;">
                                        <small class="text-muted">Ảnh hiện tại</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Thông Số Kỹ Thuật</label>
                                <textarea name="thongso" class="form-control" rows="3"><?php echo $row['THONGSOKYTHUAT']; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô Tả Chi Tiết</label>
                                <textarea name="motasp" class="form-control" rows="5"><?php echo $row['MOTASP']; ?></textarea>
                            </div>

                            <button type="submit" name="btn-update" class="btn btn-warning fw-bold">Cập Nhật</button>
                            <a href="../../Frontend/views/QLSP.php" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>