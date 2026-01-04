<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['kh_id'])) {
    // Nếu session id bị mất, thử tìm lại bằng tên (cơ chế fallback)
    if (isset($_SESSION['kh_name'])) {
        $name = $_SESSION['kh_name'];
        $res = $conn->query("SELECT MAKH FROM KHACH_HANG WHERE TENKH = '$name'");
        if ($res->num_rows > 0) $_SESSION['kh_id'] = $res->fetch_assoc()['MAKH'];
    } else {
        header("Location: ../../Backend/auth/LoginUser.php");
        exit();
    }
}
$makh = $_SESSION['kh_id'];

// 2. XỬ LÝ HỦY ĐƠN HÀNG (Logic quan trọng)
if (isset($_GET['action']) && $_GET['action'] == 'huy' && isset($_GET['madh'])) {
    $madh_huy = $_GET['madh'];
    
    // Kiểm tra xem đơn này có đúng là của khách này và đang CHỜ DUYỆT không
    $check_sql = "SELECT TRANGTHAI FROM don_dat_hang WHERE MADDH = '$madh_huy' AND MAKH = '$makh'";
    $res_check = $conn->query($check_sql);
    
    if ($res_check->num_rows > 0) {
        $row_check = $res_check->fetch_assoc();
        if ($row_check['TRANGTHAI'] == 'ChoDuyet') {
            // Cập nhật trạng thái thành 'Huy' ngay lập tức
            $conn->query("UPDATE don_dat_hang SET TRANGTHAI = 'Huy' WHERE MADDH = '$madh_huy'");
            
            // Thông báo và tải lại trang để thấy kết quả
            echo "<script>alert('Đã hủy đơn hàng thành công!'); window.location.href='Donmua.php?status=Huy';</script>";
        } else {
            echo "<script>alert('Không thể hủy! Đơn hàng đã được duyệt hoặc đang giao.'); window.location.href='Donmua.php';</script>";
        }
    }
}

// 3. XỬ LÝ LỌC TRẠNG THÁI (MENU TABS)
// Mặc định là 'all' nếu không chọn tab nào
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Tạo câu SQL cơ bản
$sql_dh = "SELECT * FROM don_dat_hang WHERE MAKH = '$makh'";

// Ghép thêm điều kiện lọc dựa vào Tab đang chọn
if ($status == 'ChoDuyet') {
    $sql_dh .= " AND TRANGTHAI = 'ChoDuyet'";
} elseif ($status == 'DaDuyet') { // Đang giao
    $sql_dh .= " AND TRANGTHAI = 'DaDuyet'";
} elseif ($status == 'DaGiao') {  // Hoàn thành
    $sql_dh .= " AND TRANGTHAI = 'DaGiao'";
} elseif ($status == 'Huy') {     // Đã hủy
    $sql_dh .= " AND TRANGTHAI = 'Huy'";
}

$sql_dh .= " ORDER BY NGAYDH DESC"; // Đơn mới nhất lên đầu
$result_dh = $conn->query($sql_dh);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn mua của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f5fa; }
        
        /* Style Sidebar */
        .profile-card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .sidebar-menu a { display: block; padding: 12px 0; color: #333; text-decoration: none; display: flex; align-items: center; }
        .sidebar-menu a:hover { color: #d0011b; }
        .sidebar-menu a.active { color: #d0011b; font-weight: 500; }
        .sidebar-menu i { width: 30px; font-size: 1.2rem; }

        /* Style Tabs Menu */
        .nav-tabs { background: white; border-radius: 2px; border-bottom: 1px solid #e5e5e5; }
        .nav-tabs .nav-link { color: #555; border: none; padding: 15px 0; margin: 0 20px; font-weight: 500; background: transparent; }
        .nav-tabs .nav-link:hover { color: #d0011b; }
        .nav-tabs .nav-link.active { color: #d0011b; border-bottom: 2px solid #d0011b; }

        /* Style Đơn hàng */
        .order-card { background: #fff; margin-top: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); border-radius: 2px; }
        .order-header { padding: 15px 20px; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center; }
        .order-status { text-transform: uppercase; font-weight: 600; font-size: 0.9rem; }
        .order-body { padding: 20px; }
        .item-img { width: 80px; height: 80px; object-fit: contain; border: 1px solid #e1e1e1; background: #fafafa; }
        .order-footer { padding: 20px; background: #fffafb; border-top: 1px dashed #eaeaea; display: flex; justify-content: flex-end; align-items: center; gap: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #004e92;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-chevron-left me-2"></i> Trở về trang chủ</a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">Xin chào, <?php echo $_SESSION['kh_name']; ?></span>
            <li class="nav-item">
                            <?php
                            $cart_count = 0;
                            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                                $cart_count = array_sum($_SESSION['cart']);
                            }
                            ?>
                            <a href="Giohang.php" class="text-white text-decoration-none position-relative ms-3 hover-opacity" style="font-size: 1.2rem;">
                                
                                <i class="fa-solid fa-cart-shopping"></i>
                                
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem; padding: 0.25em 0.4em;">
                                    <?php echo $cart_count; ?>
                                    <span class="visually-hidden">sản phẩm trong giỏ</span>
                                </span>
                            </a>                   
                        </li>
        </div>
    </div>
</nav>

<!-- Menu Danh Mục Đồng Bộ -->
<div class="row mb-3 shadow-sm" style="background-color: #f8f9fa; margin-top: 80px;">
    <div class="col-12 text-center py-2">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="danhmuc.php?madm=ML" class="btn fw-bold text-dark">Máy Lạnh</a>
            <a href="danhmuc.php?madm=MG" class="btn fw-bold text-dark">Máy Giặt</a>
            <a href="danhmuc.php?madm=MS" class="btn fw-bold text-dark">Máy sấy quần áo</a>
            <a href="danhmuc.php?madm=TL" class="btn fw-bold text-dark">Tủ Lạnh</a>
            <a href="danhmuc.php?madm=TM" class="btn fw-bold text-dark">Tủ mát</a>
            <a href="danhmuc.php?madm=TD" class="btn fw-bold text-dark">Tủ đông</a>
            <a href="danhmuc.php?madm=MNN" class="btn fw-bold text-dark">Máy nước nóng</a>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 20px;">
    <div class="row">
        
        <div class="col-md-3">
            <div class="profile-card mb-3">
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white me-3" style="width: 50px; height: 50px; font-size: 20px;">
                        <?php echo substr($_SESSION['kh_name'], 0, 1); ?>
                    </div>
                    <div>
                        <div class="fw-bold"><?php echo $_SESSION['kh_name']; ?></div>
                        <a href="profileUser.php" class="text-muted small"><i class="fa-solid fa-pen"></i> Sửa hồ sơ</a>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <a href="profileUser.php"><i class="fa-regular fa-user text-primary"></i> Tài khoản của tôi</a>
                    <a href="Donmua.php" class="active"><i class="fa-solid fa-file-invoice-dollar text-primary"></i> Đơn mua</a>
                    <a href="../../Backend/auth/LogoutUser.php" class="text-danger"><i class="fa-solid fa-right-from-bracket text-primary"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            
            <ul class="nav nav-tabs nav-fill mb-3 bg-white shadow-sm">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status=='all')?'active':''; ?>" href="Donmua.php?status=all">Tất cả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status=='ChoDuyet')?'active':''; ?>" href="Donmua.php?status=ChoDuyet">Chờ duyệt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status=='DaDuyet')?'active':''; ?>" href="Donmua.php?status=DaDuyet">Đang giao</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status=='DaGiao')?'active':''; ?>" href="Donmua.php?status=DaGiao">Hoàn thành</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($status=='Huy')?'active':''; ?>" href="Donmua.php?status=Huy">Đã hủy</a>
                </li>
            </ul>

            <?php if ($result_dh->num_rows > 0): ?>
                <?php while ($dh = $result_dh->fetch_assoc()): ?>
                    
                    <?php 
                        // Xử lý hiển thị màu trạng thái
                        $txt_stt = ''; $color_stt = '';
                        switch ($dh['TRANGTHAI']) {
                            case 'ChoDuyet': $txt_stt = 'CHỜ DUYỆT'; $color_stt = 'text-warning'; break;
                            case 'DaDuyet':  $txt_stt = 'ĐANG GIAO HÀNG'; $color_stt = 'text-primary'; break;
                            case 'DaGiao':   $txt_stt = 'HOÀN THÀNH'; $color_stt = 'text-success'; break;
                            case 'Huy':      $txt_stt = 'ĐÃ HỦY'; $color_stt = 'text-danger'; break;
                        }
                    ?>

                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="fw-bold">Đơn hàng #<?php echo $dh['MADDH']; ?></span>
                                <span class="mx-2 text-muted">|</span>
                                <span class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($dh['NGAYDH'])); ?></span>
                            </div>
                            <div class="order-status <?php echo $color_stt; ?>">
                                <i class="fa-solid fa-truck-fast me-1"></i> <?php echo $txt_stt; ?>
                            </div>
                        </div>

                        <div class="order-body">
                            <?php 
                                $curr_madh = $dh['MADDH'];
                                $sql_ct = "SELECT ct.*, sp.TENSP, sp.HINHANHSP 
                                           FROM chi_tiet_don_hang ct 
                                           JOIN san_pham sp ON ct.MASP = sp.MASP 
                                           WHERE ct.MADDH = '$curr_madh'";
                                $res_ct = $conn->query($sql_ct);
                                while($sp = $res_ct->fetch_assoc()):
                                    $img = $sp['HINHANHSP'] ? 'data:image/jpeg;base64,'.base64_encode($sp['HINHANHSP']) : 'img/no-image.png';
                            ?>
                                <div class="d-flex align-items-center border-bottom pb-3 mb-3 last-no-border">
                                    <img src="<?php echo $img; ?>" class="item-img me-3">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark"><?php echo $sp['TENSP']; ?></div>
                                        <div class="text-muted small">Phân loại: Chính hãng</div>
                                        <div class="text-muted small">x<?php echo $sp['SOLUONG']; ?></div>
                                    </div>
                                    <div class="text-danger fw-bold">
                                        <?php echo number_format($sp['DONGIA'], 0, ',', '.'); ?>₫
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="order-footer">
                            <div class="fs-6">
                                Thành tiền: <span class="text-danger fw-bold fs-4"><?php echo number_format($dh['TONGTIEN'], 0, ',', '.'); ?>₫</span>
                            </div>
                            
                            <?php if ($dh['TRANGTHAI'] == 'ChoDuyet'): ?>
                                <a href="Donmua.php?action=huy&madh=<?php echo $dh['MADDH']; ?>" 
                                   class="btn btn-danger px-4"
                                   onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy Đơn</a>
                            
                            <?php elseif ($dh['TRANGTHAI'] == 'DaGiao' || $dh['TRANGTHAI'] == 'Huy'): ?>
                                <a href="index.php" class="btn btn-primary px-4">Mua Lại</a>
                            
                            <?php else: ?>
                                <button class="btn btn-secondary px-4" disabled>Đang Vận Chuyển</button>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white text-center p-5 rounded shadow-sm mt-3">
                    <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/order/5fafbb923393b712b964.png" width="100">
                    <p class="text-muted mt-3">Chưa có đơn hàng nào</p>
                    <a href="index.php" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

                <footer style="z-index: 1020; background-color: rgba(0, 78, 146, 1); height: auto; color: white;">
        <div class="container py-2">
            <div class="row ">
                <div class="col-md-5"> <!-- Tổng đài hỗ trợ -->
                    <div>
                        <div class="fw-bold mb-1">Tổng đài hỗ trợ</div>
                        <div>Gọi mua:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Khiếu nại:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Bảo hành:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:00)</div>
                    </div>
                </div>


                <div class="col-md-5">
                    <div>
                        <div class="fw-bold mb-1">Về công ty</div> <!-- Về công ty -->
                        <div class="hover111"><a href="" class="text-decoration-none" style="color: white">Giới thiệu công ty</a></div>
                    </div>
                </div>

                
                <div class="col-md-2">
                    <div>
                        <div class="fw-bold mb-1">Liên kết hệ thống</div>
                        <div class="d-flex gap-3 mt-2">
                            <a href="https://www.facebook.com/share/1X1Hnri8xV/" class="text-reset text-decoration-none">
                                <i class="fa-brands fa-facebook fs-5"></i>
                            </a>
                            <a href="http://www.youtube.com/@TheGioiDienLanh-hp" class="text-reset text-decoration-none">
                                <i class="fa-brands fa-youtube fs-5"></i>
                            </a>
                            <a href="https://www.instagram.com/websitedienlanh2025/" class="text-reset text-decoration-none">
                                <i class="fa-brands fa-instagram fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </footer>
    <p class="mb-1 text-center">
        © 2025. Đồ án cơ sở ngành. Thực hiện bởi Phạm Thị Hồng Phương
    </p>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>