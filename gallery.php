<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gallery | Light Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #fdfcfb; color: #333; }
        .nav-simple { padding: 20px; text-align: center; border-bottom: 1px solid #eee; background: #fff; }
        .nav-simple a { text-decoration: none; color: #8c7867; font-weight: bold; letter-spacing: 2px; }
        
        .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .header { text-align: center; margin-bottom: 60px; }
        .header h1 { font-size: 2.5rem; letter-spacing: 5px; margin-bottom: 10px; color: #4a3f35; }
        
        /* Album Card Styling */
        .album-section { margin-bottom: 80px; }
        .album-info { margin-bottom: 25px; border-left: 4px solid #8c7867; padding-left: 20px; }
        .album-info h2 { margin: 0; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 2px; }
        .album-info span { color: #999; font-size: 14px; }

        .photo-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 15px; 
        }
        .photo-item { 
            height: 350px; 
            overflow: hidden; 
            border-radius: 4px; 
            background: #eee; 
            position: relative;
        }
        .photo-item img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.6s; 
            cursor: pointer;
        }
        .photo-item:hover img { transform: scale(1.05); }
    </style>
</head>
<body>

<div class="nav-simple">
    <a href="index.php">✦ LIGHT STUDIO</a>
</div>

<div class="container">
    <div class="header">
        <h1>ALL WORKS</h1>
        <p style="color:#888;">Khám phá những khoảnh khắc nghệ thuật qua từng ống kính</p>
    </div>

    <?php
    // 1. Lấy danh sách các Album (Bộ sưu tập)
    $sql_albums = "SELECT a.*, p.name as package_name FROM albums a 
                   LEFT JOIN packages p ON a.package_id = p.id 
                   ORDER BY a.id DESC";
    $res_albums = mysqli_query($conn, $sql_albums);

    while($album = mysqli_fetch_assoc($res_albums)):
        $album_id = $album['id'];
    ?>
        <div class="album-section">
            <div class="album-info">
                <h2><?= $album['title'] ?></h2>
                <span><?= $album['package_name'] ?></span>
            </div>

            <div class="photo-grid">
                <?php
                // 2. Lấy tất cả ảnh thuộc Album này
                $res_photos = mysqli_query($conn, "SELECT * FROM galleries WHERE album_id = $album_id");
                while($img = mysqli_fetch_assoc($res_photos)):
                    $src = (strpos($img['image_url'], 'http') !== false) ? $img['image_url'] : "assets/img/".$img['image_url'];
                ?>
                    <div class="photo-item">
                        <a href="<?= $src ?>" data-lightbox="album-<?= $album_id ?>" data-title="<?= $album['title'] ?>">
                            <img src="<?= $src ?>" alt="Gallery Image">
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<footer style="text-align:center; padding:50px; color:#ccc; font-size:12px; letter-spacing:2px;">
    &copy; 2026 LIGHT STUDIO - MOMENTS CAPTURED
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
</body>
</html>