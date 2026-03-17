<?php 
// Kết nối database ở đầu file
include 'config/db.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lịch - Light Studio</title>
    </head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="booking-page-wrapper">
        <div class="booking-container">
            <div class="booking-form-header">
                <div class="form-logo"><span class="star">✦</span> LIGHT STUDIO</div>
                <h2>Thông tin đặt lịch</h2>
                <p>Vui lòng điền đầy đủ thông tin bên dưới để đặt lịch chụp (Tối thiểu trước 1 ngày).</p>
            </div>

            <form action="ajax/book.php" method="POST" class="main-booking-form">
                <div class="input-group">
                    <input type="text" name="fullname" placeholder="Họ và tên" required>
                </div>

                <div class="input-group">
                    <input type="tel" name="phone" placeholder="Số điện thoại" required>
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="Địa chỉ Email" required>
                </div>

                <div class="input-group">
                    <select name="package" required>
                        <option value="" disabled selected>Chọn gói chụp</option>
                        <?php
                        // TRUY VẤN DỮ LIỆU TỪ BẢNG PACKAGES
                        $sql_pkgs = "SELECT * FROM packages ORDER BY price ASC";
                        $res_pkgs = mysqli_query($conn, $sql_pkgs);

                        if ($res_pkgs && mysqli_num_rows($res_pkgs) > 0) {
                            while($pkg = mysqli_fetch_assoc($res_pkgs)) {
                                $name = htmlspecialchars($pkg['name']);
                                $price = number_format($pkg['price'], 0, ',', '.');
                                echo "<option value='$name'>$name - {$price}đ</option>";
                            }
                        } else {
                            echo "<option value=''>Đang cập nhật các gói chụp...</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="datetime-row">
                    <div class="input-group">
                        <label>Ngày chụp:</label>
                        <input type="date" id="date-picker" name="date" required>
                    </div>
                    <div class="input-group">
                        <label>Giờ chụp:</label>
                        <select id="time-picker" name="time" required>
                            <option value="08:00">08:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="14:00">02:00 PM</option>
                            <option value="17:00">05:00 PM</option>
                            <option value="20:00">08:00 PM</option>
                        </select>
                    </div>
                </div>
                
                <div class="input-group">
                    <textarea name="message" placeholder="Lời nhắn (yêu cầu về trang phục, bối cảnh...)" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; box-sizing: border-box;"></textarea>
                </div>

                <button type="submit" class="btn-send-booking">Gửi yêu cầu đặt lịch ngay</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date-picker');
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(now.getDate() + 1);

            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');
            
            const minDateValue = `${yyyy}-${mm}-${dd}`;
            dateInput.setAttribute('min', minDateValue);
            dateInput.value = minDateValue;
        });
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>