<?php
include 'config/db.php';
session_start();

// Kiểm tra nếu không phải admin thì đá ra trang login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Thực hiện lệnh xóa trong database
    $sql = "DELETE FROM packages WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Xóa xong thì quay về trang danh sách gói chụp
        header("Location: admin-dashboard.php?view=packages&msg=deleted");
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} else {
    // Nếu không có ID thì quay về dashboard
    header("Location: admin-dashboard.php?view=packages");
    exit();
}
?>