<?php
require_once __DIR__ . '/../config/cauhinhSS.php';
require_once __DIR__ . '/../config/ConnectDB.php';
?>
<?php
session_start();
session_destroy(); // Xóa sạch session
header("Location: ../../Frontend/views/index.php"); // Quay về trang chủ
exit();
?>