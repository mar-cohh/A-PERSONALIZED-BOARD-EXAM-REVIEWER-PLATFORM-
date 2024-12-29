<?php

class Questions {	
   
	private $examTable = 'exam_subject';
	private $questionTable = 'exam_question';
	private $optionTable = 'exam_option';
	private $conn;
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	// Update method to use subjectid
	public function listQuestions(){	
		$sqlQuery = "
			SELECT questions.id, questions.question, questions.answer, options.title as option_title
			FROM ".$this->questionTable." AS questions
			LEFT JOIN ".$this->examTable." AS subject ON questions.subject_id = subject.id
			LEFT JOIN ".$this->optionTable." AS options ON options.option = questions.answer AND questions.id = options.question_id
			WHERE questions.subject_id = ? 
			GROUP BY questions.id";
		
		if(!empty($_POST["order"])){
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY questions.id ASC ';
		}
		
		if($_POST["length"] != -1){
			$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->bind_param("i", $this->subjectid); // Use subjectid
		$stmt->execute();
		$result = $stmt->get_result();	
		
		$stmtTotal = $this->conn->prepare("
			SELECT COUNT(*) as count
			FROM ".$this->questionTable."
			WHERE subject_id = ?");
		$stmtTotal->bind_param("i", $this->subjectid); // Use subjectid
		$stmtTotal->execute();
		$totalResult = $stmtTotal->get_result()->fetch_assoc();
		$allRecords = $totalResult['count'];
		
		$displayRecords = $result->num_rows;
		$records = array();	

		$counter = $_POST['start'] + 1; 
	
		while ($question = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $counter++;
			$rows[] = $question['question'];
			$rows[] = $question['option_title'];			
			$rows[] = '<button type="button" name="update" id="'.$question["id"].'" class="btn btn-warning btn-xs update"><i class="fi fi-rr-edit"></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$question["id"].'" class="btn btn-danger btn-xs delete"><i class="fi fi-rr-trash"></i></button>';		
			$records[] = $rows;
		}
		
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$displayRecords,
			"iTotalDisplayRecords"	=>  $allRecords,
			"data"	=> 	$records
		);
		
		echo json_encode($output);
	}

	

	public function getQuestion(){
		if($this->question_id) {			
			$sqlQuery = "
			SELECT questions.id as question_id, questions.question, questions.answer, options.id as option_id, options.option, options.title
			FROM ".$this->optionTable." AS options 
			LEFT JOIN ".$this->questionTable." AS questions ON options.question_id = questions.id
			WHERE questions.id = ?";			
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->question_id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($question = $result->fetch_assoc()) { 				
				$rows = array();			
				$rows['question_id'] = $question['question_id'];
				$rows['question'] = $question['question'];			
				$rows['answer'] = $question['answer'];
				$rows['option_id'] = $question['option_id'];
				$rows['option'] = $question['option'];	
				$rows['title'] = $question['title'];				
				$records[] = $rows;
			}		
			$output = array(			
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
	
	//final 	
		
	public function insert() {
		if ($this->subject_id && $this->question_title && $this->answer_option) {
			$this->question_title = htmlspecialchars(strip_tags($this->question_title));
			$this->subject_id = intval($this->subject_id); // Ensure it's an integer
			$this->answer_option = intval($this->answer_option); // Ensure it's an integer
			// Check if the question already exists
			if ($this->questionExists($this->subject_id, $this->question_title) > 0) {
				return json_encode(["status" => "error", "message" => "Failed to Save, Question '" . $this->question_title . "' ALREADY EXISTS!"]);
			}
			else {
				// Step 1: Get the question limit for the subject
				$stmtLimit = $this->conn->prepare("SELECT question_limit FROM " . $this->examTable . " WHERE id = ?");
				$stmtLimit->bind_param("i", $this->subject_id);
				$stmtLimit->execute();
				$resultLimit = $stmtLimit->get_result();
				$subject = $resultLimit->fetch_assoc();
				$questionLimit = $subject['question_limit'];
		
				// Step 2: Count the existing questions for the subject
				$stmtCount = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->questionTable . " WHERE subject_id = ?");
				$stmtCount->bind_param("i", $this->subject_id);
				$stmtCount->execute();
				$resultCount = $stmtCount->get_result();
				$count = $resultCount->fetch_assoc()['count'];
		
				// Step 3: Check if the count is less than the question limit
				if ($count < $questionLimit) {
					
					// Prepare statement for question insertion
					$stmt = $this->conn->prepare("
						INSERT INTO " . $this->questionTable . " (`subject_id`, `question`, `answer`)
						VALUES (?, ?, ?)");
					
					$stmt->bind_param("isi", $this->subject_id, $this->question_title, $this->answer_option);
				
					if ($stmt->execute()) {
						$lastInsertQuestionId = $this->conn->insert_id;
		
						// Prepare statement for options insertion
						$stmt1 = $this->conn->prepare("
							INSERT INTO " . $this->optionTable . " (`question_id`, `option`, `title`)
							VALUES (?, ?, ?)");
						
						foreach ($this->option as $key => $value) {
							$stmt1->bind_param("iis", $lastInsertQuestionId, $key, $value);
							if (!$stmt1->execute()) {
								return json_encode(["status" => "error", "message" => "Error inserting option for question ID $lastInsertQuestionId!"]);
							}
						}
						return json_encode(["status" => "success", "message" => "Question added successfully!"]);
					} else {
						return json_encode(["status" => "error", "message" => "Error inserting question: " . $stmt->error]);
					}
				} else {
					return json_encode(["status" => "error", "message" => "Failed to Save, Already reached limit of ".$questionLimit." questions!"]);
				}
			}
		}
		return json_encode(["status" => "error", "message" => "Invalid input data."]);
	}
	   
	   
	
	
	public function update(){
		
		if($this->question_id && $this->question_title && $this->answer_option) {	
			
			$this->question_id = intval($this->question_id);
			$this->question_title = htmlspecialchars(strip_tags($this->question_title));
			$this->answer_option  = intval($this->answer_option);

			$stmt = $this->conn->prepare("
			UPDATE ".$this->questionTable." 
			SET question = ?, answer = ?
			WHERE id = ?");
	 
			$stmt->bind_param("sii", $this->question_title, $this->answer_option, $this->question_id);
			
			if($stmt->execute()){
				$stmt1 = $this->conn->prepare("					
					UPDATE ".$this->optionTable." 
					SET title = ?
					WHERE option = ? AND question_id = ?");
			
				foreach($this->option as $key => $value) {					
					$stmt1->bind_param("sii", $value, $key, $this->question_id);
					$stmt1->execute();
				}
				return json_encode(["status" => "success", "message" => "Question Updated successfully!"]);
			} 
			else{
				return json_encode(["status" => "error", "message" => "Error Updating Question: " . $stmt->error]);
			}
			
		}	
	}	
	
	public function delete() {
		if ($this->question_id) {
		    // Start a transaction
		    $this->conn->begin_transaction();
		    try {
			 // First, delete all options related to the question
			 $stmt1 = $this->conn->prepare("
			     DELETE FROM " . $this->optionTable . " 
			     WHERE question_id = ?");
			 $stmt1->bind_param("i", $this->question_id);
			 $stmt1->execute();
	   
			 // Now delete the question itself
			 $stmt2 = $this->conn->prepare("
			     DELETE FROM " . $this->questionTable . " 
			     WHERE id = ?");
			 $this->question_id = htmlspecialchars(strip_tags($this->question_id));
			 $stmt2->bind_param("i", $this->question_id);
			 $stmt2->execute();
	   
			 // Commit the transaction
			 $this->conn->commit();
			 return true;
		    } catch (Exception $e) {
			 // Rollback the transaction in case of an error
			 $this->conn->rollback();
			 return false;
		    }
		}
		return false; // Return false if no question_id is set
	}

	public function questionExists($subject_id, $question_title) {
		$query = "SELECT COUNT(*) FROM exam_question WHERE subject_id = ? AND question = ?";
		$stmt = $this->conn->prepare($query);
		if ($stmt === false) {
			die("Error preparing statement: " . $this->conn->error);
		}
		$stmt->bind_param("is", $subject_id, $question_title); // Bind parameters
		$stmt->execute();
		$result = $stmt->get_result();
		
		// Fetch the count
		$count = $result->fetch_row()[0];
		return $count > 0; // Returns true if exists, false otherwise
	}

}
?>