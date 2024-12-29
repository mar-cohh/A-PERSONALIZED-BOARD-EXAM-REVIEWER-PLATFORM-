<?php
include 'config/dbconfig.php';
include_once 'class/class_manage_exams.php';

$database = new Database();
$db = $database->getConnection();

$manageExams = new manageExams ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	echo $manageExams->listExam();
} 
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$manageExams->id = $_POST["id"];
	echo $manageExams->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$manageExams->subject = $_POST["subject"];
	$manageExams->course_id = $_POST["course_id"];
	$manageExams->question_limit = $_POST["question_limit"];
	echo $manageExams->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$manageExams->id = $_POST["id"];
	$manageExams->subject = $_POST["subject"];    
	$manageExams->course_id = $_POST["course_id"];
	$manageExams->question_limit = $_POST["question_limit"];
	echo $manageExams->update();
}

if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
	// Ensure the ID is set and is an integer
	if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	    $manageExams->id = intval($_POST["id"]); // Cast to integer for safety
   
	    // Attempt to delete and check the result
	    if ($manageExams->delete()) {
		 echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
	    } else {
		 echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
	    }
	} else {
	    echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
	}
   }
?>