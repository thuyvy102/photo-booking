<?php
include 'config/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yahsightlee@gmail.com'; // Email studio
        $mail->Password = 'ftdg iimq owfk rnvn';    // 16 ký tự mật khẩu ứng dụng
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('email-cua-ban@gmail.com', 'Light Studio');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Phản hồi từ Light Studio';
        $mail->Body = "<h3>Chào $name,</h3><p>" . nl2br($message) . "</p><br><p>Trân trọng,<br>Light Studio Team</p>";

        $mail->send();
        header("Location: admin-dashboard.php?msg=sent");
    } catch (Exception $e) {
        echo "Lỗi: " . $mail->ErrorInfo;
    }
    // ... đoạn code gửi mail thành công ...
    if ($mail->send()) {
        // Cập nhật trạng thái đã trả lời vào database
        $contact_id = $_POST['contact_id']; // Đảm bảo bạn có truyền contact_id từ form qua
        mysqli_query($conn, "UPDATE contacts SET is_replied = 1 WHERE id = '$contact_id'");
        
        header("Location: admin-dashboard.php?msg=sent");
    }
}
?>