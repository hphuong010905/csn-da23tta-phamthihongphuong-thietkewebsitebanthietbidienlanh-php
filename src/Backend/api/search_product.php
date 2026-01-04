<?php
require_once __DIR__ . '/../config/ConnectDB.php';

if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $keyword = "%$keyword%";
    
    $sql = "SELECT MASP, TENSP, DONGIA, HINHANHSP FROM SAN_PHAM WHERE TENSP LIKE ?";
    $types = "s";
    $params = array($keyword);

    if (isset($_POST['madm']) && !empty($_POST['madm'])) {
        $sql .= " AND MADM = ?";
        $types .= "s";
        $params[] = $_POST['madm'];
    }

    $sql .= " LIMIT 5";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $output = '';
    
    if ($result->num_rows > 0) {
        $output .= '<ul class="list-group position-absolute w-100 shadow" style="z-index: 1050; top: 100%;">';
        while ($row = $result->fetch_assoc()) {
            $imgSrc = 'img/no-image.png';
            if ($row['HINHANHSP']) {
                $imgData = base64_encode($row['HINHANHSP']);
                $imgSrc = 'data:image/jpeg;base64,' . $imgData;
            }
            
            $output .= '
            <li class="list-group-item list-group-item-action">
                <a href="ChitietSP.php?id=' . $row['MASP'] . '" class="text-decoration-none text-dark d-flex align-items-center">
                    <img src="' . $imgSrc . '" alt="' . $row['TENSP'] . '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                    <div>
                        <div class="fw-bold" style="font-size: 0.9rem;">' . $row['TENSP'] . '</div>
                        <div class="text-danger small">' . number_format($row['DONGIA'], 0, ',', '.') . '₫</div>
                    </div>
                </a>
            </li>';
        }
        $output .= '</ul>';
    } else {
        $output .= '<ul class="list-group position-absolute w-100 shadow" style="z-index: 1050; top: 100%;"><li class="list-group-item">Không tìm thấy sản phẩm</li></ul>';
    }
    
    echo $output;
}
?>
