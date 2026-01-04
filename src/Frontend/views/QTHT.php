<?php
// 1. Cấu hình & Khởi động Session
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php'; 

// 2. Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header("Location: ../../Backend/auth/LoginUser.php");
    exit(); 
}

require_once __DIR__ . '/../../Backend/config/ConnectDB.php'; // Kết nối CSDL

// --- [MỚI] 3. TRUY VẤN SỐ LIỆU THỐNG KÊ ---
// Đếm số sản phẩm
$sql_sp = "SELECT COUNT(*) as total FROM SAN_PHAM";
$count_sp = $conn->query($sql_sp)->fetch_assoc()['total'];

// Đếm số khách hàng
$sql_kh = "SELECT COUNT(*) as total FROM KHACH_HANG";
$count_kh = $conn->query($sql_kh)->fetch_assoc()['total'];

// Đếm số danh mục
$sql_dh = "SELECT COUNT(*) as total FROM DON_DAT_HANG";
$count_dh = $conn->query($sql_dh)->fetch_assoc()['total'];

// Đếm số danh mục
$sql_dm = "SELECT COUNT(*) as total FROM DANH_MUC";
$count_dm = $conn->query($sql_dm)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ Quản Trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
        }
        .card-icon { font-size: 3rem; margin-bottom: 10px; }
        .stat-number { font-size: 2.5rem; font-weight: bold; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        footer { margin-top: auto; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 shadow">
        <div class="container">
            <a href="index.php" class="btn btn-outline-light btn-sm fw-bold shadow-sm me-3" title="Về trang chủ">
                <i class="fa-solid fa-house"></i>
            </a>
            
            <span class="navbar-brand mb-0 h1 fw-bold"><i class="fa-solid fa-gauge-high me-2"></i>Quản trị hệ thống</span>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex text-white align-items-center">
                    <span class="me-3">Xin chào, <b class="text-warning"><?php echo $_SESSION['admin_id']; ?></b></span>
                    <a href="../../Backend/auth/LogoutUser.php" class="btn btn-sm btn-light text-danger fw-bold shadow-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container text-center mb-5">
        <h2 class="text-uppercase fw-bold text-secondary">Tổng quan hệ thống</h2>
    </div>

    <div class="container mb-5">
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-3">
                <div class="card admin-card shadow-sm border-0 h-100 text-center py-4">
                    <div class="card-body">
                        <div class="card-icon text-primary"><i class="fa-solid fa-box-open"></i></div>
                        <h5 class="card-title text-uppercase text-muted">Sản phẩm</h5>
                        <div class="stat-number text-primary"><?php echo $count_sp; ?></div>
                        <a href="QLSP.php" class="btn btn-outline-primary rounded-pill mt-3 px-4">Quản lý</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card admin-card shadow-sm border-0 h-100 text-center py-4">
                    <div class="card-body">
                        <div class="card-icon text-danger"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <h5 class="card-title text-uppercase text-muted">Đơn hàng</h5>
                        <div class="stat-number text-danger"><?php echo $count_dh; ?></div>
                        <a href="QLDH.php" class="btn btn-outline-danger rounded-pill mt-3 px-4">Quản lý</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card admin-card shadow-sm border-0 h-100 text-center py-4">
                    <div class="card-body">
                        <div class="card-icon text-success"><i class="fa-solid fa-users"></i></div>
                        <h5 class="card-title text-uppercase text-muted">Khách hàng</h5>
                        <div class="stat-number text-success"><?php echo $count_kh; ?></div>
                        <a href="QLKH.php" class="btn btn-outline-success rounded-pill mt-3 px-4">Quản lý</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card admin-card shadow-sm border-0 h-100 text-center py-4">
                    <div class="card-body">
                        <div class="card-icon text-warning"><i class="fa-solid fa-list-check"></i></div>
                        <h5 class="card-title text-uppercase text-muted">Danh mục</h5>
                        <div class="stat-number text-warning"><?php echo $count_dm; ?></div>
                        <a href="QLDM.php" class="btn btn-outline-warning text-dark rounded-pill mt-3 px-4">Quản lý</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="bg-white border-top py-3">
        <div class="container text-center">
            <small class="text-muted">&copy;2025. Đồ án cơ sở ngành. Thực hiện bởi Phạm Thị Hồng Phương</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>