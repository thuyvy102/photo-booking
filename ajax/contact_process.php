<?php
include '../config/db.php'; // Kết nối tới database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận và làm sạch dữ liệu từ form
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Chèn dữ liệu vào bảng contacts (bạn cần tạo bảng này trong SQL trước)
    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

    if (mysqli_query($conn, $sql)) {
        // CHÈN ĐOẠN MÃ CHUYỂN HƯỚNG TẠI ĐÂY
        header("Location: ../contact.php?send=success#contact-form");
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($conn); // Hiển thị lỗi nếu không lưu được
    }
}
?>