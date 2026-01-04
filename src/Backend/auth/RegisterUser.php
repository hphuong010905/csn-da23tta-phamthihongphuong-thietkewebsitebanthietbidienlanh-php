<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';

if (isset($_POST['btn-reg'])) {
    // Lấy dữ liệu từ form
    $EMAIL = $_POST['EMAIL'];
    $TENKH = $_POST['TENKH'];
    $SDTKH = $_POST['SDTKH'];
    $DCHIKH = $_POST['DCHIKH'];
    $MK = $_POST['MK'];
    $nhaplai = $_POST['nhaplai'];

    // 1. Kiểm tra rỗng
    if ( empty($TENKH) || empty($MK) || empty($EMAIL)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin bắt buộc!');</script>";
    } 
    // 2. Kiểm tra mật khẩu khớp
    elseif ($MK != $nhaplai) {
        echo "<script>alert('Mật khẩu nhập lại không khớp!');</script>";
    } 
    else {
        // 3. Kiểm tra Mã Khách Hàng đã tồn tại chưa
        $check_sql = "SELECT * FROM KHACH_HANG WHERE EMAIL = '$EMAIL'";
        if ($conn->query($check_sql)->num_rows > 0) {
            echo "<script>alert('Email này đã được sử dụng!');</script>";
        } else {
            // 4. Xử lý dữ liệu
            $pass_hash = password_hash($MK, PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $ngaydk = date("Y-m-d H:i:s"); // Lấy ngày giờ hiện tại

            // 5. Chèn vào bảng KHACH_HANG
            // Lưu ý: MAQT để NULL vì khách tự đăng ký, không ai quản lý lúc này
            $sql = "INSERT INTO KHACH_HANG ( EMAIL,TENKH,  SDTKH, DCHIKH, MK, NGAYDK) 
                    VALUES ('$EMAIL', '$TENKH', '$SDTKH', '$DCHIKH', '$pass_hash', '$ngaydk')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('Đăng ký thành viên thành công!');
                        window.location.href='LoginUser.php'; // Chuyển sang trang đăng nhập (sẽ làm sau)
                      </script>";
            } else {
                echo "Lỗi: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="bootstrap-5.3.6-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8"> <div class="card shadow rounded-4 border-0">
                    <div class="card-header bg-success text-white text-center py-3 rounded-top-4">
                        <h3 class="mb-0">ĐĂNG KÝ</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">EMAIL (*)</label>
                                    <input type="text" name="EMAIL" class="form-control" required placeholder="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Họ và tên (*)</label>
                                    <input type="text" name="TENKH" class="form-control" required placeholder="">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Số điện thoại</label>
                                    <input type="text" name="SDTKH" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <input type="text" name="DCHIKH" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mật Khẩu (*)</label>
                                    <input type="password" name="MK" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nhập Lại Mật Khẩu (*)</label>
                                    <input type="password" name="nhaplai" class="form-control" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" name="btn-reg" class="btn btn-success btn-lg">Đăng Ký</button>
                            </div>

                            <div class="text-center mt-3">
                                <span>Đã có tài khoản? </span>
                                <a href="LoginUser.php" class="text-decoration-none fw-bold text-success">Đăng nhập</a>
                            </div>
                            <div class="text-center mt-2">
                                <a href="index.php" class="text-secondary text-decoration-none">← Về trang chủ</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>