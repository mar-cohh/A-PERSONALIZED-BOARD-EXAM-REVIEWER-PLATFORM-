<?php
include 'config/dbconfig.php';
include_once 'class/class_take.php';
 
$database = new Database();
$db = $database->getConnection();

$take = new Take($db);

if (!empty($_POST['action']) && $_POST['action'] == 'getExamEnroll') {
    $take->subjectid = $_POST['subject_id']; // This now matches the class property
    $take->getExamEnroll();
}
 

if (!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
     $take->deleteUserId = $_POST["userId"];
     $take->delete();
}
 
?>
