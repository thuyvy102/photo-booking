<?php
include '../config/db.php'; 
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $message = mysqli_real_escape_string($conn, $_POST['message']); 
    $booking_code = "LS" . strtoupper(substr(md5(uniqid()), 0, 6));

    // BƯỚC QUAN TRỌNG: KIỂM TRA TRÙNG LỊCH
    // Tìm bất kỳ đơn nào cùng ngày, cùng giờ mà trạng thái KHÔNG PHẢI là 'Đã hủy'
    $check_sql = "SELECT id FROM bookings WHERE date = '$date' AND time = '$time' AND status != 'Đã hủy'";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        // Nếu tìm thấy ít nhất 1 dòng, nghĩa là đã có người đặt rồi
        header("Location: ../success.php?status=error&message=" . urlencode("Rất tiếc! Khung giờ $time ngày ".date('d/m/Y', strtotime($date))." đã có khách đặt chỗ. Bạn vui lòng chọn khung giờ khác nhé!"));
        exit();
    } else {
        // Nếu không trùng, mới cho phép lưu vào Database
        $sql_insert = "INSERT INTO bookings (name, phone, email, package_name, date, time, message, code, status) 
                       VALUES ('$name', '$phone', '$email', '$package', '$date', '$time', '$message', '$booking_code', 'Chờ xác nhận')";
        
        if (mysqli_query($conn, $sql_insert)) {
            header("Location: ../success.php?status=success&name=" . urlencode($name) . "&code=" . $booking_code);
        } else {
            header("Location: ../success.php?status=error&message=" . urlencode("Lỗi hệ thống: " . mysqli_error($conn)));
        }
        exit();
    }
}
?>