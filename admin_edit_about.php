<?php
include 'config/db.php';

// 1. LẤY DỮ LIỆU CŨ ĐỂ ĐỔ VÀO FORM
if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM about_sections WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
    if(!$data) { echo "Không tìm thấy dữ liệu!"; exit; }
}

// 2. XỬ LÝ CẬP NHẬT KHI NHẤN NÚT LƯU
if(isset($_POST['update_section'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $sub_images = mysqli_real_escape_string($conn, $_POST['sub_images']);
    $layout_type = $_POST['layout_type'];
    $sort_order = (int)$_POST['sort_order'];

    $sql_update = "UPDATE about_sections SET 
                    title='$title', 
                    content='$content', 
                    image_url='$image_url', 
                    sub_images='$sub_images', 
                    layout_type='$layout_type', 
                    sort_order='$sort_order' 
                   WHERE id=$id";

    if(mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Cập nhật thành công!'); window.location='admin_manage_about.php';</script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa nội dung - Light Studio</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .admin-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-group { display: flex; gap: 10px; }
        .btn-save { background: #332a24; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; flex: 2; }
        .btn-back { background: #eee; color: #333; text-decoration: none; padding: 12px 20px; border-radius: 4px; text-align: center; flex: 1; }
    </style>
</head>
<body>

<div class="admin-container">
    <h2>Chỉnh sửa nội dung</h2>
    <form method="POST">
        <div class="form-group">
            <label>Tiêu đề:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($data['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nội dung:</label>
            <textarea name="content" rows="6" required><?php echo htmlspecialchars($data['content']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Link ảnh chính:</label>
            <input type="text" name="image_url" value="<?php echo $data['image_url']; ?>" required>
        </div>

        <div class="form-group">
            <label>Link ảnh phụ (cách nhau bằng dấu phẩy):</label>
            <input type="text" name="sub_images" value="<?php echo $data['sub_images']; ?>">
        </div>

        <div class="form-group">
            <label>Layout:</label>
            <select name="layout_type">
                <option value="normal" <?php if($data['layout_type'] == 'normal') echo 'selected'; ?>>Ảnh trái - Chữ phải</option>
                <option value="alternate" <?php if($data['layout_type'] == 'alternate') echo 'selected'; ?>>Chữ trái - Ảnh phải</option>
            </select>
        </div>

        <div class="form-group">
            <label>Thứ tự:</label>
            <input type="number" name="sort_order" value="<?php echo $data['sort_order']; ?>">
        </div>

        <div class="btn-group">
            <button type="submit" name="update_section" class="btn-save">Cập nhật thay đổi</button>
            <a href="admin_manage_about.php" class="btn-back">Quay lại</a>
        </div>
    </form>
</div>

</body>
</html>