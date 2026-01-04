<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';


// 2. CẬP NHẬT TRẠNG THÁI
if (isset($_POST['btn_update_status'])) {
    $ma_dh = $_POST['maddh'];
    $trang_thai_moi = $_POST['trangthai'];
    
    $sql_update = "UPDATE don_dat_hang SET TRANGTHAI = '$trang_thai_moi' WHERE MADDH = '$ma_dh'";
    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Đã cập nhật trạng thái đơn hàng #$ma_dh'); window.location.href='QLDH.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// 3. XÓA ĐƠN
if (isset($_GET['action']) && $_GET['action'] == 'xoa' && isset($_GET['id'])) {
    $ma_dh = $_GET['id'];
    $conn->query("DELETE FROM chi_tiet_don_hang WHERE MADDH = '$ma_dh'");
    $conn->query("DELETE FROM don_dat_hang WHERE MADDH = '$ma_dh'");
    header("Location: QLDH.php");
    exit();
}

// 4. LẤY DỮ LIỆU
$sql_dh = "SELECT dh.*, kh.TENKH 
           FROM don_dat_hang dh 
           LEFT JOIN khach_hang kh ON dh.MAKH = kh.MAKH 
           ORDER BY dh.NGAYDH DESC";
$result_dh = $conn->query($sql_dh);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { min-height: 100vh; background: #004e92; color: white; }
        .sidebar a { color: white; text-decoration: none; padding: 15px; display: block; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a:hover { background: rgba(255,255,255,0.1); }
        .status-badge { width: 120px; display: inline-block; text-align: center; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 shadow">
    <div class="container">
        
        <a href="QTHT.php" class="btn btn-outline-light btn-sm fw-bold shadow-sm me-3" title="Về trang chủ">
            <i class="fa-solid fa-house"></i>
        </a>

        <span class="navbar-brand mb-0 h1 fw-bold text-uppercase">
            <i class="fa-solid fa-gauge-high me-2"></i> Quản lý đơn hàng
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
            <h2 class="text-danger fw-bold border-start border-4 border-danger ps-3">QUẢN LÝ ĐƠN HÀNG</h2>
            
            <button onclick="window.print()" class="btn btn-outline-danger fw-bold no-print">
                <i class="fa-solid fa-print me-2"></i> In Danh Sách
            </button>
        </div>

        <div class="card shadow-sm border-0">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($result_dh && $result_dh->num_rows > 0) {
                                while ($row = $result_dh->fetch_assoc()) {
                                    $stt_color = 'secondary'; $stt_text = 'Chờ duyệt';
                                    switch ($row['TRANGTHAI']) {
                                        case 'ChoDuyet': $stt_color = 'warning text-dark'; $stt_text = 'Chờ duyệt'; break;
                                        case 'DaDuyet':  $stt_color = 'primary'; $stt_text = 'Đang giao'; break;
                                        case 'DaGiao':   $stt_color = 'success'; $stt_text = 'Hoàn tất'; break;
                                        case 'Huy':      $stt_color = 'danger'; $stt_text = 'Đã hủy'; break;
                                    }
                            ?>
                            <tr>
                                <td class="fw-bold">#<?php echo $row['MADDH']; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo $row['TENKH'] ? $row['TENKH'] : 'Khách lẻ / Đã xóa'; ?></div>
                                    <small class="text-muted"><?php echo $row['SDT_GIAO']; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['NGAYDH'])); ?></td>
                                <td class="fw-bold text-danger"><?php echo number_format($row['TONGTIEN'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <span class="badge bg-<?php echo ($row['HINHTHUC_TT']=='QR') ? 'primary' : 'secondary'; ?>">
                                        <?php echo ($row['HINHTHUC_TT']=='QR') ? 'Chuyển khoản' : 'Tiền mặt'; ?>
                                    </span>
                                </td>
                                <td><span class="badge bg-<?php echo $stt_color; ?> status-badge py-2"><?php echo $stt_text; ?></span></td>
                                
                                <td>
                                    <button class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $row['MADDH']; ?>">
                                        <i class="fa-regular fa-eye"></i> Xem
                                    </button>
                                    
                                    <?php if($row['TRANGTHAI'] == 'Huy'): ?>
                                        <a href="QLDH.php?action=xoa&id=<?php echo $row['MADDH']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa vĩnh viễn?')"><i class="fa-solid fa-trash"></i></a>
                                    <?php endif; ?>

                                    <div class="modal fade" id="modal_<?php echo $row['MADDH']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Chi tiết đơn #<?php echo $row['MADDH']; ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    
                                                    <div class="alert alert-light border">
                                                        <h6><i class="fa-solid fa-truck me-2"></i>Thông tin giao hàng:</h6>
                                                        <p class="mb-1"><b>Người nhận:</b> <?php echo $row['TENKH']; ?></p>
                                                        <p class="mb-1"><b>SĐT:</b> <?php echo $row['SDT_GIAO']; ?></p>
                                                        <p class="mb-0"><b>Địa chỉ:</b> <?php echo $row['DIACHI_GIAO']; ?></p>
                                                    </div>

                                                    <table class="table table-bordered mb-3">
                                                        <thead class="table-light">
                                                            <tr><th>Sản phẩm</th><th>Đơn giá</th><th>SL</th><th>Thành tiền</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $curr_madh = $row['MADDH'];
                                                            $sql_ct = "SELECT ct.*, sp.TENSP FROM chi_tiet_don_hang ct JOIN san_pham sp ON ct.MASP = sp.MASP WHERE ct.MADDH = '$curr_madh'";
                                                            $res_ct = $conn->query($sql_ct);
                                                            if($res_ct && $res_ct->num_rows > 0) {
                                                                while($r_ct = $res_ct->fetch_assoc()):
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $r_ct['TENSP']; ?></td>
                                                                <td><?php echo number_format($r_ct['DONGIA'],0,',','.'); ?></td>
                                                                <td class="text-center"><?php echo $r_ct['SOLUONG']; ?></td>
                                                                <td class="fw-bold"><?php echo number_format($r_ct['DONGIA']*$r_ct['SOLUONG'],0,',','.'); ?>₫</td>
                                                            </tr>
                                                            <?php endwhile; } ?>
                                                        </tbody>
                                                    </table>

                                                    <form action="" method="POST" class="bg-light p-3 rounded border">
                                                        <input type="hidden" name="maddh" value="<?php echo $row['MADDH']; ?>">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <label class="fw-bold">Cập nhật trạng thái:</label>
                                                            <select name="trangthai" class="form-select w-auto">
                                                                <option value="ChoDuyet" <?php if($row['TRANGTHAI']=='ChoDuyet') echo 'selected'; ?>>Chờ duyệt</option>
                                                                <option value="DaDuyet" <?php if($row['TRANGTHAI']=='DaDuyet') echo 'selected'; ?>>Đã duyệt (Đang giao)</option>
                                                                <option value="DaGiao" <?php if($row['TRANGTHAI']=='DaGiao') echo 'selected'; ?>>Giao thành công</option>
                                                                <option value="Huy" <?php if($row['TRANGTHAI']=='Huy') echo 'selected'; ?>>Hủy đơn</option>
                                                            </select>
                                                            <button type="submit" name="btn_update_status" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            <?php 
                                } 
                            } else {
                                echo "<tr><td colspan='7' class='text-center py-4 text-muted'>Chưa có đơn hàng nào</td></tr>";
                            }
                            ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>