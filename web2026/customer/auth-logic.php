<?php
require_once '../config/db.php';
session_start();
global $conn;

if (isset($_POST['register'])) {

    $name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, phone, password) 
            VALUES ('$name', '$email', '$phone', '$pass')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?msg=success");
    } else {
        echo "Error: Email might already exist!";
    }
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: index.php");
    } else {
        header("Location: login.php?error=1");
    }
}
?>