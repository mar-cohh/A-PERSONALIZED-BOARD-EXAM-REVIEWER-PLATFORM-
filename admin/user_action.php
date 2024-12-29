<?php
include 'config/dbconfig.php';
include_once 'class/class_user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (!empty($_POST['action']) && $_POST['action'] == 'listUsers') {
    $user->listUsers();
}

if (isset($_POST['id']) && isset($_POST['action'])) {
    $userId = $_POST['id']; 

    // Approve user
    if ($_POST['action'] == 'approve') {
        if ($user->approve($userId)) {
            echo json_encode(['message' => "User has been approved successfully."]);
        } else {
            echo json_encode(['error' => "Error approving user."]);
        }
        exit;
    }

    // Fetch user details
    if ($_POST['action'] == 'getUserDetails') {
        $userDetails = $user->getUserDetails($userId);
        if ($userDetails) {
            echo json_encode($userDetails);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
        exit;
    }
}

if (!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
    $user->deleteUserId = $_POST["userId"];
    $user->delete();
}

?>
