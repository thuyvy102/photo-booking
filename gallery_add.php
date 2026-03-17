<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin-login.php"); exit(); }

// 1. Lấy danh sách Gói chụp để làm Phân loại (Category)
$packages_res = mysqli_query($conn, "SELECT id, name FROM packages ORDER BY name ASC");

// 2. Xử lý khi Admin nhấn nút "TẠO BỘ ẢNH MỚI"
if (isset($_POST['create_album'])) {
    $title = mysqli_real_escape_string($conn, $_POST['album_title']);
    $package_id = (int)$_POST['package_id'];
    $thumb_url = mysqli_real_escape_string($conn, $_POST['thumb_url']);
    
    // Xử lý upload ảnh đại diện bộ ảnh (Thumbnail)
    $final_thumb = $thumb_url;
    if (!empty($_FILES['thumb_file']['name'])) {
        $file_name = time() . '_thumb_' . $_FILES['thumb_file']['name'];
        move_uploaded_file($_FILES['thumb_file']['tmp_name'], "assets/img/" . $file_name);
        $final_thumb = $file_name;
    }

    $sql = "INSERT INTO albums (package_id, title, thumbnail) VALUES ('$package_id', '$title', '$final_thumb')";
    if (mysqli_query($conn, $sql)) {
        $new_album_id = mysqli_insert_id($conn);
        header("Location: gallery_add.php?album_id=$new_album_id&msg=album_created");
        exit();
    }
}

// 3. Xử lý khi Admin "ĐĂNG NHIỀU ẢNH VÀO BỘ"
if (isset($_POST['add_photos'])) {
    $album_id = (int)$_POST['album_id'];
    $urls = $_POST['photo_urls']; // Nhận mảng link ảnh

    foreach ($urls as $url) {
        if (!empty($url)) {
            $url = mysqli_real_escape_string($conn, $url);
            mysqli_query($conn, "INSERT INTO galleries (album_id, image_url) VALUES ('$album_id', '$url')");
        }
    }
    header("Location: admin-dashboard.php?view=gallery&msg=photos_added");
    exit();
}

// Lấy thông tin album nếu đang ở bước thêm ảnh
$current_album = null;
if(isset($_GET['album_id'])) {
    $aid = (int)$_GET['album_id'];
    $current_album = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM albums WHERE id = $aid"));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Album & Gallery - Light Studio</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 700px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .step-header { background: #8c7867; color: white; padding: 10px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        label { display: block; font-weight: bold; margin: 15px 0 5px; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-next { background: #27ae60; color: white; border: none; padding: 15px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; font-size: 16px; margin-top: 20px; }
        .photo-input-item { background: #f9f9f9; padding: 10px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #8c7867; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!$current_album): ?>
        <div class="step-header">THÊM ALBUM MỚI</div>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Tên Album:</label>
            <input type="text" name="album_title" placeholder="" required>

            <label>Phân loại:</label>
            <select name="package_id" required>
                <?php while($p = mysqli_fetch_assoc($packages_res)): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Ảnh bìa bộ sưu tập (URL Pinterest hoặc File):</label>
            <input type="text" name="thumb_url" placeholder="Link ảnh">
            <div style="text-align:center; font-size:11px; color:#999; margin:5px 0;">HOẶC</div>
            <input type="file" name="thumb_file">

            <button type="submit" name="create_album" class="btn-next">THÊM ẢNH VÀO ALBUM</button>
            <a href="admin-dashboard.php?view=gallery" style="display:block; text-align:center; margin-top:15px; color:#999; text-decoration:none;">Hủy bỏ</a>
        </form>

    <?php else: ?>
        <div class="step-header">THÊM ẢNH VÀO ALBUM "<?= mb_strtoupper($current_album['title']) ?>"</div>
        <p style="font-size: 13px; color: #666;">Dán link ảnh album vào bên dưới:</p>
        
        <form action="" method="POST">
            <input type="hidden" name="album_id" value="<?= $current_album['id'] ?>">
            
            <div id="photo-fields">
                <div class="photo-input-item"><input type="text" name="photo_urls[]" placeholder="Pic 1 (Pinterest/URL)"></div>
                <div class="photo-input-item"><input type="text" name="photo_urls[]" placeholder="Pic 2"></div>
                <div class="photo-input-item"><input type="text" name="photo_urls[]" placeholder="Pic 3"></div>
                <div class="photo-input-item"><input type="text" name="photo_urls[]" placeholder="Pic 4"></div>
            </div>

            <button type="button" onclick="addMoreField()" style="background:#eee; border:none; padding:8px; border-radius:4px; cursor:pointer; font-size:12px;">+ Thêm ô nhập link</button>

            <button type="submit" name="add_photos" class="btn-next">HOÀN TẤT VÀ ĐĂNG GALLERY</button>
        </form>

        <script>
            function addMoreField() {
                const div = document.createElement('div');
                div.className = 'photo-input-item';
                div.innerHTML = '<input type="text" name="photo_urls[]" placeholder="Dán link ảnh tiếp theo">';
                document.getElementById('photo-fields').appendChild(div);
            }
        </script>
    <?php endif; ?>
</div>

</body>
</html>