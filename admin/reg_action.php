<?php
include 'config/dbconfig.php';
include_once 'class/class_user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->created = date('Y-m-d H:i:s');

    $course_id = $_POST['course'];
    
    $registerResult = $user->register($course_id);

    session_start();

    if ($registerResult['status'] === 'error') {
        $_SESSION['error'] = $registerResult['message'];
        header("Location: user.php"); // Replace with your desired page
        exit();

    } else {
         $_SESSION['success'] = $registerResult['message'];
         header("Location: user.php");
        exit();
    }
}
?>