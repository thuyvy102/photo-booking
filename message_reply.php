<?php
include 'config/db.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Đảm bảo đường dẫn này chính xác tuyệt đối trên máy bạn
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if (!isset($_SESSION['admin_logged_in'])) { exit(); }

if (isset($_POST['id']) && isset($_POST['reply_text'])) {
    $id = (int)$_POST['id'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply_text']);
    
    // 1. Lấy Thông tin khách hàng
    $check = mysqli_query($conn, "SELECT email, name FROM contacts WHERE id = $id");
    $cust = mysqli_fetch_assoc($check);

    // KIỂM TRA: Email có tồn tại và không trống không
    if ($cust && !empty($cust['email'])) {
        
        // 2. Cập nhật Database trước
        $sql = "UPDATE contacts SET is_replied = 1, reply_content = '$reply' WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $mail = new PHPMailer(true);
            try {
                // Cấu hình Server SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'yahsightlee@gmail.com'; 
                $mail->Password   = 'ftdg iimq owfk rnvn'; // PHẢI LÀ MẬT KHẨU ỨNG DỤNG 16 KÝ TỰ
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Người nhận & Người gửi
                $mail->setFrom('yahsightlee@gmail.com', 'Light Studio');
                $mail->addAddress($cust['email'], $cust['name']);

                // Nội dung Email
                $mail->isHTML(true);
                $mail->Subject = "Phản hồi từ Light Studio";
                
                $random_id = substr(md5(time()), 0, 5);

                $mail->Body = "
                <div style='font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333;'>
                    Chào {$cust['name']},<br><br>
                    Cảm ơn bạn đã liên hệ với <strong>Light Studio</strong>. Về thắc mắc của bạn, Studio xin được phản hồi như sau:<br><br>
                    $reply<br><br>
                    Hy vọng câu trả lời trên đã giải đáp được phần nào thắc mắc của bạn. Nếu cần hỗ trợ thêm bất kỳ thông tin nào khác, bạn đừng ngần ngại phản hồi lại email này hoặc liên hệ trực tiếp qua số hotline của Studio nhé.<br><br>
                    Chúc bạn một ngày tốt lành và nhiều niềm vui!<br><br>
                    Trân trọng,<br>
                    <strong>Đội ngũ Light Studio</strong>
                    <div style='display:none;'>$random_id</div>
                </div>";

                $mail->send();
                // Thành công: Chuyển hướng về trang quản lý
                header("Location: admin-dashboard.php?view=messages&msg=updated");
            } catch (Exception $e) {
                // LỖI: Hiển thị lỗi ra màn hình để debug nếu không gửi được mail
                echo "Lỗi gửi mail: " . $mail->ErrorInfo;
                exit();
            }
            exit();
        }
    } else {
        echo "Lỗi: Khách hàng này không có địa chỉ Email trong dữ liệu!";
    }
}
?>