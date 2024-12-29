<?php
include 'config/dbconfig.php';
include_once 'class/class_ales.php';

$database = new Database();
$db = $database->getConnection();

$ales = new Ales ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	echo $ales->listExam();
} 
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$ales->id = $_POST["id"];
	echo $ales->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$ales->subject = $_POST["subject"];
	$ales->course_id = $_POST["course_id"];
	$ales->question_limit = $_POST["question_limit"];
	echo $ales->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$ales->id = $_POST["id"];
	$ales->subject = $_POST["subject"];    
	$ales->course_id = $_POST["course_id"];
	$ales->question_limit = $_POST["question_limit"];
	echo $ales->update();
}

if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
	// Ensure the ID is set and is an integer
	if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	    $ales->id = intval($_POST["id"]); // Cast to integer for safety
   
	    // Attempt to delete and check the result
	    if ($ales->delete()) {
		 echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
	    } else {
		 echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
	    }
	} else {
	    echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
	}
   }
?>