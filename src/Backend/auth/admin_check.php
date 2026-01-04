<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
// 1. Kiểm tra xem phiên làm việc (Session) đã bật chưa, nếu chưa thì bật lên
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Kiểm tra xem có "thẻ thông hành" (admin_id) hay không?
// Biến $_SESSION['admin_id'] này được tạo ra ở trang dangnhap_admin.php khi đăng nhập thành công.
if (!isset($_SESSION['admin_id'])) {
    
    // Nếu KHÔNG tìm thấy session (tức là chưa đăng nhập hoặc hack):
    echo "<script>
            alert('CẢNH BÁO: Bạn chưa đăng nhập quyền Quản trị!');
            window.location.href = 'LoginAD.php'; // Đuổi về trang đăng nhập ngay lập tức
          </script>";
    
    exit(); // Lệnh này cực quan trọng: Dừng toàn bộ mã lệnh phía sau, không cho chạy tiếp.
}
?>

