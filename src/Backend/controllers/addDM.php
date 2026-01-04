<?php
// 1. Cấu hình & Bảo mật
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../auth/admin_check.php'; 
require_once __DIR__ . '/../config/ConnectDB.php';

// 2. XỬ LÝ THÊM MỚI
if (isset($_POST['btn_add'])) {
    $ma = trim($_POST['madm']);
    $ten = trim($_POST['tendm']);

    if (empty($ma) || empty($ten)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
    } else {
        $check = $conn->query("SELECT * FROM DANH_MUC WHERE MADM = '$ma'");
        if ($check->num_rows > 0) {
            echo "<script>alert('Lỗi: Mã danh mục [$ma] đã tồn tại!');</script>";
        } else {
            $sql = "INSERT INTO DANH_MUC (MADM, TENDM) VALUES ('$ma', '$ten')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Thêm thành công!'); window.location.href='../../Frontend/views/QLDM.php';</script>";
                exit();
            } else {
                echo "<script>alert('Lỗi SQL: " . $conn->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh Mục Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="../../Frontend/views/QLDM.php">
                <i class="fa-solid fa-gauge-high me-2"></i> Về Dashboard
            </a>
            
            <div class="ms-auto text-white">
                <span class="me-2">Xin chào, <b><?php echo $_SESSION['admin_id']; ?></b></span>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-warning text-white fw-bold">
                        <i class="fa-solid fa-plus-circle me-2"></i> THÊM DANH MỤC MỚI
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã Danh Mục (VD: DM01)</label>
                                <input type="text" name="madm" class="form-control" required placeholder="Nhập mã...">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tên Danh Mục (VD: Máy Lạnh)</label>
                                <input type="text" name="tendm" class="form-control" required placeholder="Nhập tên...">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="btn_add" class="btn btn-warning text-white fw-bold w-100">
                                    <i class="fa-solid fa-save me-1"></i> Lưu Lại
                                </button>
                                
                                <a href="../../Frontend/views/QLDM.php" class="btn btn-secondary w-100">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại DS
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="../../Frontend/views/QTHT.php" class="text-decoration-none text-muted small">
                        <i class="fa-solid fa-house"></i> Trở về trang chủ quản trị
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>