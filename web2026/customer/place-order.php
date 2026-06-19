<?php
require_once '../config/db.php';
global $conn;

if (!$conn) {
    die("ERROR: Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name    = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $phone   = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $region  = isset($_POST['region']) ? mysqli_real_escape_string($conn, $_POST['region']) : '';
    $city    = isset($_POST['city']) ? mysqli_real_escape_string($conn, $_POST['city']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $total   = isset($_POST['total']) ? $_POST['total'] : 0;
    $items   = isset($_POST['items']) ? mysqli_real_escape_string($conn, $_POST['items']) : '';

    if (empty($name) || empty($phone)) {
        die("ERROR: Name or Phone is missing");
    }

    $sql = "INSERT INTO `orders` (customer_name, customer_phone, location, city, address, total_amount, order_items) 
            VALUES ('$name', '$phone', '$region', '$city', '$address', '$total', '$items')";

    if (mysqli_query($conn, $sql)) {
        echo "SUCCESS";
    } else {
        echo "SQL_ERROR: " . mysqli_error($conn);
    }
} else {
    echo "ERROR: Invalid Request Method";
}
?>