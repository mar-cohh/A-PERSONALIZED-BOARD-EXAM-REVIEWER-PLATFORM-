<?php
include 'config/dbconfig.php';
include_once 'class/class_eele.php';

$database = new Database();
$db = $database->getConnection();

$eele = new Eele ($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listExam') {
	$eele->listExam();
}
 
 if(!empty($_POST['action']) && $_POST['action'] == 'getExam') {
	$eele->id = $_POST["id"];
	$eele->getExam();      
}  

if(!empty($_POST['action']) && $_POST['action'] == 'addExam') {	
	$eele->subject = $_POST["subject"];
	$eele->course_id = $_POST["course_id"];
	$eele->insert();
}


 if(!empty($_POST['action']) && $_POST['action'] == 'updateExam') {
	$eele->id = $_POST["id"];
	$eele->subject = $_POST["subject"];    
	$eele->course_id = $_POST["course_id"];
	$eele->update();
}
if (!empty($_POST['action']) && $_POST['action'] == 'deleteExam') {
    // Ensure the ID is set and is an integer
    if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
        $eele->id = intval($_POST["id"]); // Cast to integer for safety

        // Attempt to delete and check the result
        if ($eele->delete()) {
            echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID!"]);
    }
}

?>