<?php
$host = "localhost";
$user = "root";
$pass = "123456"; 
$db   = "photo_booking";

// Tạo kết nối
$conn = mysqli_connect($host, $user, $pass, $db);

// Thiết lập tiếng Việt
mysqli_set_charset($conn, "utf8mb4");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
?>