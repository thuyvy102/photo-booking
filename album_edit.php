<?php
include 'config/db.php';
session_start();

// Bảo mật: Nếu chưa đăng nhập thì đá về trang login
if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin-login.php"); exit(); }

// 1. Lấy thông tin Album dựa trên ID từ URL
if (isset($_GET['id'])) {
    $album_id = (int)$_GET['id'];
    $album = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM albums WHERE id = $album_id"));
    if (!$album) { header("Location: admin-dashboard.php?view=gallery"); exit(); }
} else {
    header("Location: admin-dashboard.php?view=gallery");
    exit();
}

// 2. XỬ LÝ XÓA ẢNH LẺ TRONG BỘ (Đây là phần bà đang bị lỗi không load trang)
if (isset($_GET['delete_photo_id'])) {
    $pid = (int)$_GET['delete_photo_id'];
    mysqli_query($conn, "DELETE FROM galleries WHERE id = $pid");
    
    // Xóa xong phải dùng header để trình duyệt load lại URL sạch, không còn cái đuôi delete_photo_id nữa
    header("Location: album_edit.php?id=" . $album_id . "&msg=photo_deleted");
    exit();
}

// 3. XỬ LÝ CẬP NHẬT THÔNG TIN CHUNG (Tên, Phân loại)
if (isset($_POST['update_info'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $package_id = (int)$_POST['package_id'];
    
    $sql_up = "UPDATE albums SET title = '$title', package_id = '$package_id' WHERE id = $album_id";
    if (mysqli_query($conn, $sql_up)) {
        header("Location: album_edit.php?id=$album_id&msg=updated");
        exit();
    }
}

// 4. XỬ LÝ ĐĂNG THÊM ẢNH MỚI VÀO BỘ
if (isset($_POST['add_more_photos'])) {
    $urls = $_POST['new_urls'];
    foreach ($urls as $url) {
        if (!empty($url)) {
            $url = mysqli_real_escape_string($conn, $url);
            mysqli_query($conn, "INSERT INTO galleries (album_id, image_url) VALUES ('$album_id', '$url')");
        }
    }
    header("Location: album_edit.php?id=$album_id&msg=added");
    exit();
}

// Lấy danh sách gói chụp và danh sách ảnh hiện có để hiển thị
$packages_res = mysqli_query($conn, "SELECT id, name FROM packages ORDER BY name ASC");
$photos_res = mysqli_query($conn, "SELECT * FROM galleries WHERE album_id = $album_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Edit Album | Light Studio</title>
    <style>
        :root {
            --primary: #8c7867;
            --success: #27ae60;
            --danger: #e74c3c;
            --bg: #f8f9fa;
            --text: #333;
            --radius: 12px;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: var(--bg); 
            color: var(--text);
            margin: 0;
            padding: 40px 20px;
        }

        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            display: grid; 
            grid-template-columns: 350px 1fr; 
            gap: 30px; 
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #777;
            font-size: 14px;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--primary); }

        .card { 
            background: #fff; 
            padding: 30px; 
            border-radius: var(--radius); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            border: 1px solid rgba(0,0,0,0.03);
        }

        h3 { 
            margin: 0 0 20px 0; 
            font-size: 18px; 
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
        }

        label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            font-size: 13px;
            color: #666;
        }

        input, select { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid #eee; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(140, 120, 103, 0.1); }

        .btn { 
            width: 100%;
            padding: 14px; 
            border-radius: 8px; 
            cursor: pointer; 
            border: none; 
            font-weight: bold; 
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-update { background: var(--primary); color: white; margin-bottom: 30px; }
        .btn-update:hover { background: #766556; transform: translateY(-2px); }

        .btn-add { background: #34495e; color: white; }
        .btn-add:hover { background: #2c3e50; }

        .btn-more {
            background: transparent;
            border: 1px dashed #ccc;
            color: #888;
            margin-bottom: 15px;
        }
        .btn-more:hover { border-color: var(--primary); color: var(--primary); }

        .photo-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
            gap: 15px; 
        }

        .photo-item { 
            position: relative; 
            border-radius: 10px; 
            overflow: hidden; 
            height: 180px; 
            background: #eee;
        }

        .photo-item img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.5s;
        }

        .photo-item:hover img { transform: scale(1.1); }

        .del-photo { 
            position: absolute; 
            top: 8px; 
            right: 8px; 
            background: rgba(231, 76, 60, 0.9); 
            color: white; 
            text-decoration: none; 
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%; 
            font-size: 14px; 
            opacity: 0;
            transition: 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .photo-item:hover .del-photo { opacity: 1; }

        .section-divider {
            height: 1px;
            background: #eee;
            margin: 30px 0;
        }

        .msg-toast {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
            border: 1px solid #a5d6a7;
        }

    </style>
</head>
<body>

<div class="container">
    <div style="grid-column: 1 / -1;">
        <a href="admin-dashboard.php?view=gallery" class="back-link">← Quay lại Dashboard</a>
        <?php if(isset($_GET['msg'])) echo '<div class="msg-toast">Đã cập nhật dữ liệu bộ ảnh!</div>'; ?>
    </div>

    <div class="sidebar">
        <div class="card">
            <h3>✦ Chỉnh sửa</h3>
            <form action="" method="POST">
                <label>Tên concept:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($album['title']) ?>" required>

                <label>Gói dịch vụ:</label>
                <select name="package_id">
                    <?php while($p = mysqli_fetch_assoc($packages_res)): ?>
                        <option value="<?= $p['id'] ?>" <?= ($p['id']==$album['package_id'])?'selected':'' ?>><?= $p['name'] ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="update_info" class="btn btn-update">Lưu thay đổi</button>
            </form>

            <div class="section-divider"></div>

            <h3>✦ Thêm ảnh</h3>
            <form action="" method="POST">
                <div id="new-fields">
                    <input type="text" name="new_urls[]" placeholder="Thêm link ảnh">
                </div>
                <button type="button" class="btn btn-more" onclick="addField()">+ Thêm ô nhập link</button>
                <button type="submit" name="add_more_photos" class="btn btn-add">Tải ảnh lên bộ</button>
            </form>
        </div>
    </div>

    <div class="main">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                <h3 style="margin:0;">✦ Album Gallery</h3>
                <span style="font-size:13px; color:#999;"><?= mysqli_num_rows($photos_res) ?> ảnh trong concept</span>
            </div>
            
            <div class="photo-grid">
                <?php while($img = mysqli_fetch_assoc($photos_res)): 
                    $src = (strpos($img['image_url'], 'http') !== false) ? $img['image_url'] : "assets/img/".$img['image_url'];
                ?>
                    <div class="photo-item">
                        <img src="<?= $src ?>">
                        <a href="album_edit.php?id=<?= $album_id ?>&delete_photo_id=<?= $img['id'] ?>" 
                           class="del-photo" onclick="return confirm('Xóa tấm ảnh này?')">✕</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function addField() {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'new_urls[]';
        input.className = 'new-input-url'; // Thêm class nếu muốn style riêng
        input.placeholder = 'Dán link ảnh tiếp theo...';
        input.style.marginBottom = "10px";
        document.getElementById('new-fields').appendChild(input);
    }
</script>

</body>
</html>