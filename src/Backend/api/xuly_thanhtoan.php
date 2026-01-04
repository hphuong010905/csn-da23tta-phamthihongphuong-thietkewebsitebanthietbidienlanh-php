<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';

if (isset($_POST['btn_dathang'])) {
    
    // --- PHẦN 1: TÌM THÔNG TIN KHÁCH HÀNG (Logic thông minh) ---
    $kh = null;
    
    // Ưu tiên 1: Tìm theo ID (nếu đã đăng nhập mới)
    if (isset($_SESSION['kh_id'])) {
        $makh_check = $_SESSION['kh_id'];
        $sql_kh = "SELECT * FROM KHACH_HANG WHERE MAKH = '$makh_check'";
        $result_kh = $conn->query($sql_kh);
        if($result_kh->num_rows > 0) $kh = $result_kh->fetch_assoc();
    }
    
    // Ưu tiên 2: Tìm theo Tên (nếu đang dùng phiên cũ)
    if ($kh == null && isset($_SESSION['kh_name'])) {
        $tenkh = $_SESSION['kh_name'];
        $sql_kh = "SELECT * FROM KHACH_HANG WHERE TENKH = '$tenkh'";
        $result_kh = $conn->query($sql_kh);
        if($result_kh->num_rows > 0) $kh = $result_kh->fetch_assoc();
    }

    // Nếu vẫn không tìm thấy thì chặn lại
    if ($kh == null) {
        echo "<script>alert('Phiên đăng nhập lỗi. Vui lòng đăng nhập lại!'); window.location.href='LoginUser.php';</script>";
        exit();
    }

    // Lấy thông tin chuẩn từ CSDL
    $makh_chuan = $kh['MAKH']; 
    $diachi_giao = $_POST['diachi_nguoinhan']; 
    $sdt_giao    = $_POST['sdt_nguoinhan'];

    // --- PHẦN 2: LẤY DỮ LIỆU TỪ FORM ---
    $tong_tien = $_POST['tong_tien'];
    $pttt = $_POST['pttt'];
    
    // --- PHẦN 3: TẠO MÃ ĐƠN HÀNG (SỬA LỖI DATA TOO LONG) ---
    // Cột MADDH trong DB là varchar(10). 
    // Ta dùng hàm time() sẽ trả về đúng 10 con số (Ví dụ: 1708234567) -> Vừa khít!
    $maddh = time(); 
    
    $ngaydh = date('Y-m-d H:i:s');

    // --- PHẦN 4: LƯU ĐƠN HÀNG ---
    $sql_insert_dh = "INSERT INTO don_dat_hang (MADDH, MAKH, NGAYDH, TONGTIEN, HINHTHUC_TT, TRANGTHAI, DIACHI_GIAO, SDT_GIAO) 
                      VALUES ('$maddh', '$makh_chuan', '$ngaydh', '$tong_tien', '$pttt', 'ChoDuyet', '$diachi_giao', '$sdt_giao')";
    if ($conn->query($sql_insert_dh) === TRUE) {
        
        // Lưu chi tiết đơn hàng
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $masp => $soluong) {
                // Lấy giá hiện tại
                $sql_gia = "SELECT DONGIA FROM san_pham WHERE MASP = '$masp'"; 
                $check_gia = $conn->query($sql_gia);
                
                if ($check_gia->num_rows > 0) {
                    $dongia = $check_gia->fetch_assoc()['DONGIA'];
                    
                    // Insert chi tiết (Lưu ý: MASP phải để trong dấu nháy '$masp' vì là varchar)
                    $sql_ct = "INSERT INTO chi_tiet_don_hang (MADDH, MASP, SOLUONG, DONGIA) 
                               VALUES ('$maddh', '$masp', $soluong, $dongia)";
                    $conn->query($sql_ct);
                }
            }
        }

        // Xóa giỏ hàng và thông báo thành công
        unset($_SESSION['cart']);
        echo "<script>
                alert('Đặt hàng thành công! Mã đơn: #$maddh');
                window.location.href = 'index.php'; 
              </script>";
    } else {
        // In lỗi chi tiết nếu Insert thất bại
        echo "Lỗi hệ thống: " . $conn->error;
    }
}
?>