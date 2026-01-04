<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
// --- SỬA LỖI 1: Kiểm tra file kết nối ---
// Đảm bảo bạn đã có file 'ketnoi.php' trong cùng thư mục
if (file_exists(__DIR__ . '/../config/ConnectDB.php')) {
    require_once __DIR__ . '/../config/ConnectDB.php';
} else {
    die("<div style='color:red; text-align:center; padding:20px;'>
            LỖI: Không tìm thấy file <b>ketnoi.php</b>!<br>
            Vui lòng kiểm tra lại tên file trong thư mục C:/laragon/www/CSN/
         </div>");
}

if (isset($_POST['btn-login'])) {
    $maqt = $_POST['maqt'];
    $matkhau = $_POST['matkhau'];

    if (empty($maqt) || empty($matkhau)) {
        echo "<script>alert('Vui lòng nhập đủ thông tin!');</script>";
    } else {
        $sql = "SELECT * FROM ADMIN WHERE MAQT = '$maqt'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($matkhau, $row['MATKHAU'])) {
                $_SESSION['admin_id'] = $row['MAQT'];
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href='../../Frontend/views/QTHT.php';</script>";
            } else {
                echo "<script>alert('Sai mật khẩu!');</script>";
            }
        } else {
            echo "<script>alert('Mã quản trị không tồn tại!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>

        body {

            background-color: #f0f2f5;

            height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

        }

        .login-card {

            width: 100%;

            max-width: 400px;

        }

    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg p-4">
                    <div class="text-center mb-4">
                        <h3 class="text-primary fw-bold">QUẢN TRỊ VIÊN</h3>
                        <p class="text-muted">Đăng nhập để quản lý hệ thống</p>
                    </div>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã Quản Trị</label>
                            <input type="text" name="maqt" class="form-control" required placeholder="Nhập mã (VD: AD01)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật Khẩu</label>
                            <input type="password" name="matkhau" class="form-control" required placeholder="Nhập mật khẩu">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="btn-login" class="btn btn-primary btn-lg">Đăng Nhập</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="index.php" class="text-decoration-none">← Quay về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>