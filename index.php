<?php 
include 'config/db.php'; 
include 'header.php'; 
?>
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>LƯU GIỮ TỪNG KHOẢNH KHẮC</h1>
            <a href="booking.php" class="btn-banner">Đặt lịch ngay</a>
        </div>
    </section>

    <section class="packages">
        <?php
        $sql = "SELECT * FROM packages ORDER BY price ASC";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)):
            $id = $row['id'];
            $featured = ($id == 2) ? "featured" : "";
        ?>
        <div class="card <?php echo $featured; ?>">
            <div class="card-img-container">
            <?php 
                // Kiểm tra xem image_url là link web (http) hay là tên file local
                $image_src = $row['image_url'];
                if (strpos($image_src, 'http') === false) {
                    $image_src = "assets/img/" . $image_src;
                }
            ?>
            <img src="<?php echo $image_src; ?>" alt="<?php echo $row['name']; ?>">
            </div>
            <h3><?php echo mb_strtoupper($row['name']); ?><br><span>(<?php echo $row['subtitle']; ?>)</span></h3>
            <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</p>
            <div class="description">
                <ul>
                    <?php 
                    $desc_items = explode("\n", $row['description']); 
                    foreach($desc_items as $item): 
                        if(trim($item) != ""): 
                    ?>
                        <li>✦ <?php echo htmlspecialchars(trim($item)); ?></li>
                    <?php 
                        endif; 
                    endforeach; 
                    ?>
                </ul>
            </div>
            <a href="booking.php?package=<?php echo urlencode($row['name']); ?>" class="btn-card">Đặt lịch ngay</a>
        </div>
        <?php endwhile; ?>
    </section>
<section class="gallery-section" style="padding: 60px 0; background: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 1.8rem; letter-spacing: 3px; color: #333;">GALLERY</h2>
            <div style="width: 50px; height: 2px; background: #b5a48d; margin: 15px auto;"></div>
            <p style="color: #777;">Những khoảnh khắc tuyệt vời được ghi lại bởi Light Studio</p>
        </div>

        <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
            <?php
            // Chỉnh sửa: LIMIT 4 để chỉ lấy đúng 4 tấm ảnh mới nhất, tránh bị nhảy xuống hàng
            $sql_gal = "SELECT * FROM galleries ORDER BY id DESC LIMIT 4";
            $res_gal = mysqli_query($conn, $sql_gal);

            if (mysqli_num_rows($res_gal) > 0) {
                while($img = mysqli_fetch_assoc($res_gal)) {
                    $img_src = (strpos($img['image_url'], 'http') !== false) ? $img['image_url'] : "assets/img/".$img['image_url'];
            ?>
                <div class="gallery-item" style="overflow: hidden; border-radius: 4px; height: 300px; position: relative; cursor: pointer;">
                    <img src="<?php echo $img_src; ?>" alt="Light Studio Gallery" 
                         style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s; display: block;">
                    
                    <div class="gallery-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s;">
                        <span style="color: #fff; border: 1px solid #fff; padding: 8px 15px; font-size: 12px; font-weight: bold; letter-spacing: 1px;">XEM ẢNH</span>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='text-align:center; grid-column: 1/-1; color: #999;'>Đang cập nhật hình ảnh...</p>";
            }
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="gallery.php" style="text-decoration: none; color: #333; font-weight: bold; border: 1px solid #333; padding: 12px 30px; font-size: 13px; display: inline-block; transition: 0.3s;">XEM TẤT CẢ TÁC PHẨM</a>
        </div>
    </div>
    </section>

    <style>
        .gallery-item:hover img { transform: scale(1.1); }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-section a:hover { background: #333; color: #fff !important; }
    </style>

<style>
    .gallery-item:hover img { transform: scale(1.1); }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
</style>

<?php if(file_exists('footer.php')) { include 'footer.php'; } ?>
</body>
</html>