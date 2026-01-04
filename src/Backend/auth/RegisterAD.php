<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';

$thongbao = ""; // Biến lưu thông báo

if (isset($_POST['btn-reg'])) {
    // Lấy dữ liệu từ form
    $MAQT = $conn->real_escape_string($_POST['MAQT']);
    $MATKHAU = $_POST['MATKHAU'];
    $NHAPLAI = $_POST['NHAPLAI'];
    
    // 1. Kiểm tra rỗng
    if (empty($MAQT) || empty($MATKHAU) ) {
        $thongbao = "Vui lòng điền đầy đủ thông tin bắt buộc!";
    } 
    // 2. Kiểm tra mật khẩu khớp
    elseif ($MATKHAU != $NHAPLAI) {
        $thongbao = "Mật khẩu nhập lại không khớp!";
    } 
    else {
        // 3. Kiểm tra Username hoặc Mã QT đã tồn tại chưa
        $check_sql = "SELECT MAQT FROM ADMIN WHERE MAQT = '$MAQT' ";
        if ($conn->query($check_sql)->num_rows > 0) {
            $thongbao = "Mã Quản trị đã tồn tại!";
        } else {
            // 4. Mã hóa mật khẩu (BẢO MẬT)
            $hash_pass = password_hash($MATKHAU, PASSWORD_DEFAULT);

            // 5. Chèn vào bảng ADMIN
            $sql = "INSERT INTO ADMIN (MAQT, MATKHAU) 
                    VALUES ('$MAQT', '$hash_pass')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('Đăng ký tài khoản ADMIN thành công! Vui lòng Đăng nhập.');
                        window.location.href='LoginAD.php'; 
                      </script>";
                exit();
            } else {
                $thongbao = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class=" d-flex align-items-center justify-content-center min-vh-100">

    <div class=" card shadow" style="width: 400px;">
        <div class="bg-primary card-header text-white text-center">
            <h4 class="mb-0">TẠO TÀI KHOẢN ADMIN</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if ($thongbao != ""): ?>
                <div class="alert alert-danger text-center"><?php echo $thongbao; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Mã Quản Trị (VD: AD01)</label>
                    <input type="text" name="MAQT" class="form-control" required>
                </div>
                
                

                <div class="mb-3">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="MATKHAU" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nhập lại Mật khẩu</label>
                    <input type="password" name="NHAPLAI" class="form-control" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" name="btn-reg" class="btn bg-primary text-white fw-bold">ĐĂNG KÝ ADMIN</button>
                </div>
                
            </form>
            <div class="text-center mt-3">
                <a href="LoginAD.php" class="text-decoration-none small">Đã có tài khoản? Đăng nhập ngay</a>
            </div>
        </div>
    </div>

</body>
</html>