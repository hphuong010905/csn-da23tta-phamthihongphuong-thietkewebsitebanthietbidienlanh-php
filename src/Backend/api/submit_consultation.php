<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';

// Check if table exists, if not create it
$check_table = $conn->query("SHOW TABLES LIKE 'TU_VAN'");
if ($check_table->num_rows == 0) {
    $sql_create = "CREATE TABLE TU_VAN (
        MATV INT(11) AUTO_INCREMENT PRIMARY KEY,
        HOTEN VARCHAR(100) NOT NULL,
        SDT VARCHAR(20) NOT NULL,
        NOIDUNG TEXT,
        MASP VARCHAR(50),
        NGAYTAO DATETIME DEFAULT CURRENT_TIMESTAMP,
        TRANGTHAI INT(1) DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->query($sql_create);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = isset($_POST['hoten']) ? trim($_POST['hoten']) : '';
    $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
    $noidung = isset($_POST['noidung']) ? trim($_POST['noidung']) : '';
    $masp = isset($_POST['masp']) ? trim($_POST['masp']) : '';

    if (empty($hoten) || empty($sdt)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập họ tên và số điện thoại!']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO TU_VAN (HOTEN, SDT, NOIDUNG, MASP) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $hoten, $sdt, $noidung, $masp);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ sớm.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $conn->error]);
    }
    $stmt->close();
}
?>