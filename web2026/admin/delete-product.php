<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $fetchQuery = "SELECT name, image FROM products WHERE id = $id";
    $fetchResult = mysqli_query($conn, $fetchQuery);
    $product = mysqli_fetch_assoc($fetchResult);

    if ($product) {
        $product_name = $product['name'];
        $image_name = $product['image'];

        $filePath = "../images/" . $image_name;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $admin_name = $_SESSION['user_name'];
        $log_details = "Deleted product: " . $product_name . " (ID: $id)";

        $logSql = "INSERT INTO activity_log (admin_id, admin_name, action, details) 
                   VALUES ('{$_SESSION['admin_id']}', '$admin_name', 'Inventory', '$log_details')";
        mysqli_query($conn, $logSql);

        $deleteSql = "DELETE FROM products WHERE id = $id";

        if (mysqli_query($conn, $deleteSql)) {
            header("Location: inventory.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting from database: " . mysqli_error($conn);
        }
    } else {
        echo "Product not found!";
    }
} else {
    header("Location: inventory.php");
    exit();
}
?>