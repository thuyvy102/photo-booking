<?php
$servername = "localhost";
$username = "root";
$password = "123456"; // Mặc định của XAMPP là để trống
$dbname = "photo_booking"; // Thay bằng tên database của bạn

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Thiết lập font chữ tiếng Việt
mysqli_set_charset($conn, "utf8mb4");
?>