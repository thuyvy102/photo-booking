<?php
    /**
     * TRANG THÔNG BÁO ĐẶT LỊCH - LIGHT STUDIO
     * Xử lý hiển thị trạng thái Thành công hoặc Lỗi từ hệ thống đặt lịch
     */
    
    // 1. Nhận và lọc dữ liệu từ URL để tránh lỗi bảo mật và hiển thị
    $status  = isset($_GET['status'])  ? $_GET['status']  : 'error';
    $name    = isset($_GET['name'])    ? htmlspecialchars($_GET['name']) : 'Quý khách';
    $code    = isset($_GET['code'])    ? htmlspecialchars($_GET['code']) : '';
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Khung giờ này đã có người đặt hoặc xảy ra lỗi hệ thống.';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo | Light Studio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* CSS nội bộ để đảm bảo trang luôn hiển thị đẹp dù file style.css có sự cố */
        body {
            background-color: #F2EFE9; /* var(--light-cream) */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .notification-card {
            background: #FFFFFF; /* var(--white) */
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(88, 71, 56, 0.12);
            max-width: 450px;
            width: 90%;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #FFFFFF;
        }

        .icon-circle.success { background-color: #584738; } /* var(--mahogany) */
        .icon-circle.error { background-color: #d9534f; }

        h1 { 
            color: #1E100A; /* var(--chocolate) */
            margin-bottom: 15px; 
            font-weight: 800;
            font-size: 28px;
        }

        .info-box {
            background-color: #F2EFE9;
            padding: 20px;
            border-radius: 12px;
            margin: 25px 0;
            border: 1px dashed #584738;
        }

        .booking-code {
            font-size: 30px;
            font-weight: 800;
            color: #584738;
            letter-spacing: 3px;
            margin-top: 8px;
            text-transform: uppercase;
        }

        .email-note { 
            font-size: 13px; 
            color: #666; 
            font-style: italic;
            line-height: 1.5;
        }

        .btn-group {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-action {
            display: inline-block;
            padding: 14px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-mahogany {
            background-color: #584738;
            color: #FFFFFF;
        }

        .btn-mahogany:hover {
            background-color: #1E100A;
            transform: translateY(-2px);
        }

        .btn-outline {
            color: #584738;
            border: 1px solid #584738;
        }

        .btn-outline:hover {
            background-color: #584738;
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <div class="notification-card">
        <?php if($status == 'success'): ?>
            <div class="icon-circle success">✓</div>
            <h1>Đặt lịch thành công!</h1>
            <p>Cảm ơn <strong><?php echo $name; ?></strong>,<br>yêu cầu của bạn đã được hệ thống ghi nhận.</p>
            
            <div class="info-box">
                <span style="font-size: 14px; color: #584738;">Mã tra cứu của bạn:</span>
                <div class="booking-code"><?php echo $code; ?></div>
            </div>
            
            <p class="email-note">
                ✦ Một email xác nhận chứa mã tra cứu đã được gửi đến bạn.<br>
                ✦ Vui lòng chụp lại màn hình này để đối soát khi nhận ảnh.
            </p>

            <div class="btn-group">
                <a href="index.php" class="btn-action btn-mahogany">Quay lại Trang chủ</a>
            </div>

        <?php else: ?>
            <div class="icon-circle error">!</div>
            <h1>Rất tiếc!</h1>
            <p><?php echo $message; ?></p>
            
            <div class="btn-group">
                <a href="booking.php" class="btn-action btn-mahogany">Thử chọn giờ khác</a>
                <a href="index.php" class="btn-action btn-outline">Về trang chủ</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>