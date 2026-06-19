<?php
session_start();
require_once '../config/db.php';
global $conn;

if (isset($_POST['new_pass'])) {
    $admin_id = $_SESSION['admin_id'];
    $raw_password = $_POST['new_pass'];

    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $sql = "UPDATE admins SET password = '$hashed_password' WHERE id = $admin_id";

    if (mysqli_query($conn, $sql)) {
        logActivity("Security", "Admin changed their password");

        header("Location: settings.php?msg=PassUpdated");
    }
}
?>
