<?php
require_once __DIR__ . '/../../Backend/config/cauhinhSS.php';
require_once __DIR__ . '/../../Backend/config/ConnectDB.php';

// ... (Giữ nguyên phần xử lý lấy sản phẩm) ...
if (isset($_GET['id'])) {
    $masp = $_GET['id'];
    $sql = "SELECT * FROM SAN_PHAM WHERE MASP = '$masp'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['HINHANHSP']) {
            $imgData = base64_encode($row['HINHANHSP']);
            $src = 'data:image/jpeg;base64,'.$imgData;
        } else { $src = 'img/no-image.png'; }
        $thongso = nl2br($row['THONGSOKYTHUAT']); 
    } else {
        echo "<div class='container mt-5 text-center'><h3>Sản phẩm không tồn tại!</h3><a href='index.php' class='btn btn-primary'>Về trang chủ</a></div>"; exit();
    }
} else { header("Location: index.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['TENSP']; ?></title>
    <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/libs/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <style>
        /* CẤU TRÚC TRANG */
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 80px; 
        }
        main { flex: 1; }

        /* STYLE SẢN PHẨM */
        .product-detail-img { width: 100%; max-height: 450px; object-fit: contain; }
        .content-box {
            background: #fff; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 25px; height: auto; margin-bottom: 20px;
        }
        .spec-content { font-size: 0.95rem; line-height: 1.8; color: #333; }
        .desc-content { font-size: 1rem; line-height: 1.6; text-align: justify; }
        .navbar-custom { background-color: rgba(0, 78, 146, 1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
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

    <main class="container">
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="fw-bold text-primary mb-1"><?php echo $row['TENSP']; ?></h3>
                <div class="text-muted small">Mã SP: <?php echo $row['MASP']; ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="content-box text-center p-4"> 
                    <img src="<?php echo $src; ?>" class="product-detail-img" alt="<?php echo $row['TENSP']; ?>">
                </div> 
                    <div class="content-box">
                        <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-file-lines me-2"></i>Đặc điểm nổi bật
                        </h5>
                        <div class="desc-content">
                            <?php echo nl2br($row['MOTASP']); ?>
                        </div> 
                    </div>
                <div class="content-box">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-list-check me-2"></i>Thông số kỹ thuật
                    </h5>
                    <div class="spec-content">
                        <?php 
                        if ($row['THONGSOKYTHUAT']) {
                            // Tách từng dòng
                            $lines = preg_split('/\r\n|\r|\n/', $row['THONGSOKYTHUAT']);
                            echo '<table class="table table-striped table-hover border">';
                            echo '<tbody>';
                            
                            for ($i = 0; $i < count($lines); $i++) {
                                $line = trim($lines[$i]);
                                if (empty($line)) continue;

                                // Trường hợp 1: Dòng chứa dấu ':' ở giữa (Ví dụ: "Công suất: 1HP")
                                if (strpos($line, ':') !== false && substr($line, -1) != ':') {
                                    $parts = explode(':', $line, 2);
                                    echo '<tr>';
                                    echo '<td style="width: 40%; font-weight: 600; color: #555;">' . trim($parts[0]) . '</td>';
                                    echo '<td>' . trim($parts[1]) . '</td>';
                                    echo '</tr>';
                                } 
                                // Trường hợp 2: Dòng kết thúc bằng ':' (Ví dụ: "Loại máy:") -> Dòng tiếp theo là giá trị
                                elseif (substr($line, -1) == ':') {
                                    $key = rtrim($line, ':');
                                    $value = '';
                                    // Lấy dòng tiếp theo làm giá trị
                                    if (isset($lines[$i+1])) {
                                        $value = trim($lines[$i+1]);
                                        $i++; // Bỏ qua dòng tiếp theo vì đã lấy rồi
                                    }
                                    echo '<tr>';
                                    echo '<td style="width: 40%; font-weight: 600; color: #555;">' . $key . '</td>';
                                    echo '<td>' . $value . '</td>';
                                    echo '</tr>';
                                }
                                // Trường hợp 3: Dòng bình thường (Tiêu đề hoặc text)
                                else {
                                     echo '<tr><td colspan="2" class="fw-bold text-uppercase text-primary bg-light">' . $line . '</td></tr>';
                                }
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo "<span class='text-muted'>Đang cập nhật...</span>";
                        }
                        ?>
                    </div>
                </div>

              
            </div>
        
            <div class="col-lg-5 col-12">
                <div class="content-box sticky-top" style="top: 100px; z-index: 1;"> 
                    <h2 class="text-danger fw-bold mb-3 text-center"><?php echo number_format($row['DONGIA'], 0, ',', '.'); ?>₫</h2>
                    
                    <div class="bg-light border border-success rounded-3 p-3 mb-4">
                        <h6 class="fw-bold text-success"><i class="fa-solid fa-gift"></i> ƯU ĐÃI ĐI KÈM</h6>
                        <ul class="list-unstyled mb-0 small ps-1">
                            <li class="mb-2"><i class="fa-solid fa-check-circle text-success me-2"></i> Miễn phí công lắp đặt</li>
                            <li class="mb-2"><i class="fa-solid fa-check-circle text-success me-2"></i> Tặng ống đồng 3m</li>
                            <li><i class="fa-solid fa-check-circle text-success me-2"></i> Bảo hành chính hãng 2 năm</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        
                        <?php if (isset($_SESSION['kh_name']) || isset($_SESSION['kh_id']) || isset($_SESSION['admin_id'])): ?>
                            
                            <button onclick="themVaoGio('<?php echo $row['MASP']; ?>')" class="btn btn-outline-danger w-100 py-3 fw-bold">
                                <i class="fa-solid fa-cart-plus fs-5 me-2"></i> Thêm vào giỏ
                            </button>

                            <a href="Giohang.php?id=<?php echo $row['MASP']; ?>&action=them" class="btn btn-danger w-100 py-3 fw-bold text-uppercase shadow-sm">
                                MUA NGAY
                            </a>

                        <?php else: ?>
                            <button onclick="alert('Vui lòng đăng nhập để mua hàng!'); window.location.href='LoginUser.php';" class="btn btn-outline-danger w-100 py-3 fw-bold">
                                <i class="fa-solid fa-cart-plus fs-5 me-2"></i> Thêm vào giỏ
                            </button>
                            <button onclick="alert('Vui lòng đăng nhập để mua hàng!'); window.location.href='LoginUser.php';" class="btn btn-danger w-100 py-3 fw-bold text-uppercase shadow-sm">
                                MUA NGAY
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-primary w-100 fw-bold py-2" data-bs-toggle="modal" data-bs-target="#consultationModal">
                            <i class="fa-solid fa-phone me-2"></i> GỌI TƯ VẤN
                        </button>
                    </div>

                    <div class="row mt-4 text-center small text-muted">
                        <div class="col-4 border-end"><i class="fa-solid fa-shield-halved fs-4 text-primary mb-1"></i><br>Chính hãng</div>
                        <div class="col-4 border-end"><i class="fa-solid fa-rotate fs-4 text-primary mb-1"></i><br>Đổi trả 30 ngày</div>
                        <div class="col-4"><i class="fa-solid fa-truck-fast fs-4 text-primary mb-1"></i><br>Giao miễn phí</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer style="z-index: 1020; background-color: rgba(0, 78, 146, 1); height: auto; color: white;">
        <div class="container py-2">
            <div class="row ">
                <div class="col-md-5"> <div>
                        <div class="fw-bold mb-1">Tổng đài hỗ trợ</div>
                        <div>Gọi mua:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Khiếu nại:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:30)</div>
                        <div>Bảo hành:<span class="fw-bold" style="color: white"> 0393 710 219</span> (8:00 - 21:00)</div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div>
                        <div class="fw-bold mb-1">Về công ty</div> <div class="hover111"><a href="" class="text-decoration-none" style="color: white">Giới thiệu công ty</a></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <div class="fw-bold mb-1">Liên kết hệ thống</div>
                        <div class="d-flex gap-3 mt-2">
                            <a href="" class="text-reset text-decoration-none"><i class="fa-brands fa-facebook fs-5"></i></a>
                            <a href="" class="text-reset text-decoration-none"><i class="fa-brands fa-youtube fs-5"></i></a>
                            <a href="" class="text-reset text-decoration-none"><i class="fa-brands fa-instagram fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </footer>
    <p class="mb-1 text-center">© 2025. Đồ án cơ sở ngành. Thực hiện bởi Phạm Thị Hồng Phương</p>
    
    <!-- jQuery and Search Script -->
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

            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_text, #result_list').length) {
                    $('#result_list').html('');
                }
            });
        });
    </script>
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
    <!-- Consultation Modal -->
    <div class="modal fade" id="consultationModal" tabindex="-1" aria-labelledby="consultationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="consultationModalLabel"><i class="fa-solid fa-headset me-2"></i>ĐĂNG KÝ TƯ VẤN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Vui lòng để lại thông tin, chúng tôi sẽ liên hệ lại ngay!</p>
                    <form id="consultationForm">
                        <input type="hidden" name="masp" value="<?php echo $row['MASP']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="hoten" required placeholder="Nhập họ tên của bạn">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="sdt" required placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung cần tư vấn</label>
                            <textarea class="form-control" name="noidung" rows="3" placeholder="Bạn cần tư vấn thêm về điều gì?"></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">GỬI YÊU CẦU</button>
                            <a href="tel:0393710219" class="btn btn-outline-danger fw-bold"><i class="fa-solid fa-phone me-2"></i> GỌI NGAY CHO ADMIN</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('consultationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch('../../Backend/api/submit_consultation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    var modal = bootstrap.Modal.getInstance(document.getElementById('consultationModal'));
                    modal.hide();
                    this.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
            });
        });
    </script>
</body>
</html>