<?php 
// 1. Kết nối database
include 'config/db.php'; 
// 2. Gọi Header
include 'header.php'; 
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;800&display=swap" rel="stylesheet">

<main class="about-container">
    <section class="intro-header section-padding">
        <div class="content-limit center-align">
            <h1 class="main-title">LIGHT STUDIO</h1>
            <p class="subtitle">Nơi nghệ thuật ánh sáng kể câu chuyện của riêng bạn</p>
            <div class="divider"></div>
            <p class="intro-text">
                Tại Light Studio, chúng tôi không chỉ chụp ảnh, chúng tôi tạo nên những tác phẩm nghệ thuật 
                đóng gói cảm xúc. Với tôn chỉ tôn vinh nét đẹp tự nhiên và cá tính riêng biệt, 
                mỗi khung hình là một sự đầu tư nghiêm túc về ánh sáng và góc độ.
            </p>
        </div>
    </section>

    <?php 
    $sql = "SELECT * FROM about_sections ORDER BY sort_order ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $is_alt = ($row['layout_type'] == 'alternate') ? 'alternate' : '';
            $subs = !empty($row['sub_images']) ? explode(',', $row['sub_images']) : [];
            ?>
            <section class="service-block <?php echo $is_alt; ?>">
                <div class="content-limit grid-2">
                    
                    <div class="col-content">
                        <?php if($is_alt == ''): // Layout Normal: Ảnh to bên trái ?>
                            <div class="block-image-hero">
                                <img src="<?php echo $row['image_url']; ?>" alt="Main Photo">
                            </div>
                        <?php else: // Layout Alternate: Chữ + Ảnh nhỏ bên trái ?>
                            <div class="block-text">
                                <h3><?php echo strtoupper(htmlspecialchars($row['title'])); ?></h3>
                                <div class="title-line-small"></div>
                                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                                <?php if(!empty($subs)): ?>
                                    <div class="image-sub-grid">
                                        <?php foreach($subs as $url): ?>
                                            <div class="sub-thumb-item">
                                                <img src="<?php echo trim($url); ?>" alt="Sub Photo">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-content">
                        <?php if($is_alt == ''): // Layout Normal: Chữ + Ảnh nhỏ bên phải ?>
                            <div class="block-text">
                                <h3><?php echo strtoupper(htmlspecialchars($row['title'])); ?></h3>
                                <div class="title-line-small"></div>
                                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                                <?php if(!empty($subs)): ?>
                                    <div class="image-sub-grid">
                                        <?php foreach($subs as $url): ?>
                                            <div class="sub-thumb-item">
                                                <img src="<?php echo trim($url); ?>" alt="Sub Photo">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: // Layout Alternate: Ảnh to bên phải ?>
                            <div class="block-image-hero">
                                <img src="<?php echo $row['image_url']; ?>" alt="Main Photo">
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </section>
            <?php
        }
    }
    ?>

    <section class="final-statement section-padding">
        <div class="content-limit center-align">
            <p class="final-quote">Hơn cả một tấm hình đẹp, đó là trải nghiệm. Chúng tôi đồng hành cùng bạn từ khâu lên ý tưởng, chuẩn bị trang phục cho đến những bản in cuối cùng đạt độ hoàn thiện cao nhất.</p>
            <div class="cta-wrapper">
                <a href="booking.php" class="btn-aesthetic">
                    ĐẶT LỊCH NGAY
                </a>
            </div>
        </div>
    </section>
</main>

<style>
/* --- FIX TOÀN BỘ CSS --- */

.about-container { 
    background-color: #ffffff !important; 
    color: #1E100A; 
    font-family: 'Inter', sans-serif;
    margin: 0;
}

.content-limit { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
.section-padding { padding: 80px 0; }
.center-align { text-align: center; }

/* Typography */
.main-title { font-size: 3rem; font-weight: 800; letter-spacing: 12px; color: #584738; margin-bottom: 10px; }
.subtitle { color: #b5a48d; letter-spacing: 4px; text-transform: uppercase; font-weight: 600; font-size: 0.9rem; }
.divider { width: 50px; height: 1px; background: #333; margin: 30px auto; opacity: 0.3; }
.intro-text { line-height: 2.2; opacity: 0.8; max-width: 800px; margin: 40px auto 0; font-size: 1rem; }

/* Grid Bố cục */
.grid-2 { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 80px; 
    align-items: center; /* Căn giữa theo chiều dọc để cân đối với ảnh */
    margin-bottom: 100px;
}

/* Đảo Layout */
.service-block.alternate .grid-2 { direction: rtl; }
.service-block.alternate .col-content { direction: ltr; }

/* Text Blocks */
.block-text h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: 2px; color: #584738; }
.title-line-small { width: 40px; height: 2px; background: #b5a48d; margin-bottom: 30px; }
.block-text p { line-height: 2; color: #555; text-align: justify; font-size: 0.95rem; margin-bottom: 30px; }

/* Image Blocks - CHỈNH ẢNH CHÍNH NGẮN LẠI & BẰNG NHAU */
.block-image-hero img { 
    width: 100%; 
    height: 550px; /* Chiều cao cố định cho tất cả ảnh chính */
    object-fit: cover; /* Giữ tỷ lệ ảnh, cắt gọn phần thừa */
    border-radius: 4px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
}

/* Sub Images (2 ảnh nhỏ bên dưới text) */
.image-sub-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; }
.sub-thumb-item img { 
    width: 100%; 
    height: 200px; /* Chiều cao ảnh nhỏ */
    object-fit: cover; 
    border-radius: 2px; 
}

/* CTA Section - CHỮ ĐỨNG */
.final-quote { 
    font-size: 1.1rem; 
    line-height: 2; 
    margin-bottom: 50px; 
    font-style: normal; /* CHỮ ĐỨNG, KHÔNG NGHIÊNG */
    color: #444; 
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.btn-aesthetic {
    display: inline-block;
    padding: 18px 60px;
    background-color: #584738;
    color: #ffffff !important;
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 2px;
    border-radius: 50px;
    transition: 0.4s;
    min-width: 250px;
    text-align: center;
}
.btn-aesthetic:hover { background-color: #b5a48d; transform: translateY(-3px); }

@media (max-width: 768px) {
    .grid-2 { grid-template-columns: 1fr !important; gap: 40px; direction: ltr !important; }
    .block-image-hero img { height: 350px; } /* Trên mobile cho ngắn lại chút nữa */
}
</style>

<?php include 'footer.php'; ?>