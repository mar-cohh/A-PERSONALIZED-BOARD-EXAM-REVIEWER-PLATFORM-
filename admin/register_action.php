<?php
include 'config/dbconfig.php';
include_once 'class/class_admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin->first_name = $_POST['first_name'];
    $admin->last_name = $_POST['last_name'];
    $admin->email = $_POST['email'];
    $admin->password = $_POST['password'];
    $admin->created = date('Y-m-d H:i:s');


    $registerResult = $admin->register();

    session_start();

    if ($registerResult['status'] === 'error') {
        $_SESSION['error'] = $registerResult['message'];
        header("Location: register.php"); // Replace with your desired page
        exit();

    } else {
         $_SESSION['success'] = $registerResult['message'];
         header("Location: login.php");
        exit();
    }
}
?>