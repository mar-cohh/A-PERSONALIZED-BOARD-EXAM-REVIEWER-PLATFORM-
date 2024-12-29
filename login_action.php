
<?php

include 'admin/config/dbconfig.php';
include_once 'class_user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    $loginResult = $user->login();
    
    session_start(); // Ensure session is started before accessing $_SESSION

    if ($loginResult['status'] === 'success') {
        // Set session variables for the user
        $_SESSION['user_id'] = $loginResult['user']['id'];
        $_SESSION['user_name'] = $loginResult['user']['first_name'];
        $_SESSION['success'] = $loginResult['message']; // Set success message
        $_SESSION['course_id'] = $loginResult['user']['course_id'];
        
        header("Location: user/dashboard.php");
        exit();
    } else {
        // Set error message for pending approval or invalid credentials
        $_SESSION['error'] = $loginResult['message']; // Set error message
        header("Location: login.php");
        exit();
    }
}

?>
