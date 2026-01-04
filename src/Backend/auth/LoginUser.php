<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php'; 

if (isset($_POST['btn-login'])) {
    $input_user = $_POST['username']; // Có thể là Email (Khách) hoặc Mã Admin
    $password = $_POST['password'];

    // --- KIỂM TRA 1: CÓ PHẢI ADMIN KHÔNG? ---
    // Giả sử Admin đăng nhập bằng MAQT
    $sql_admin = "SELECT * FROM ADMIN WHERE MAQT = '$input_user'";
    $res_admin = $conn->query($sql_admin);

    if ($res_admin->num_rows > 0) {
        $row_ad = $res_admin->fetch_assoc();
        // Kiểm tra mật khẩu Admin
        if (password_verify($password, $row_ad['MATKHAU'])) {
            // Đăng nhập thành công -> Lưu session Admin
            $_SESSION['admin_id'] = $row_ad['MAQT'];
            $_SESSION['admin_name'] = $row_ad['TENQT'];
            
            echo "<script>alert('Xin chào Admin!'); window.location.href='../../Frontend/views/QTHT.php';</script>";
            exit();
        }
    }

    // --- KIỂM TRA 2: CÓ PHẢI KHÁCH HÀNG KHÔNG? (Nếu không phải Admin) ---
    // Giả sử Khách đăng nhập bằng EMAIL
    $sql_user = "SELECT * FROM KHACH_HANG WHERE EMAIL = '$input_user'";
    $res_user = $conn->query($sql_user);

    if ($res_user->num_rows > 0) {
        $row_user = $res_user->fetch_assoc();
        // Kiểm tra mật khẩu Khách (Cột MK trong DB)
        if (password_verify($password, $row_user['MK'])) {
            // Đăng nhập thành công -> Lưu session Khách
            $_SESSION['kh_email'] = $row_user['EMAIL'];
            $_SESSION['kh_name'] = $row_user['TENKH'];
            
            echo "<script>alert('Đăng nhập thành công!'); window.location.href='../../Frontend/views/index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Sai mật khẩu!');</script>";
        }
    } else {
        // Nếu tìm cả 2 bảng đều không thấy
        echo "<script>alert('Tài khoản không tồn tại hoặc sai thông tin!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow rounded-4 p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center text-primary fw-bold mb-4">ĐĂNG NHẬP</h3>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Tài khoản</label>
                <input type="text" name="username" class="form-control" required placeholder="">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="">
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" name="btn-login" class="btn btn-primary btn-lg fw-bold">Đăng Nhập</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Khách hàng mới?</span> 
            <a href="RegisterUser.php" class="text-decoration-none fw-bold">Đăng ký ngay</a>
        </div>
        <div class="text-center mt-2">
            <a href="index.php" class="text-secondary text-decoration-none">← Về trang chủ</a>
        </div>
    </div>

</body>
</html>