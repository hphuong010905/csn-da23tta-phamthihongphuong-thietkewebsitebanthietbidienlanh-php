<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// 1. Kiểm tra đăng nhập và giỏ hàng
// Chấp nhận cả trường hợp chỉ có tên (phiên đăng nhập cũ)
if ((!isset($_SESSION['kh_id']) && !isset($_SESSION['kh_name'])) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// 2. Tính lại tổng tiền
$list_id = array_keys($_SESSION['cart']);
$str_id = "'" . implode("','", $list_id) . "'";
$sql = "SELECT * FROM SAN_PHAM WHERE MASP IN ($str_id)";
$result = $conn->query($sql);

$tong_tien = 0;
while ($row = $result->fetch_assoc()) {
    $tong_tien += $row['DONGIA'] * $_SESSION['cart'][$row['MASP']];
}

// 3. LẤY THÔNG TIN KHÁCH HÀNG (ĐOẠN QUAN TRỌNG NHẤT) =======================
$kh = null; // Khởi tạo biến rỗng

if (isset($_SESSION['kh_id'])) {
    // TRƯỜNG HỢP 1: Đã nâng cấp code Login (Có ID) -> Tìm chính xác theo ID
    $makh = $_SESSION['kh_id'];
    $sql_kh = "SELECT * FROM KHACH_HANG WHERE MAKH = '$makh'";
    $result_kh = $conn->query($sql_kh);
    if($result_kh->num_rows > 0) $kh = $result_kh->fetch_assoc();
} 

// Nếu chưa tìm thấy (do dùng phiên đăng nhập cũ chưa có ID), tìm tiếp bằng Tên
if ($kh == null && isset($_SESSION['kh_name'])) {
    // TRƯỜNG HỢP 2: Tìm theo Tên (Fallback)
    $tenkh = $_SESSION['kh_name'];
    $sql_kh = "SELECT * FROM KHACH_HANG WHERE TENKH = '$tenkh'"; // Sửa MAKH thành TENKH
    $result_kh = $conn->query($sql_kh);
    if($result_kh->num_rows > 0) $kh = $result_kh->fetch_assoc();
}

// Kiểm tra lần cuối, nếu vẫn không thấy thì bắt buộc đăng nhập lại
if ($kh == null) {
    echo "<script>
            alert('Phiên đăng nhập đã hết hạn hoặc lỗi dữ liệu. Vui lòng đăng nhập lại!');
            window.location.href = 'LoginUser.php'; // Chuyển về trang đăng nhập
          </script>";
    exit();
}
// ============================================================================
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4 text-uppercase fw-bold text-primary">Xác nhận thanh toán</h2>
    
    <div class="row">
        <div class="col-md-7">
    
    <form action="xuly_thanhtoan.php" method="POST" id="formThanhToan">
        
        <input type="hidden" name="tong_tien" value="<?php echo $tong_tien; ?>">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-location-dot me-2 text-danger"></i> Thông tin giao hàng
            </div>
            <div class="card-body">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Người nhận hàng:</label>
                    <input type="text" class="form-control" name="ten_nguoinhan" 
                           value="<?php echo $kh['TENKH']; ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Số điện thoại:</label>
                        <input type="text" class="form-control" name="sdt_nguoinhan" 
                               value="<?php echo $kh['SDTKH']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Email (nhận hóa đơn):</label>
                        <input type="email" class="form-control bg-light" value="<?php echo $kh['EMAIL']; ?>" readonly> 
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Địa chỉ nhận hàng (Có thể sửa):</label>
                    <textarea class="form-control" name="diachi_nguoinhan" rows="2" required><?php echo $kh['DCHIKH']; ?></textarea>
                </div>
                
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-wallet me-2 text-success"></i> Phương thức thanh toán
            </div>
            <div class="card-body">
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="pttt" id="tt_cod" value="COD" checked>
                    <label class="form-check-label fw-bold" for="tt_cod">
                        <i class="fa-solid fa-money-bill-wave text-success me-2"></i> Thanh toán khi nhận hàng (COD)
                    </label>
                    <div class="text-muted small ms-4">Bạn sẽ thanh toán tiền mặt cho shipper khi nhận được hàng.</div>
                </div>

                <hr>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="pttt" id="tt_qr" value="QR">
                    <label class="form-check-label fw-bold" for="tt_qr">
                        <i class="fa-solid fa-qrcode text-primary me-2"></i> Chuyển khoản ngân hàng (QR Code)
                    </label>
                </div>

                <div id="qr_area" class="text-center p-3 border rounded bg-light d-none">
                    <p class="mb-2 fw-bold text-danger">Quét mã ZaloPay / Ngân hàng để thanh toán:</p>
                    <img src="img\now\qr.jpg" class="img-fluid border shadow-sm" style="max-width: 250px;" alt="Mã QR">
                    <p class="mt-3 fw-bold text-primary">
                        Vui lòng nhập số tiền: <span class="text-danger fs-5"><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
                    </p>
                    <p class="small text-muted fst-italic mb-0">Nội  dung chuyển khoản: Thanh toan don hang [Tên bạn]</p>
                </div>

            </div>
        </div>

        <button type="submit" name="btn_dathang" class="btn btn-danger w-100 py-3 mt-4 fw-bold text-uppercase fs-5 shadow">
            Xác nhận Đặt Hàng - <?php echo number_format($tong_tien, 0, ',', '.'); ?>₫
        </button>

    </form> </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Đơn hàng của bạn</div>
                <div class="card-body">
                   <div class="d-flex justify-content-between mb-2">
                       <span>Tạm tính:</span>
                       <span class="fw-bold"><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
                   </div>
                   <div class="d-flex justify-content-between mb-2 text-success">
                       <span>Phí vận chuyển:</span>
                       <span>Miễn phí</span>
                   </div>
                   <hr>
                   <div class="d-flex justify-content-between fs-4 fw-bold text-danger">
                       <span>Tổng cộng:</span>
                       <span><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script JS để Ẩn/Hiện mã QR
    const radioCOD = document.getElementById('tt_cod');
    const radioQR = document.getElementById('tt_qr');
    const qrArea = document.getElementById('qr_area');

    radioCOD.addEventListener('change', function() {
        if(this.checked) qrArea.classList.add('d-none');
    });

    radioQR.addEventListener('change', function() {
        if(this.checked) qrArea.classList.remove('d-none');
    });
</script>

</body>
</html>