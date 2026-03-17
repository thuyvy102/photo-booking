<?php
include 'config/db.php'; 
session_start();

// Kiểm tra đăng nhập (để bảo mật giống các file admin khác)
if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin-login.php"); exit(); }

// 1. XỬ LÝ XÓA MỤC
if(isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $sql_del = "DELETE FROM about_sections WHERE id = $id";
    if(mysqli_query($conn, $sql_del)) {
        header("Location: admin_manage_about.php?msg=deleted");
        exit();
    }
}

// 2. XỬ LÝ THÊM MỚI
if(isset($_POST['add_section'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $sub_images = mysqli_real_escape_string($conn, $_POST['sub_images']);
    $sort_order = (int)$_POST['sort_order'];

    // Ép kiểu layout mặc định là 'normal' vì bạn đã bỏ phần chọn layout
    $sql = "INSERT INTO about_sections (title, content, image_url, sub_images, layout_type, sort_order) 
            VALUES ('$title', '$content', '$image_url', '$sub_images', 'normal', '$sort_order')";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: admin_manage_about.php?msg=added");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Giới thiệu - Light Studio</title>
    <style>
        :root { --sidebar-bg: #342e29; --main-gold: #8c7867; --bg-light: #f8f9fa; --white: #ffffff; --success: #27ae60; --danger: #e74c3c; }
        body { display: flex; margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-light); color: #332a24; }
        
        /* Sidebar Styles */
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 30px 20px; text-align: center; font-weight: bold; letter-spacing: 3px; border-bottom: 1px solid #4a443f; }
        .sidebar-menu { flex-grow: 1; padding-top: 20px; }
        .sidebar-menu a { display: block; color: #b2bec3; padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: var(--main-gold); color: white; border-left: 4px solid #fff; }
        
        /* Main Content Styles */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; box-sizing: border-box; }
        .card { background: var(--white); border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 30px; }
        
        h2 { margin-top: 0; border-bottom: 2px solid var(--main-gold); padding-bottom: 10px; font-size: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        
        button { background: var(--sidebar-bg); color: white; border: none; padding: 12px 30px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        button:hover { background: var(--main-gold); }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f2f6; padding: 15px; font-size: 12px; text-transform: uppercase; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .img-preview { width: 80px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        
        .btn-edit { color: #8e44ad; text-decoration: none; font-weight: bold; margin-right: 15px; border: 1px solid #8e44ad; padding: 5px 12px; border-radius: 4px; font-size: 13px; }
        .btn-delete { color: var(--danger); text-decoration: none; font-weight: bold; border: 1px solid var(--danger); padding: 5px 12px; border-radius: 4px; font-size: 13px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">✦ LIGHT STUDIO</div>
    <div class="sidebar-menu">
        <a href="admin-dashboard.php?view=bookings">Quản lý Đặt lịch</a>
        <a href="admin-dashboard.php?view=packages">Quản lý Gói chụp</a>
        <a href="admin-dashboard.php?view=gallery">Quản lý Gallery</a>
        <a href="admin-dashboard.php?view=messages">Quản lý Tin nhắn</a>
        <a href="admin_manage_about.php" class="active">Quản lý Giới thiệu</a>
    </div>
    <a href="logout.php" style="padding:20px; color:#ff7675; text-decoration:none; border-top: 1px solid #4a443f; font-weight: bold; display: block; margin-top: auto;">🚪 Đăng xuất</a>
</div>
    <a href="logout.php" style="padding:20px; color:#ff7675; text-decoration:none; border-top: 1px solid #4a443f; font-weight: bold;">🚪 Đăng xuất</a>
</div>

<div class="main-content">
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= ($_GET['msg']=='added') ? "Thêm nội dung thành công!" : "Đã xóa mục thành công!" ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>✦ Thêm nội dung mới vào About</h2>
        <form method="POST" style="margin-top:20px;">
            <div class="form-group">
                <label>Tiêu đề dịch vụ:</label>
                <input type="text" name="title" required placeholder="VD: SIGNATURE SOLO - CHÂN DUNG CÁ NHÂN">
            </div>
            
            <div class="form-group">
                <label>Nội dung mô tả:</label>
                <textarea name="content" rows="4" required placeholder="Nhập lời giới thiệu..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Link hình ảnh chính:</label>
                    <input type="text" name="image_url" required placeholder="https://link-anh-chinh.jpg">
                </div>
                <div class="form-group">
                    <label>Thứ tự hiển thị:</label>
                    <input type="number" name="sort_order" value="0">
                </div>
            </div>

            <div class="form-group">
                <label>Link các ảnh phụ (Cách nhau bằng dấu phẩy):</label>
                <input type="text" name="sub_images" placeholder="link1.jpg, link2.jpg">
            </div>

            <button type="submit" name="add_section">Lưu và Hiển thị ngay</button>
        </form>
    </div>

    <div class="card">
        <h2>✦ Danh sách các mục hiện có</h2>
        <table>
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tiêu đề</th>
                    <th style="text-align:center;">Thứ tự</th>
                    <th style="text-align:right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM about_sections ORDER BY sort_order ASC");
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><img src='{$row['image_url']}' class='img-preview'></td>";
                        echo "<td><strong>{$row['title']}</strong></td>";
                        echo "<td style='text-align:center;'>{$row['sort_order']}</td>";
                        echo "<td style='text-align:right;'>";
                        echo "<a href='admin_edit_about.php?id={$row['id']}' class='btn-edit'>Sửa</a>";
                        echo "<a href='?delete_id={$row['id']}' class='btn-delete' onclick='return confirm(\"Xác nhận xóa mục này?\")'>Xóa</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding:30px;'>Chưa có dữ liệu giới thiệu.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>