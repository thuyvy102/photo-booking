<?php
include 'config/db.php';

if (isset($_POST['add_pkg'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $img_url_input = mysqli_real_escape_string($conn, $_POST['img_url_input']);
    
    $final_image = "";

    // Ưu tiên lấy URL ảnh nếu có dán vào
    if (!empty($img_url_input)) {
        $final_image = $img_url_input;
    } elseif (!empty($_FILES['image']['name'])) {
        // Nếu không có URL thì mới xử lý upload file từ máy
        $file_name = time() . '_' . $_FILES['image']['name'];
        if(move_uploaded_file($_FILES['image']['tmp_name'], "assets/img/" . $file_name)){
            $final_image = $file_name;
        }
    }

    $sql = "INSERT INTO packages (name, subtitle, price, description, image_url) 
            VALUES ('$name', '$subtitle', '$price', '$description', '$final_image')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin-dashboard.php?view=packages&msg=added");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Gói Chụp Mới - Light Studio</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .add-card { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 15px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; transition: 0.3s; }
        .btn-save:hover { background: #219150; }
        .or-text { text-align: center; margin: 10px 0; color: #999; font-size: 12px; font-weight: bold; }
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="add-card">
        <h2 style="margin-top:0; color:#4a3f35;">✦ Thêm Gói Chụp Mới</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="input-group">
                <label>Tên gói (English):</label>
                <input type="text" name="name" placeholder="" required>
            </div>

            <div class="input-group">
                <label>Tên hiển thị (Tiếng Việt):</label>
                <input type="text" name="subtitle" placeholder="">
            </div>

            <div class="input-group">
                <label>Giá tiền (VNĐ):</label>
                <input type="number" name="price" placeholder="" required>
            </div>

            <div class="input-group" style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px dashed #ccc;">
                <label>Hình ảnh gói chụp:</label>
                <input type="text" name="img_url_input" placeholder="Link ảnh">
                <div class="or-text">HOẶC TẢI ẢNH TỪ MÁY TÍNH</div>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="input-group">
                <label>Mô tả dịch vụ (Mỗi dòng 1 ý):</label>
                <textarea name="description" rows="6" placeholder="Nhấn Enter để xuống dòng cho mỗi dấu ✦..."></textarea>
            </div>

            <button type="submit" name="add_pkg" class="btn-save">TẠO GÓI MỚI</button>
            <a href="admin-dashboard.php?view=packages" class="btn-back">Hủy bỏ và quay lại</a>
        </form>
    </div>
</body>
</html>