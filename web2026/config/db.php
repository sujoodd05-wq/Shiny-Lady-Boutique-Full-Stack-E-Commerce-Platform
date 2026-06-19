<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "boutique_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function logActivity($action, $details) {
    global $conn;
    $admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 0;
    $admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'System';

    $action = mysqli_real_escape_string($conn, $action);
    $details = mysqli_real_escape_string($conn, $details);

    mysqli_query($conn, "INSERT INTO activity_log (admin_id, admin_name, action, details) VALUES ($admin_id, '$admin_name', '$action', '$details')");
}
?>

