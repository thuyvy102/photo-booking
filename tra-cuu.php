<?php 
include 'header.php'; 
// Đảm bảo đường dẫn này đúng với file kết nối database của bạn
include 'config/db.php'; 
?>

<main class="container" style="margin-top: 100px; min-height: 80vh;">
    <div class="contact-form-wrapper" style="max-width: 600px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 15px 45px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 20px; letter-spacing: 1px;">TRA CỨU LỊCH HẸN</h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px; font-size: 14px;">
            Nhập mã tra cứu (ví dụ: 0FCD69FC) để xem trạng thái lịch chụp của bạn.
        </p>

        <form action="" method="GET" class="main-form">
            <div class="form-group" style="margin-bottom: 20px;">
                <input type="text" name="code" placeholder="Nhập mã tra cứu của bạn..." 
                       value="<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>"
                       required style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 4px; text-align: center; letter-spacing: 2px; font-weight: bold; outline: none;">
            </div>
            <button type="submit" class="btn-aesthetic" style="background: #b5a48d; color: white; width: 100%; border: none; padding: 16px; border-radius: 4px; font-weight: 600; cursor: pointer; transition: 0.3s;">
                KIỂM TRA NGAY
            </button>
        </form>

        <?php
        if (isset($_GET['code'])) {
            $code = mysqli_real_escape_string($conn, $_GET['code']);
            
            // Truy vấn tìm kiếm mã trong bảng bookings
            $sql = "SELECT * FROM bookings WHERE code = '$code'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                
                // CẬP NHẬT: Định dạng màu sắc dựa trên trạng thái thực tế trong DB
                $status_text = $row['status'];
                $status_color = ($status_text == 'Đã xác nhận') ? '#28a745' : '#e67e22'; // Xanh cho đã duyệt, Cam cho chờ duyệt
                
                echo "
                <div style='margin-top: 40px; padding: 25px; border: 1px dashed #b5a48d; border-radius: 8px; background: #fdfcf9;'>
                    <h4 style='margin-bottom: 15px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;'>Thông tin lịch hẹn: #".$row['code']."</h4>
                    <p style='margin-bottom: 10px;'><strong>Khách hàng:</strong> ".$row['name']."</p>
                    <p style='margin-bottom: 10px;'><strong>Ngày chụp:</strong> ".date('d/m/Y', strtotime($row['date']))."</p>
                    <p style='margin-bottom: 10px;'><strong>Giờ chụp:</strong> ".$row['time']."</p>
                    <p><strong>Trạng thái:</strong> <span style='color: ".$status_color."; font-weight: bold;'>".$status_text."</span></p>
                </div>";
            } else {
                echo "
                <div style='margin-top: 40px; padding: 20px; border: 1px solid #ff4444; border-radius: 8px; background: #fff5f5; color: #ff4444; text-align: center; font-size: 14px;'>
                    Rất tiếc! Mã tra cứu <strong>".htmlspecialchars($code)."</strong> không tồn tại trên hệ thống.
                </div>";
            }
        }
        ?>
    </div>
</main>

<?php include 'footer.php'; ?>