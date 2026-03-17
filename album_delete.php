<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin-login.php"); exit(); }

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Xóa album (galleries sẽ tự động bị xóa theo nếu đã cài FOREIGN KEY)
    $sql = "DELETE FROM albums WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin-dashboard.php?view=gallery&msg=album_deleted");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>