<?php
include 'config/db.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if (!isset($_SESSION['admin_logged_in'])) { exit(); }

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $st = $_GET['status'];
    
    $res = mysqli_query($conn, "SELECT * FROM bookings WHERE id = $id");
    $info = mysqli_fetch_assoc($res);

    if ($info) {
        $new_status = ($st == 'confirmed') ? 'Đã xác nhận' : 'Đã hủy';
        
        if (mysqli_query($conn, "UPDATE bookings SET status = '$new_status' WHERE id = $id")) {
            
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'yahsightlee@gmail.com'; 
                $mail->Password   = 'ftdg iimq owfk rnvn'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('yahsightlee@gmail.com', 'Light Studio');
                $mail->addAddress($info['email'], $info['name']);
                $mail->isHTML(true);

                $random_id = substr(md5(time()), 0, 5);

                if ($st == 'confirmed') {
                    // Mẫu Mail Xác Nhận theo ý của bạn
                    $mail->Subject = '[Light Studio] Xác nhận lịch hẹn thành công - #' . $info['code'];
                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333;'>
                        Chào {$info['name']},<br><br>
                        <strong>Light Studio</strong> rất vui được thông báo lịch hẹn của bạn đã được <strong>xác nhận thành công!</strong><br><br>
                        <strong>Thông tin chi tiết buổi chụp:</strong><br>
                        - Mã đơn: #{$info['code']}<br>
                        - Gói chụp: {$info['package_name']}<br>
                        - Thời gian: {$info['time']} | Ngày: {$info['date']}<br><br>
                        Chúng mình đã sẵn sàng mọi thứ để cùng bạn ghi lại những khung hình tuyệt vời nhất. Dưới đây là một vài lưu ý nhỏ để buổi chụp diễn ra hoàn hảo:<br>
                        <ul style='padding-left: 20px;'>
                            <li>Bạn vui lòng có mặt trước 15 phút để chuẩn bị trang phục và làm quen với không gian nhé.</li>
                            <li>Đừng quên mang theo những bộ đồ yêu thích hoặc phụ kiện cá nhân mà bạn muốn xuất hiện trong ảnh.</li>
                            <li>Nếu có bất kỳ thay đổi nào, hãy liên hệ với tụi mình sớm qua số hotline hoặc email này nhé.</li>
                        </ul>
                        <br>
                        Tụi mình đã sẵn sàng để cùng bạn tạo nên những khung hình tuyệt vời nhất. Hẹn gặp bạn tại Studio!<br><br>
                        Trân trọng,<br>
                        <strong>Đội ngũ Light Studio</strong>
                        <div style='display:none;'>$random_id</div>
                    </div>";
                } else {
                    // Mẫu Mail Báo Hủy
                    $mail->Subject = '[Light Studio] Thông báo điều chỉnh lịch hẹn - #' . $info['code'];
                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333;'>
                        Chào {$info['name']},<br><br>
                        <strong>Light Studio</strong> rất tiếc phải thông báo rằng vì một vài lý do khách quan ngoài ý muốn, tụi mình chưa thể thực hiện buổi chụp cho gói <strong>{$info['package_name']}</strong> như dự kiến.<br><br>
                        Tụi mình biết việc này có thể gây bất tiện cho kế hoạch của bạn và chân thành xin lỗi về sự cố này. Để bù đắp, Studio rất mong được hỗ trợ bạn <strong>thay đổi sang một khung giờ hoặc ngày khác</strong> phù hợp hơn, kèm theo những ưu đãi đặc biệt cho lần đặt lịch này.<br><br>
                        Bạn vui lòng phản hồi lại email này hoặc liên hệ hotline để tụi mình sắp xếp lại lịch mới ưu tiên cho bạn ngay nhé.<br><br>
                        Rất mong nhận được sự cảm thông từ bạn.<br><br>
                        Trân trọng,<br>
                        <strong>Đội ngũ Light Studio</strong>
                        <div style='display:none;'>$random_id</div>
                    </div>";
                }

                $mail->send();
            } catch (Exception $e) { }
            
            header("Location: admin-dashboard.php?view=bookings&msg=status_updated");
            exit();
        }
    }
}
?>