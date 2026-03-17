<?php
include 'config/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pkg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM packages WHERE id = $id"));
}

if (isset($_POST['update_pkg'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $img_url_input = mysqli_real_escape_string($conn, $_POST['img_url_input']);
    
    // XỬ LÝ ẢNH
    $final_image = $pkg['image_url']; // Mặc định giữ ảnh cũ

    if (!empty($img_url_input)) {
        // ƯU TIÊN 1: Nếu bà dán link URL
        $final_image = $img_url_input;
    } elseif (!empty($_FILES['image']['name'])) {
        // ƯU TIÊN 2: Nếu bà chọn file từ máy
        $file_name = time() . '_' . $_FILES['image']['name'];
        if(move_uploaded_file($_FILES['image']['tmp_name'], "assets/img/" . $file_name)){
            $final_image = $file_name;
        }
    }

    $sql = "UPDATE packages SET name='$name', subtitle='$subtitle', price='$price', description='$description', image_url='$final_image' WHERE id=$id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin-dashboard.php?view=packages&msg=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật Gói chụp</title>
    <style>
        .edit-card { max-width: 600px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); font-family: sans-serif; }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .img-preview { width: 100%; max-height: 200px; object-fit: cover; border-radius: 6px; margin-top: 10px; border: 1px solid #eee; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        .or-text { text-align: center; margin: 10px 0; color: #999; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="edit-card">
        <h2 style="margin-top:0;">✦ Cập nhật Gói chụp</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $pkg['id'] ?>">
            
            <div class="input-group">
                <label>Tên gói (English):</label>
                <input type="text" name="name" value="<?= $pkg['name'] ?>">
            </div>

            <div class="input-group">
                <label>Tên hiển thị (Tiếng Việt):</label>
                <input type="text" name="subtitle" value="<?= $pkg['subtitle'] ?>">
            </div>

            <div class="input-group">
                <label>Giá tiền (VNĐ):</label>
                <input type="number" name="price" value="<?= $pkg['price'] ?>">
            </div>

            <div class="input-group" style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <label>Ảnh đại diện:</label>
                <input type="text" name="img_url_input" placeholder="Dán link ảnh (URL) vào đây..." value="<?= (strpos($pkg['image_url'], 'http') !== false) ? $pkg['image_url'] : '' ?>">
                
                <div class="or-text">HOẶC TẢI FILE TỪ MÁY</div>
                
                <input type="file" name="image" accept="image/*">
                
                <p style="font-size: 11px; color: #666; margin-top:5px;">* Hiện tại đang dùng: <?= $pkg['image_url'] ?></p>
                <?php 
                    $src = (strpos($pkg['image_url'], 'http') !== false) ? $pkg['image_url'] : "assets/img/".$pkg['image_url'];
                ?>
                <img src="<?= $src ?>" class="img-preview">
            </div>

            <div class="input-group">
                <label>Mô tả dịch vụ:</label>
                <textarea name="description" rows="5"><?= $pkg['description'] ?></textarea>
            </div>

            <button type="submit" name="update_pkg" class="btn-save">LƯU THAY ĐỔI</button>
        </form>
    </div>
</body>
</html>