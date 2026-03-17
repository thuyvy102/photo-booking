<?php
include 'config/db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit(); }

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Câu lệnh toggle: Nếu là 0 thì lên 1, nếu là 1 thì về 0
    mysqli_query($conn, "UPDATE contacts SET is_replied = 1 - is_replied WHERE id = $id");
}
header("Location: admin-dashboard.php?view=messages");
exit();