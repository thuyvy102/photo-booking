<?php
include 'config/db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit(); }

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Xóa tin nhắn khỏi database
    if (mysqli_query($conn, "DELETE FROM contacts WHERE id = $id")) {
        header("Location: admin-dashboard.php?view=messages&msg=deleted");
        exit();
    }
}
// Nếu có lỗi hoặc không có ID, vẫn quay về trang tin nhắn
header("Location: admin-dashboard.php?view=messages");
exit();
?>