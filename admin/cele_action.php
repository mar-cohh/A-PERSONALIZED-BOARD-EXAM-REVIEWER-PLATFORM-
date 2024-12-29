<?php
include 'config/dbconfig.php';
include_once 'class/class_cele.php';

$database = new Database();
$db = $database->getConnection();

$cele = new Cele ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	$cele->listExam();
}
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$cele->id = $_POST["id"];
	$cele->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$cele->subject = $_POST["subject"];
	/* $cele->total_question = $_POST["total_question"]; */
	$cele->course_id = $_POST["course_id"];
	$cele->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$cele->id = $_POST["id"];
	$cele->subject = $_POST["subject"];    
	/* $cele->total_question = $_POST["total_question"]; */
	$cele->course_id = $_POST["course_id"];
	$cele->update();
}

if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
	// Ensure the ID is set and is an integer
	if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	    $cele->id = intval($_POST["id"]); // Cast to integer for safety
   
	    // Attempt to delete and check the result
	    if ($cele->delete()) {
		 echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
	    } else {
		 echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
	    }
	} else {
	    echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
	}
   }
?>