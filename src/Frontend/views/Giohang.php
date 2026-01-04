<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// --- XỬ LÝ CẬP NHẬT ĐỊA CHỈ ADMIN ---
if (isset($_POST['btn_update_admin_address'])) {
    $_SESSION['admin_address'] = $_POST['new_address'];
    echo "<script>alert('Đã cập nhật địa chỉ!'); window.location.href='Giohang.php';</script>";
    exit();
}

// =================================================================
// PHẦN 1: XỬ LÝ LOGIC (Thêm/Tăng/Giảm/Xóa)
// =================================================================
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // --- LOGIC MỚI: THÊM SẢN PHẨM (Dùng cho trang Chi tiết) ---
    if ($action == 'them') {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++; // Đã có thì tăng số lượng
        } else {
            $_SESSION['cart'][$id] = 1; // Chưa có thì thêm mới = 1
        }
    }

    // --- LOGIC CŨ: TĂNG/GIẢM/XÓA (Dùng trong trang Giỏ hàng) ---
    if (isset($_SESSION['cart'][$id])) {
        if ($action == 'tang') $_SESSION['cart'][$id]++;
        
        if ($action == 'giam') {
            $_SESSION['cart'][$id]--;
            if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
        }
        
        if ($action == 'xoa') unset($_SESSION['cart'][$id]);
    }

    // --- QUAN TRỌNG: ĐIỀU HƯỚNG SAU KHI XỬ LÝ ---
    
    // Nếu có yêu cầu quay lại trang cũ (redirect=back)
    if (isset($_GET['redirect']) && $_GET['redirect'] == 'back') {
        echo "<script>
                alert('Đã thêm sản phẩm vào giỏ hàng!');
                window.history.back(); // Quay lại trang Chi tiết sản phẩm
              </script>";
        exit();
    } 
    
    // Mặc định: Tải lại trang giỏ hàng (Để cập nhật số liệu)
    header("Location: Giohang.php");
    exit();
}



// =================================================================
// PHẦN 2: LẤY DỮ LIỆU ĐỂ HIỂN THỊ
// =================================================================
$cart_empty = true;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_empty = false;
    $list_id = array_keys($_SESSION['cart']); 
    $str_id = "'" . implode("','", $list_id) . "'";
    $sql = "SELECT * FROM SAN_PHAM WHERE MASP IN ($str_id)";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f5f5fa; }
        .address-bar { background: #fff; border-bottom: 2px dashed #00bfa5; padding: 15px 0; margin-bottom: 20px; }
        .cart-item-img { width: 80px; height: 80px; object-fit: contain; border: 1px solid #eee; border-radius: 5px; }
        .qty-btn { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; background: #fff; cursor: pointer; text-decoration: none; color: #333; }
        .qty-input { width: 40px; height: 30px; text-align: center; border: 1px solid #ddd; border-left: none; border-right: none; }
        .price-text { color: #ff424e; font-weight: bold; }
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
                
                <!-- Search Bar -->
                <div class="d-flex mx-auto position-relative mt-3 mt-lg-0" style="width: 100%; max-width: 400px;">
                    <input class="form-control rounded-pill pe-5" type="text" placeholder="Tìm kiếm sản phẩm..." id="search_text" autocomplete="off">
                    <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 border-0 bg-transparent" type="button">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </button>
                    <div id="result_list" class="position-absolute w-100" style="top: 100%; left: 0; z-index: 1050;"></div>
                </div>

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

                    <?php elseif (isset($_SESSION['admin_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn bg-white text-dark border shadow-sm px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user-shield fs-5 text-danger me-2"></i> 
                                Xin chào, <b class="text-danger ms-1"><?php echo $_SESSION['admin_id']; ?></b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 animate slideIn">
                                <li><a class="dropdown-item py-2 fw-bold" href="QTHT.php"><i class="fa-solid fa-screwdriver-wrench me-2 text-warning"></i> Trang Quản Trị</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="../../Backend/auth/LogoutUser.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a href="../../Backend/auth/LoginUser.php" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
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

    <div class="address-bar shadow-sm">
        <div class="container">
            <div class="d-flex align-items-center text-primary fs-5 mb-2">
                <i class="fa-solid fa-location-dot me-2"></i> Địa chỉ nhận hàng
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-bold text-dark">
                    <?php 
                    if(isset($_SESSION['kh_name'])) {
                        // 1. Lấy tên từ Session
                        $current_user = $_SESSION['kh_name'];
                        
                        // 2. Truy vấn CSDL để lấy SĐT và Địa chỉ
                        // Lưu ý: Tốt nhất nên dùng MAKH (ID) nếu session có lưu. Ở đây dùng tạm TENKH.
                        $sql_user = "SELECT SDTKH, DCHIKH FROM KHACH_HANG WHERE TENKH = '$current_user' LIMIT 1";
                        $result_user = $conn->query($sql_user);
                        
                        if ($result_user->num_rows > 0) {
                            $user_data = $result_user->fetch_assoc();
                            
                            // Xử lý hiển thị nếu dữ liệu trống
                            $sdt = !empty($user_data['SDTKH']) ? $user_data['SDTKH'] : 'Chưa có SĐT';
                            $diachi = !empty($user_data['DCHIKH']) ? $user_data['DCHIKH'] : '<span class="text-danger fst-italic">Chưa cập nhật địa chỉ</span>';
                            
                            // Hiển thị ra màn hình
                            echo "$current_user ($sdt) <span class='mx-2 text-secondary'>|</span> $diachi";
                        } else {
                            echo $_SESSION['kh_name']; 
                        }
                    } elseif (isset($_SESSION['admin_id'])) {
                        $admin_addr = isset($_SESSION['admin_address']) ? $_SESSION['admin_address'] : '<span class="text-danger fst-italic">Chưa nhập địa chỉ</span>';
                        echo "Quản trị viên: <b>" . $_SESSION['admin_id'] . "</b> <span class='mx-2 text-secondary'>|</span> " . $admin_addr;
                    } else {
                        echo "Bạn chưa đăng nhập.";
                    }
                    ?>
                </div>
                
                <?php if(isset($_SESSION['kh_name'])): ?>
                    <a href="profileUser.php" class="text-primary text-decoration-none fw-bold small">Thay đổi</a>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#adminAddressModal" class="text-primary text-decoration-none fw-bold small">Thay đổi</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
        </div>
    </div>

    <div class="container mb-5">
        <?php if ($cart_empty): ?>
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" width="100">
                <p class="mt-3 text-muted">Giỏ hàng trống</p>
                <a href="index.php" class="btn btn-primary">Mua ngay</a>
            </div>
        <?php else: ?>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="bg-white rounded shadow-sm p-3">
                        <div class="row border-bottom pb-2 mb-3 text-muted small d-none d-md-flex">
                            <div class="col-6">Sản phẩm</div>
                            <div class="col-2 text-center">Đơn giá</div>
                            <div class="col-2 text-center">Số lượng</div>
                            <div class="col-2 text-center">Thành tiền</div>
                        </div>

                        <?php 
                        $tong_tien = 0;
                        while ($row = $result->fetch_assoc()): 
                            $id = $row['MASP'];
                            $so_luong = $_SESSION['cart'][$id];
                            $thanh_tien = $row['DONGIA'] * $so_luong;
                            $tong_tien += $thanh_tien;
                            $src = ($row['HINHANHSP']) ? 'data:image/jpeg;base64,'.base64_encode($row['HINHANHSP']) : 'img/no-image.png';
                        ?>
                        <div class="row align-items-center mb-4">
                            <div class="col-12 col-md-6 d-flex align-items-center">
                                <a href="Giohang.php?id=<?php echo $id; ?>&action=xoa" class="text-muted me-3" onclick="return confirm('Xóa?')" title="Xóa"><i class="fa-regular fa-trash-can"></i></a>
                                <img src="<?php echo $src; ?>" class="cart-item-img me-3">
                                <div>
                                    <div class="fw-bold product-name"><?php echo $row['TENSP']; ?></div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2 text-center mt-3 mt-md-0">
                                <?php echo number_format($row['DONGIA'], 0, ',', '.'); ?>₫
                            </div>
                            <div class="col-4 col-md-2 d-flex justify-content-center mt-3 mt-md-0">
                                <div class="d-flex">
                                    <a href="Giohang.php?id=<?php echo $id; ?>&action=giam" class="qty-btn rounded-start">-</a>
                                    <input type="text" class="qty-input" value="<?php echo $so_luong; ?>" readonly>
                                    <a href="Giohang.php?id=<?php echo $id; ?>&action=tang" class="qty-btn rounded-end">+</a>
                                </div>
                            </div>
                            <div class="col-4 col-md-2 text-center mt-3 mt-md-0 price-text">
                                <?php echo number_format($thanh_tien, 0, ',', '.'); ?>₫
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">
                    <div class="bg-white rounded shadow-sm p-4 sticky-top" style="top: 20px;">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính:</span>
                            <span class="fw-bold"><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span>Giảm giá:</span><span class="text-success">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="price-text fs-4"><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
                        </div>
                        <?php if (isset($_SESSION['kh_name'])): ?>
                            <a href="thanhtoan.php" class="btn btn-danger w-100 py-3 fw-bold text-uppercase">Mua Hàng</a>
                        <?php else: ?>
                            <a href="LoginUser.php" class="btn btn-warning w-100 py-3 fw-bold">Đăng nhập để mua</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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

    <!-- jQuery and Search Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#search_text').keyup(function(){
                var txt = $(this).val();
                if(txt != '')
                {
                    $.ajax({
                        url:"search_product.php",
                        method:"post",
                        data:{keyword:txt},
                        dataType:"text",
                        success:function(data)
                        {
                            $('#result_list').html(data);
                        }
                    });
                }
                else
                {
                    $('#result_list').html('');
                }
            });

            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_text, #result_list').length) {
                    $('#result_list').html('');
                }
            });
        });
    </script>
    <!-- Admin Address Modal -->
    <div class="modal fade" id="adminAddressModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cập nhật địa chỉ giao hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST">
              <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Địa chỉ mới:</label>
                    <input type="text" class="form-control" name="new_address" required placeholder="Nhập địa chỉ giao hàng..." value="<?php echo isset($_SESSION['admin_address']) ? $_SESSION['admin_address'] : ''; ?>">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" name="btn_update_admin_address" class="btn btn-primary">Lưu thay đổi</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>