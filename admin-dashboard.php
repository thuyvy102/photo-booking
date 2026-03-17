<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin-login.php"); exit(); }
include 'config/db.php';
$view = isset($_GET['view']) ? $_GET['view'] : 'bookings';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị Light Studio</title>
    <style>
        :root { --sidebar-bg: #342e29; --main-gold: #8c7867; --bg-light: #f8f9fa; --white: #ffffff; --success: #27ae60; --danger: #e74c3c; }
        body { display: flex; margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg-light); }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 30px 20px; text-align: center; font-weight: bold; letter-spacing: 3px; border-bottom: 1px solid #4a443f; }
        .sidebar-menu { flex-grow: 1; padding-top: 20px; }
        .sidebar-menu a { display: block; color: #b2bec3; padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: var(--main-gold); color: white; border-left: 4px solid #fff; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .card { background: var(--white); border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f2f6; padding: 15px; font-size: 12px; text-transform: uppercase; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .btn { padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-block; border: 1px solid; cursor: pointer; }
        .btn-edit { color: #8e44ad; border-color: #8e44ad; }
        .btn-disabled { opacity: 0.3; cursor: not-allowed; pointer-events: none; filter: grayscale(100%); background: #eee; color: #999; border-color: #ccc; }
        .status { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid; }
        .stt-pending { background: #fff4e5; color: #ff9800; border-color: #ff9800; }
        .stt-confirmed { background: #e8f5e9; color: var(--success); border-color: var(--success); }
        .stt-canceled { background: #ffebee; color: var(--danger); border-color: var(--danger); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 14px; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .alert-danger { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">✦ LIGHT STUDIO</div>
    <div class="sidebar-menu">
        <a href="?view=bookings" class="<?= $view=='bookings'?'active':'' ?>">Quản lý Đặt lịch</a>
        <a href="?view=packages" class="<?= $view=='packages'?'active':'' ?>">Quản lý Gói chụp</a>
        <a href="?view=gallery" class="<?= $view=='gallery'?'active':'' ?>">Quản lý Gallery</a>
        <a href="?view=messages" class="<?= $view=='messages'?'active':'' ?>">Quản lý Tin nhắn</a>
        <a href="admin_manage_about.php" class="menu-item">Quản lý Giới thiệu</a>
    </div>
    <a href="logout.php" style="padding:20px; color:#ff7675; text-decoration:none; border-top: 1px solid #4a443f; font-weight: bold;">🚪 Đăng xuất</a>
</div>

<div class="main-content">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'album_deleted' || $_GET['msg'] == 'deleted' || $_GET['msg'] == 'msg_deleted'): ?>
            <div class="alert alert-danger"> Đã xóa thành công!</div>
        <?php elseif($_GET['msg'] == 'added' || $_GET['msg'] == 'updated' || $_GET['msg'] == 'status_updated'): ?>
            <div class="alert alert-success"> Cập nhật dữ liệu thành công!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
    <?php 
    switch($view) {
        case 'packages': ?>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2>✦ Danh sách gói chụp</h2>
                <a href="package_add.php" class="btn" style="background:var(--success); color:white; border:none;">+ Thêm gói mới</a>
            </div>
            <table>
                <thead>
                    <tr><th>Ảnh</th><th>Tên gói</th><th>Giá</th><th>Mô tả</th><th>Thao tác</th></tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM packages ORDER BY price ASC");
                    while($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><img src="assets/img/<?= $row['image_url'] ?>" width="60" style="border-radius:4px;"></td>
                        <td><strong><?= $row['name'] ?></strong><br><small>(<?= $row['subtitle'] ?>)</small></td>
                        <td><b style="color:var(--success)"><?= number_format($row['price'], 0, ',', '.') ?>đ</b></td>
                        <td style="font-size:12px; color:#777; max-width:250px;"><?= nl2br($row['description']) ?></td>
                        <td>
                            <a href="package_edit.php?id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                            <a href="package_delete.php?id=<?= $row['id'] ?>" class="btn" 
                               style="color:var(--danger); border-color:var(--danger); margin-left:5px;"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa gói [<?= $row['name'] ?>] không?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php break;

        case 'gallery':
            echo '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">';
            echo '<h2 style="margin:0;">✦ Quản lý Gallery</h2>';
            echo '<a href="gallery_add.php" class="btn" style="background:var(--success); color:white; border:none;">+ Tạo bộ ảnh mới</a>';
            echo '</div>';
            
            $sql_albums = "SELECT a.*, p.name as package_name, 
                          (SELECT COUNT(*) FROM galleries WHERE album_id = a.id) as total_photos 
                          FROM albums a 
                          LEFT JOIN packages p ON a.package_id = p.id 
                          ORDER BY a.id DESC";
            $res_albums = mysqli_query($conn, $sql_albums);
            
            echo '<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:25px;">';
            if (mysqli_num_rows($res_albums) > 0) {
                while($row = mysqli_fetch_assoc($res_albums)) {
                    $thumb = (strpos($row['thumbnail'], 'http') !== false) ? $row['thumbnail'] : "assets/img/".$row['thumbnail'];
                    if(empty($row['thumbnail'])) $thumb = "assets/img/default-album.jpg";
                    
                    echo "
                    <div style='background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.05); border:1px solid #eee; transition:0.3s;'>
                        <div style='position:relative; height:180px;'>
                            <img src='$thumb' style='width:100%; height:100%; object-fit:cover;'>
                            <span style='position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.6); color:white; padding:3px 10px; border-radius:20px; font-size:11px;'>
                                {$row['total_photos']} ảnh
                            </span>
                        </div>
                        <div style='padding:15px; text-align:center;'>
                            <p style='font-size:11px; color:var(--main-gold); text-transform:uppercase; font-weight:bold; margin-bottom:5px;'>{$row['package_name']}</p>
                            <h3 style='margin:0 0 15px 0; font-size:16px; color:#333;'>{$row['title']}</h3>
                            <div style='display:flex; justify-content:center; gap:10px; border-top:1px solid #f5f5f5; padding-top:15px;'>
                                <a href='album_edit.php?id={$row['id']}' class='btn' style='color:#8e44ad; border-color:#8e44ad; flex:1; font-size:12px;'>Sửa bộ ảnh</a>
                                <a href='album_delete.php?id={$row['id']}' class='btn' style='color:var(--danger); border-color:var(--danger); flex:1; font-size:12px;' 
                                   onclick=\"return confirm('Cảnh báo: Xóa bộ ảnh [{$row['title']}] sẽ xóa toàn bộ các tấm ảnh bên trong. Bạn có chắc không?')\">
                                   Xóa bộ ảnh
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align:center; color:#999; padding:50px;'>Chưa có bộ sưu tập nào được tạo.</p>";
            }
            echo '</div>';
            break;

        case 'messages':
        echo '<h2>✦ Quản lý Tin nhắn</h2>';
        $res = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC");
        echo '<table><thead><tr><th>Khách hàng</th><th>Nội dung & Phản hồi</th><th>Thao tác</th></tr></thead><tbody>';
        
        while($row = mysqli_fetch_assoc($res)) {
            $is_rep = $row['is_replied'];
            echo "<tr>
                <td><strong>{$row['name']}</strong><br><small>{$row['phone']}</small></td>
                <td>
                    <div>{$row['message']}</div>";
                    if($is_rep) {
                        echo "<div style='color:var(--success); font-size:13px; margin-top:5px;'><b>Rep:</b> {$row['reply_content']}</div>";
                    } else {
                        echo "<div id='form-{$row['id']}' style='display:none; margin-top:10px;'>
                                <form action='message_reply.php' method='POST'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <textarea name='reply_text' style='width:100%; border-radius:5px; border:1px solid #ddd;' required></textarea>
                                    <button type='submit' class='btn' style='background:var(--success); color:white; border:none; margin-top:5px;'>Gửi</button>
                                </form>
                            </div>";
                    }
            echo "</td>
                <td>";
                    if(!$is_rep) {
                        echo "<button class='btn btn-edit' onclick=\"document.getElementById('form-{$row['id']}').style.display='block'; this.style.display='none'\">Trả lời</button>";
                    }
                    echo "<a href='message_delete.php?id={$row['id']}' style='color:var(--danger); margin-left:10px; text-decoration:none;' onclick=\"return confirm('Xóa tin nhắn này?')\">Xóa</a>
                </td>
            </tr>";
        }
        echo '</tbody></table>';
        break;
        
        default: ?>
            <h2>✦ Danh sách lịch đặt chụp</h2>
            <table>
                <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Thông tin chụp</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM bookings ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($res)): 
                        $st_class = ($row['status']=='Đã xác nhận')?'stt-confirmed':(($row['status']=='Đã hủy')?'stt-canceled':'stt-pending');
                    ?>
                    <tr>
                        <td><strong>#<?= $row['code'] ?></strong></td>
                        <td><?= $row['name'] ?><br><small><?= $row['phone'] ?></small></td>
                        <td><?= $row['package_name'] ?><br><small><?= $row['date'] ?> | <?= $row['time'] ?></small></td>
                        <td><span class="status <?= $st_class ?>"><?= $row['status'] ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Chờ xác nhận'): ?>
                                <a href="update_status.php?id=<?= $row['id'] ?>&status=confirmed" class="btn" style="color:var(--success)" onclick="return confirm('Duyệt đơn?')">Duyệt</a>
                                <a href="update_status.php?id=<?= $row['id'] ?>&status=canceled" class="btn" style="color:var(--danger)" onclick="return confirm('Hủy đơn?')">Hủy</a>
                            <?php else: ?>
                                <span class="btn btn-disabled">Xong</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php break;
    } ?>
    </div>
</div>
</body>
</html>