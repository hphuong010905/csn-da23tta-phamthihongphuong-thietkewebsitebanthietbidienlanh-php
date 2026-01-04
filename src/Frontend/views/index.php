<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Ban_MayLanh.css">
    <link rel="stylesheet" href="../assets/libs/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">    
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
                
                <!-- Thanh tìm kiếm -->
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
    <!-- Kết thúc header -->
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
    <!-- Carousel -->
    

    <div class="row">
    <div class="carousel slide col-2 align-items-center justify-content-center"></div>

    <div id="demo" class="carousel slide col-8 align-items-center justify-content-center" data-bs-ride="carousel" data-bs-interval="3000">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
        </div>
    
        <div class="carousel-inner">
            <div class="carousel-item active rounded-0">
                <img src="../assets/img/img/bg_panasonic_x4_pixel1.jpg" alt="Deal hot" class="d-block" style="width:100%">
            </div>
            <div class="carousel-item rounded-0">
                <img src="../assets/img/img/bg_daikin_x4 pixel.jpg" alt="Deal hoi" class="d-block" style="width:100%">
            </div>
            <div class="carousel-item rounded-0">
                <img src="../assets/img/img/bg_toshiba_x4_pixel1.jpg" alt="Deal" class="d-block" style="width:100%">
            </div>
        </div>
    
        </div>
    <div class="carousel slide col-2 align-items-center justify-content-center"></div>
</div>
    

    <!-- Container -->
    <div class="container my-4">


    <div class="category-section mb-5">
        <div class="d-flex justify-content-between align-items-center border-bottom border-primary pb-2 mb-4">
            <h4 class="text-primary m-0 fw-bold"><i class="fa-solid fa-wind"></i> GỢI Ý MÁY LẠNH</h4>
           <!-- <a href="danhmuc.php?madm=DM_ML" class="text-decoration-none small fw-bold">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>-->
        </div>
        <div class="row g-3 justify-content-center">
            <?php
            // Lấy ngẫu nhiên 4 sản phẩm
            $sql = "SELECT * FROM SAN_PHAM WHERE MADM = 'ML' ORDER BY RAND() LIMIT 4";
            hienThiSanPham($conn->query($sql)); 
            ?>
        </div>
    </div>

    <div class="category-section mb-5">
        <div class="d-flex justify-content-between align-items-center border-bottom border-success pb-2 mb-4">
            <h4 class="text-success m-0 fw-bold"><i class="fa-solid fa-soap"></i> GỢI Ý MÁY GIẶT</h4>
            <!--<a href="danhmuc.php?madm=DM_MG" class="text-decoration-none text-success small fw-bold">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>-->
        </div>
        <div class="row g-3 justify-content-center">
            <?php
            $sql = "SELECT * FROM SAN_PHAM WHERE MADM = 'MG' ORDER BY RAND() LIMIT 4";
            hienThiSanPham($conn->query($sql));
            ?>
        </div>
    </div>

    <div class="category-section mb-5">
        <div class="d-flex justify-content-between align-items-center border-bottom border-info pb-2 mb-4">
            <h4 class="text-info m-0 fw-bold"><i class="fa-solid fa-snowflake"></i> GỢI Ý TỦ LẠNH</h4>
            <!--<a href="danhmuc.php?madm=DM_TL" class="text-decoration-none text-info small fw-bold">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>-->
        </div>
        <div class="row g-3 justify-content-center">
            <?php
            $sql = "SELECT * FROM SAN_PHAM WHERE MADM = 'TL' ORDER BY RAND() LIMIT 4";
            hienThiSanPham($conn->query($sql));
            ?>
        </div>
    </div>

</div>

    <?php
    function hienThiSanPham($result) {
        global $conn; // Quan trọng: Gọi biến kết nối CSDL vào trong hàm

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                // 1. XỬ LÝ ẢNH
                if ($row['HINHANHSP']) {
                    $imgData = base64_encode($row['HINHANHSP']);
                    $src = 'data:image/jpeg;base64,'.$imgData;
                } else { 
                    $src = '../assets/img/img/no-image.png'; 
                }

                // 2. TÍNH SỐ LƯỢT BÁN (Lấy từ đơn hàng đã giao)
                $masp = $row['MASP'];
                $sql_ban = "SELECT SUM(SOLUONG) as daban FROM chi_tiet_don_hang ct 
                            JOIN don_dat_hang dh ON ct.MADDH = dh.MADDH 
                            WHERE ct.MASP = '$masp' AND dh.TRANGTHAI = 'DaGiao'";
                $res_ban = $conn->query($sql_ban);
                $row_ban = $res_ban->fetch_assoc();
                $sl_daban = ($row_ban['daban']) ? $row_ban['daban'] : 0; // Nếu chưa bán được thì là 0

                // 3. RANDOM SỐ SAO (4.5 hoặc 5)
                // Logic: Random số từ 0-10. Nếu > 3 thì 5 sao, ngược lại 4.5 sao (tỷ lệ 70% là 5 sao)
                $is_5_star = (rand(0, 10) > 3); 
    ?>
                <div class="col-6 col-md-4 col-lg-3 mb-4"> 
                    <div class="card h-100 shadow-sm border-0 product-card position-relative"> 
                        
                        <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded-2 small fw-bold" style="font-size: 0.7rem; z-index: 2;">
                            -<?php echo rand(5, 20); ?>% 
                        </div>

                        <a href="ChitietSP.php?id=<?php echo $row['MASP']; ?>">
                            <img src="<?php echo $src; ?>" class="card-img-top product-img p-2" alt="<?php echo $row['TENSP']; ?>" style="height: 200px; object-fit: contain;">
                        </a>
                        
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title product-name mb-1" style="min-height: 40px;">
                                <a href="ChitietSP.php?id=<?php echo $row['MASP']; ?>" class="text-decoration-none text-dark fw-bold" style="font-size: 0.9rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo $row['TENSP']; ?>
                                </a>
                            </h6>
                            
                            <div class="rating-box">
                                <span class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <?php if($is_5_star): ?>
                                        <i class="fa-solid fa-star"></i> <?php else: ?>
                                        <i class="fa-solid fa-star-half-stroke"></i> <?php endif; ?>
                                </span>
                                <span class="border-start mx-2" style="height: 12px; display: inline-block;"></span>
                                <span class="sold-count">Đã bán <?php echo $sl_daban; ?></span>
                            </div>

                            <div class="mt-auto">
                                <div class="mb-3">
                                    <span class="text-danger fw-bold fs-5"><?php echo number_format($row['DONGIA'], 0, ',', '.'); ?>₫</span>
                                </div>
                                
                                <div class="d-grid">
                                    <?php if (isset($_SESSION['kh_name']) || isset($_SESSION['kh_id']) || isset($_SESSION['admin_id'])): ?>
                                        <a href="#" onclick="themVaoGio('<?php echo $row['MASP']; ?>'); return false;" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
                                        </a>
                                    <?php else: ?>
                                        <button onclick="alert('Vui lòng đăng nhập để mua hàng!'); window.location.href='LoginUser.php';" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
            }
        } else {
            echo "<div class='col-12 text-center text-muted py-5'>Đang cập nhật sản phẩm...</div>";
        }
    }
    ?>
 <!-- container -->

    <!-- Bắt đầu footer -->
    <footer style="z-index: 1020; background-color: rgba(0, 78, 146, 1); height: auto; color: white;">
        <div class="container py-2">
            <div class="row ">
                <div class="col-md-10"> <!-- Tổng đài hỗ trợ -->
                    <div>
                        <div class="fw-bold mb-1">Tổng đài hỗ trợ</div>
                        <div>Gọi mua:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Khiếu nại:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Bảo hành:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:00)</div>
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
    

    <!-- Kết thúc footer -->
    <script>
        function themVaoGio(masp) {
            // Tạo form data để gửi đi
            let formData = new FormData();
            formData.append('id', masp);

            // Gửi yêu cầu ngầm đến file ajax_cart.php
            fetch('../../Backend/api/ajax_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // Nhận về con số
            .then(data => {
                // 1. Cập nhật số trên Navbar ngay lập tức
                document.getElementById('cart-count').innerText = data;
                
                // 2. Hiệu ứng thông báo nhỏ (Tùy chọn cho đẹp)
                alert('Đã thêm sản phẩm vào giỏ hàng thành công!');
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            });
        }
    </script>

    <script src="../assets/libs/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script xử lý tìm kiếm -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#search_text').keyup(function(){
                var txt = $(this).val();
                if(txt != '')
                {
                    $.ajax({
                        url:"../../Backend/api/search_product.php",
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

            // Ẩn kết quả khi nhấp ra ngoài
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_text, #result_list').length) {
                    $('#result_list').html('');
                }
            });
        });
    </script>
</body>
</html>