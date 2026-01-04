<?php
// 1. Cấu hình & Bảo mật
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/auth/admin_check.php'; 
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// // --- LOGIC XỬ LÝ XÓA (Giữ nguyên logic xóa tại đây cho tiện) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Kiểm tra ràng buộc
    $check_sp = $conn->query("SELECT COUNT(*) as total FROM SAN_PHAM WHERE MADM = '$id'");
    if ($check_sp->fetch_assoc()['total'] > 0) {
        echo "<script>alert('KHÔNG THỂ XÓA: Danh mục này đang chứa sản phẩm!'); window.location.href='QLDM.php';</script>";
        exit();
    }

    $conn->query("DELETE FROM DANH_MUC WHERE MADM = '$id'");
    echo "<script>alert('Đã xóa thành công!'); window.location.href='QLDM.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Danh Mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print { .no-print, .navbar, .btn { display: none !important; } th:last-child, td:last-child { display: none; } }
    </style>
</head>

<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 shadow">
    <div class="container">
        
        <a href="QTHT.php" class="btn btn-outline-light btn-sm fw-bold shadow-sm me-3" title="Về trang chủ">
            <i class="fa-solid fa-house"></i>
        </a>

        <span class="navbar-brand mb-0 h1 fw-bold text-uppercase">
            <i class="fa-solid fa-gauge-high me-2"></i> Quản lý danh mục
        </span>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex text-white align-items-center mt-2 mt-lg-0">
                <span class="me-3">Xin chào, <b class="text-warning"><?php echo $_SESSION['admin_id']; ?></b></span>
                <a href="../../Backend/auth/LogoutUser.php" class="btn btn-sm btn-light text-danger fw-bold shadow-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>

    </div>
</nav>

    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold border-start border-4 border-warning ps-3" style="color: #b37400 !important;">DANH SÁCH DANH MỤC</h2>
            
            <div class="d-flex gap-2 no-print">
                <a href="../../Backend/controllers/addDM.php" class="btn btn-warning text-white fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Thêm Mới
                </a>
                
                <button onclick="window.print()" class="btn btn-outline-secondary fw-bold bg-white">
                    <i class="fa-solid fa-print me-1"></i> In DS
                </button>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: #ffc107;"> 
                            <tr>
                                <th class="py-3 ps-4 text-dark">Mã DM</th>
                                <th class="py-3 text-dark">Tên Danh Mục</th>
                                <th class="py-3 text-center text-dark">Số Lượng SP</th>
                                <th class="py-3 text-center text-dark no-print">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT dm.*, COUNT(sp.MASP) as so_luong_sp 
                                    FROM DANH_MUC dm 
                                    LEFT JOIN SAN_PHAM sp ON dm.MADM = sp.MADM 
                                    GROUP BY dm.MADM ORDER BY dm.MADM ASC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo $row['MADM']; ?></td>
                                    <td class="fw-bold fs-5"><?php echo $row['TENDM']; ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo $row['so_luong_sp']>0?'bg-primary':'bg-secondary'; ?> rounded-pill px-3">
                                            <?php echo $row['so_luong_sp']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center no-print">
                                        <a href="../../Backend/controllers/editDM.php?id=<?php echo $row['MADM']; ?>" class="btn btn-sm btn-outline-warning mx-1">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        <a href="QLDM.php?action=delete&id=<?php echo $row['MADM']; ?>" 
                                           class="btn btn-sm btn-outline-danger mx-1"
                                           onclick="return confirm('Xóa danh mục này?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='4' class='text-center py-4'>Trống</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-top py-3">
        <div class="container text-center">
            <small class="text-muted">&copy;2025. Đồ án cơ sở ngành. Thực hiện bởi Phạm Thị Hồng Phương</small>
        </div>
    </footer>

</body>
</html>