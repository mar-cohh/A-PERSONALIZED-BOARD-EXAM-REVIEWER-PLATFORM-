<?php
include 'config/dbconfig.php';
include_once 'class/class_blepts.php';

$database = new Database();
$db = $database->getConnection();

$blept = new Blept ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	$blept->listExam();
}
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$blept->id = $_POST["id"];
	$blept->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$blept->subject = $_POST["subject"];
	$blept->course_id = $_POST["course_id"];
	$blept->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$blept->id = $_POST["id"];
	$blept->subject = $_POST["subject"];    
	$blept->course_id = $_POST["course_id"];
	$blept->update();
}
if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
    if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
        $blept->id = intval($_POST["id"]);
        if ($blept->delete()) {
            echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
    }
}
?>