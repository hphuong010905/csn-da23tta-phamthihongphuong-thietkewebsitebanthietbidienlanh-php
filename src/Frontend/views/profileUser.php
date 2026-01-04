<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// 1. Kiểm tra đăng nhập (Chưa đăng nhập thì đuổi về)
if (!isset($_SESSION['kh_name'])) {
    header("Location: ../../Backend/auth/LoginUser.php");
    exit();
}

// 2. Lấy thông tin khách hàng từ CSDL
// LƯU Ý: Tốt nhất khi đăng nhập bạn nên lưu $_SESSION['kh_id'] (Mã KH) để query chính xác nhất.
// Ở đây mình tạm dùng tên để tìm (nếu tên trùng nhau sẽ bị lỗi, bạn nên sửa lại logic login sau nhé)
$name_session = $_SESSION['kh_name'];
$sql = "SELECT * FROM KHACH_HANG WHERE TENKH = '$name_session'"; 
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// 3. Xử lý CẬP NHẬT thông tin
if (isset($_POST['btn_update'])) {
    $ten = $_POST['tenkh'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $email = $_POST['email']; // Thường email dùng để đăng nhập nên hạn chế cho sửa, hoặc phải check trùng
    
    $id = $user['MAKH']; // Lấy ID từ dữ liệu vừa query được

    $sql_update = "UPDATE KHACH_HANG SET TENKH='$ten', SDTKH='$sdt', DCHIKH='$diachi', EMAIL='$email' WHERE MAKH='$id'";
    
    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['kh_name'] = $ten; // Cập nhật lại session tên mới
        echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f5fa; }
        .profile-sidebar { background: #fff; padding: 20px; border-radius: 8px; }
        .profile-content { background: #fff; padding: 30px; border-radius: 8px; }
        .avatar-circle {
            width: 80px; height: 80px;
            background-color: #e0e0e0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; color: #fff; margin-right: 15px;
        }
        .nav-link-profile {
            color: #333; padding: 10px 0; display: block; text-decoration: none;
            border-bottom: 1px solid #eee;
        }
        .nav-link-profile:hover { color: #d0021b; }
        .nav-link-profile.active { color: #d0021b; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top" 
        style="z-index: 1020; background-color: rgba(0, 78, 146, 1); height: auto; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
        
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/img/img/bg-1.png" alt="Logo" style="height: 60px; width: auto;" class="me-2">
                <span class="fw-bold fs-4" style= "color: white;">Thế giới điện lạnh</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                
                <ul class="navbar-nav ms-auto align-items-center gap-3 mt-3 mt-lg-0">

                    <?php if (isset($_SESSION['kh_name'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn bg-white text-dark border shadow-sm px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user fs-5 text-success me-2"></i> 
                                Xin chào, <b class="text-primary ms-1"><?php echo $_SESSION['kh_name']; ?></b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 animate slideIn">
                                <li><a class="dropdown-item py-2" href="profileUser.php"><i class="fa-solid fa-id-card me-2 text-primary"></i> Thông tin cá nhân</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="../../Backend/auth/LogoutUser.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </li>

                    <?php elseif (isset($_SESSION['admin_name'])): ?>
                        <li class="nav-item">
                            <a href="QTHT.php" class="btn btn-warning fw-bold text-dark px-3 rounded-pill shadow-sm">
                                <i class="fa-solid fa-screwdriver-wrench me-1"></i> Trang Quản Trị
                            </a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a href="LoginUser.php" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-regular fa-user fs-5"></i> Đăng nhập
                            </a>
                        </li>
                    <?php endif; ?>

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

                </ul>
            </div>
        </div>
    </nav>

    <!-- Menu Danh Mục Đồng Bộ -->
    <div class="row mb-3 shadow-sm" style="background-color: #f8f9fa;">
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

    <div class="container my-4">
        <div class="row">
            
            <div class="col-md-3 ">
                <div class="profile-sidebar shadow-sm">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <?php echo substr($_SESSION['kh_name'], 0, 1); ?>
                        </div>
                        <div>
                            <div class="fw-bold"><?php echo $user['TENKH']; ?></div>
                        </div>
                    </div>
                    
                    <div>
                        <a href="profile.php" class="nav-link-profile active"><i class="fa-solid fa-user me-2 text-primary"></i> Tài khoản của tôi</a>
                        <a href="Donmua.php" class="nav-link-profile"><i class="fa-solid fa-file-invoice-dollar me-2 text-primary"></i> Đơn mua</a>
                        <a href="../../Backend/auth/LogoutUser.php" class="nav-link-profile text-danger border-0"><i class="fa-solid fa-right-from-bracket me-2 text-primary"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="profile-content shadow-sm">
                    <div class="border-bottom pb-3 mb-4">
                        <h5 class="fw-bold m-0">Tài khoản của tôi</h5>
                    </div>

                    <form method="POST" action="">
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 text-md-end text-muted">Họ và tên</label>
                            <div class="col-md-7">
                                <input type="text" name="tenkh" class="form-control" value="<?php echo $user['TENKH']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 text-md-end text-muted">Email</label>
                            <div class="col-md-7">
                                <input type="email" name="email" class="form-control" value="<?php echo $user['EMAIL']; ?>">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 text-md-end text-muted">Số điện thoại</label>
                            <div class="col-md-7">
                                <input type="text" name="sdt" class="form-control" value="<?php echo $user['SDTKH']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 text-md-end text-muted">Địa chỉ</label>
                            <div class="col-md-7">
                                <input type="text" name="diachi" class="form-control" value="<?php echo $user['DCHIKH']; ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" name="btn_update" class="btn btn-warning text-white fw-bold px-4 shadow-sm">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- Bắt đầu footer -->
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