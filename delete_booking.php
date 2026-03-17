<?php
include 'config/db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id = $id");
}
header("Location: admin-dashboard.php");
?>