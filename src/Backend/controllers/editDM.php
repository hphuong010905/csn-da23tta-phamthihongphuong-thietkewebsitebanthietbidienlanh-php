<?php
// 1. Cấu hình & Bảo mật
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../auth/admin_check.php'; 
require_once __DIR__ . '/../config/ConnectDB.php';

// --- BIẾN CHỨA DỮ LIỆU ---
$id = '';
$ten_hien_tai = '';

// 2. LẤY DỮ LIỆU CẦN SỬA (Từ URL id=...)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM DANH_MUC WHERE MADM = '$id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ten_hien_tai = $row['TENDM'];
    } else {
        // Nếu id không tồn tại -> Quay về trang danh sách
        header("Location: ../../Frontend/views/QLDM.php");
        exit();
    }
} else {
    // Không có id trên URL -> Quay về
    header("Location: ../../Frontend/views/QLDM.php");
    exit();
}

// 3. XỬ LÝ CẬP NHẬT (Khi bấm nút Lưu)
if (isset($_POST['btn_update'])) {
    $ten_moi = trim($_POST['tendm']); // Mã không cần lấy từ POST vì đã có $id

    if (empty($ten_moi)) {
        echo "<script>alert('Tên danh mục không được để trống!');</script>";
    } else {
        $sql_update = "UPDATE DANH_MUC SET TENDM = '$ten_moi' WHERE MADM = '$id'";
        
        if ($conn->query($sql_update) === TRUE) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href='../../Frontend/views/QLDM.php';</script>";
            exit();
        } else {
            echo "<script>alert('Lỗi SQL: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh Mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="../../Frontend/views/QTHT.php">
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
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2"></i> CẬP NHẬT DANH MỤC
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã Danh Mục</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $id; ?>" readonly>
                                <div class="form-text text-muted"><i class="fa-solid fa-circle-info"></i> Mã danh mục không thể thay đổi.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tên Danh Mục</label>
                                <input type="text" name="tendm" class="form-control" value="<?php echo $ten_hien_tai; ?>" required placeholder="Nhập tên mới...">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="btn_update" class="btn btn-primary fw-bold w-100">
                                    <i class="fa-solid fa-save me-1"></i> Cập Nhật
                                </button>
                                
                                <a href="../../Frontend/views/QLDM.php" class="btn btn-secondary w-100">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>