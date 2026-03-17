<?php
session_start();
include 'config/db.php';

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // Truy vấn kiểm tra trong bảng users
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4;">
    <form method="POST" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; text-align: center;">ADMIN LOGIN</h2>
        <input type="text" name="user" placeholder="Tài khoản" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd;">
        <input type="password" name="pass" placeholder="Mật khẩu" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd;">
        <button type="submit" name="login" style="width: 100%; padding: 10px; background: #8c7867; color: white; border: none; cursor: pointer;">Đăng nhập hệ thống</button>
        <?php if(isset($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
    </form>
</body>
</html>