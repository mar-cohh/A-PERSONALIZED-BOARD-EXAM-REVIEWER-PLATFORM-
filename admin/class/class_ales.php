
<?php
class Ales {	

	private $examTable = 'exam_subject';
    private $questionTable = 'exam_question';
    private $optionTable = 'exam_option';
    private $courseTable = 'exam_course'; 
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }	    
    
    public function listExam() {
	// Prepare base SQL query
	
	$searchValue = !empty($_POST["search"]["value"]) ? $_POST["search"]["value"] : '';
	$orderColumnIndex = !empty($_POST["order"]) ? $_POST['order']['0']['column'] : 0; // Default to first column
	$orderDir = !empty($_POST["order"]) ? $_POST['order']['0']['dir'] : 'ASC';
	$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
	$length = isset($_POST['length']) ? (int)$_POST['length'] : 5; // Default length to 5
   
	// Map the column index to actual column names
	$columns = ['es.id', 'es.subject', 'ec.course', 'es.question_limit'];
	$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'es.id';
   

	$sqlQuery = "
	    SELECT es.id, es.subject, es.question_limit, ec.course
	    FROM " . $this->examTable . " es
	    JOIN " . $this->courseTable . " ec ON es.course_id = ec.id
	    WHERE ec.course = 'ALE'
	";
   
	
	// Prepare SQL for filtering (search)
	$filterQuery = '';
	if (!empty($searchValue)) {
	    $filterQuery = " AND (es.subject LIKE  ? OR ec.course LIKE ?)";
	}
   
	// Full SQL with filtering, ordering, and pagination
	$sqlQuery .= $filterQuery . " ORDER BY $orderColumn $orderDir ";
   
	if ($length != -1) {
	    $sqlQuery .= " LIMIT ?, ?";
	}
   
	// Prepare and execute the statement
	$stmt = $this->conn->prepare($sqlQuery);
   
	if ($filterQuery) {
	    $searchValueLike = "%$searchValue%";
	    if ($length != -1) {
		 $stmt->bind_param('ssii', $searchValueLike, $searchValueLike, $start, $length);
	    } else {
		 $stmt->bind_param('ss', $searchValueLike, $searchValueLike);
	    }
	} else {
	    if ($length != -1) {
		 $stmt->bind_param('ii', $start, $length);
	    }
	}
   
	if (!$stmt->execute()) {
	    echo json_encode(array("error" => $stmt->error));
	    return;
	}
   
	$result = $stmt->get_result();
   
	// Get the total number of records (before filtering)
	$stmtTotal = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->examTable . " es JOIN " . $this->courseTable . " ec ON es.course_id = ec.id WHERE ec.course = 'ALE'");
	$stmtTotal->execute();
	$allResult = $stmtTotal->get_result()->fetch_assoc();
	$allRecords = $allResult['count'];
   
	// Get the total number of records (after filtering)
	$stmtFilteredTotal = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->examTable . " es JOIN " . $this->courseTable . " ec ON es.course_id = ec.id WHERE ec.course = 'ALE' " . $filterQuery);
	if ($filterQuery) {
	    $stmtFilteredTotal->bind_param('ss', $searchValueLike, $searchValueLike);
	}
	$stmtFilteredTotal->execute();
	$filteredResult = $stmtFilteredTotal->get_result()->fetch_assoc();
	$filteredRecords = $filteredResult['count'];
   
	// Calculate the starting counter based on the current page
	$counter = $start + 1; // Start from the current index + 1
	$records = [];
	while ($subject = $result->fetch_assoc()) {
	    $rows = [];
	    $rows[] = $counter++; // Increment counter
	    $rows[] = $subject['subject'];
	    $rows[] = $subject['course'];
	    $rows[] = '<a type="button" name="view" href="question.php?subject_id=' . $subject["id"] . '" class="btn btn-info btn-xs">Questions</a>';
	    $rows[] = '<a type="button" name="enroll" href="take.php?subject_id=' . $subject["id"] . '" class="btn btn-primary btn-xs">Users</a>';
	    $rows[] = '<button type="button" name="update" id="' . $subject["id"] . '" class="btn btn-warning btn-xs update"><i class="fi fi-rr-edit"></i></button>';
	    $rows[] = '<button type="button" name="delete" id="' . $subject["id"] . '" class="btn btn-danger btn-xs delete"><i class="fi fi-rr-trash"></i></button>';
	    $records[] = $rows;
	}
   
	// Prepare the output for DataTables
	$output = [
	    "draw" => intval($_POST["draw"]),
	    "iTotalRecords" => $allRecords,
	    "iTotalDisplayRecords" => $filteredRecords,
	    "data" => $records
	];
   
	// Output the result in JSON format
	return json_encode($output);
   }
	
		public function getExam(){
			if($this->id) {
				$sqlQuery = "
					SELECT id, subject, course_id, question_limit FROM ".$this->examTable." 
					WHERE id = ?";			
				$stmt = $this->conn->prepare($sqlQuery);
				$stmt->bind_param("i", $this->id);	
				$stmt->execute();
				$result = $stmt->get_result();
				$record = $result->fetch_assoc();
				return json_encode($record);
			}
		}

		public function insert() {
			if ($this->subject && $this->course_id && $this->question_limit) { // Ensure course_id is also provided
				$stmt = $this->conn->prepare("
					INSERT INTO " . $this->examTable . " (`subject`, `course_id`, `question_limit`)
					VALUES (?, ?, ?)");
				
				$this->subject = htmlspecialchars(strip_tags($this->subject));            
				$this->course_id = htmlspecialchars(strip_tags($this->course_id)); // Sanitize course_id
				$this->question_limit = htmlspecialchars(strip_tags($this->question_limit));
				
				$stmt->bind_param("sii", $this->subject, $this->course_id, $this->question_limit); // Bind course_id as an integer
				
				if ($stmt->execute()) {
					return json_encode(["status" => "success", "message" => "Exam Subject added successfully!"]);
				}         
			}  
			return json_encode(["status" => "error", "message" => "Failed to Save, Please Try Again "]);
		}
	//update
	public function update() {
		if ($this->id) { 
			// Check if course_id and id exist in exam_result
			$checkQuery = "
            SELECT COUNT(*) as count 
            FROM exam_result 
            WHERE course_id = ? AND subject_id = ? ";
        
			$checkStmt = $this->conn->prepare($checkQuery);
			$this->course_id = htmlspecialchars(strip_tags($this->course_id)); // Sanitize course_id
			$this->id = htmlspecialchars(strip_tags($this->id)); // Sanitize id
			$checkStmt->bind_param("ii", $this->course_id, $this->id);
			$checkStmt->execute();
			$checkResult = $checkStmt->get_result()->fetch_assoc();
			
			if ($checkResult['count'] > 0) {
				return json_encode(["status" => "error", "message" => "Cannot update. The Exam Subject was Already Taken by Users.\nYou can only Edit Exam that was Not Taken Yet"]);
			}
			 else {
				$stmt = $this->conn->prepare("  
				UPDATE " . $this->examTable . " 
				SET subject = ?, course_id = ? , question_limit = ?
				WHERE id = ?");
			
				$this->id = htmlspecialchars(strip_tags($this->id));
				$this->subject = htmlspecialchars(strip_tags($this->subject));            
				$this->course_id = htmlspecialchars(strip_tags($this->course_id)); // Sanitize course_id
				$this->question_limit = htmlspecialchars(strip_tags($this->question_limit));
				
				$stmt->bind_param("siii", $this->subject, $this->course_id, $this->question_limit, $this->id);
				
				if ($stmt->execute()) {
					return json_encode(["status" => "success", "message" => "Exam Subject Updated successfully!"]);
				}
				else{
					return json_encode(["status" => "error", "message" => "Error Updating Exam Subject: " . $stmt->error]);
				}
			}
		}    
	}
	
	public function delete(){
		if($this->id) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->examTable." 
				WHERE id = ?");
			$this->id = htmlspecialchars(strip_tags($this->id));
			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){
				return true;
			}
		}
	}
	
}
?>