<?php

include 'config/dbconfig.php';
include_once 'class/class_admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin->email = $_POST['email'];
    $admin->password = $_POST['password'];

    // Call the login method with email and password
    $loginResult = $admin->login();

   
    if ($loginResult['status'] === 'success') {
        // Set session variables for the user
        session_start(); 
        $_SESSION['admin_id'] = $loginResult['admin']['id'];
        $_SESSION['admin_name'] = $loginResult['admin']['email'];
        $_SESSION['success'] = $loginResult['message']; // Set success message
        
        header("Location: index.php");
        exit();
    } else {
        // Set error message for pending approval or invalid credentials
        $_SESSION['error'] = $loginResult['message']; // Set error message
        header("Location: login.php");
        exit();
    }
}

?>
