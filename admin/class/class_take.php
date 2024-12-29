<?php
class Take {	
    private $userTable = 'exam_user';	
    private $subjectTable = 'exam_subject';	
    private $courseTable = 'exam_course';	
    private $resultTable = 'exam_result';	

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getExamEnroll() {

        //final code 
        $sqlQuery = "
            SELECT er.id, u.first_name, u.last_name, s.subject AS subject, c.course AS course, 
                er.score, er.total_questions, er.created_at AS take_date
                FROM " . $this->resultTable . " AS er
                JOIN " . $this->subjectTable . " AS s ON s.id = er.subject_id
                JOIN " . $this->courseTable . " AS c ON c.id = er.course_id
                JOIN " . $this->userTable . " AS u ON u.id = er.user_id
                WHERE er.subject_id = ?"; // Filtering by subject_id";
            
        // Handle ordering
        $columns = ['er.id', 'u.first_name', 'u.last_name', 's.subject', 'c.course', 'er.score', 'er.total_questions', 'er.created_at'];

        if (!empty($_POST["order"])) {
            $columnIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            $orderColumn = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'er.created_at';
            $sqlQuery .= " ORDER BY $orderColumn $dir ";
        } else {
            $sqlQuery .= ' ORDER BY er.created_at ASC ';
        }
    
        // Handle pagination
        if ($_POST["length"] != -1) {
            $sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
    
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bind_param("i", $this->subjectid); // Ensure subjectid is set
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Count total records
        $stmtTotal = $this->conn->prepare("
            SELECT COUNT(*) as count
            FROM " . $this->resultTable . "
            WHERE subject_id = ?");
        $stmtTotal->bind_param("i", $this->subjectid); 
        $stmtTotal->execute();
        $totalResult = $stmtTotal->get_result()->fetch_assoc();
        $allRecords = $totalResult['count'];
    
        $displayRecords = $result->num_rows;
        $records = array();

        $counter = $_POST['start'] + 1; 
    
        while ($row = $result->fetch_assoc()) {                
            $rows = array();   
            $rows[] = $counter++;                  // Fetching exam_result ID
            $rows[] = ucfirst($row['first_name']." ".$row['last_name']);          // Fetching user_id from exam_result
            $rows[] = $row['subject'];              // Fetching subject from exam_subject
            $rows[] = $row['course'];               // Fetching course from exam_course
            $rows[] = $row['score'];                // Fetching score
            $rows[] = $row['total_questions'];      // Fetching total_questions
            $rows[] = $row['take_date'];  
            $rows[] = '<button type="button" name="delete" id="'. $row["id"].'" class="btn btn-danger btn-xs delete"><i class="fi fi-rr-trash"></i></button>';
            $records[] = $rows;
        }
    
        $output = array(
            "draw" => intval($_POST["draw"]),            
            "iTotalRecords" => $displayRecords,
            "iTotalDisplayRecords" => $allRecords,
            "data" => $records
        );
    
        echo json_encode($output);
    }
    

    public function delete() {    
		
		if($this->deleteUserId) {		          
			$queryDelete = "
				DELETE FROM ".$this->resultTable." 
				WHERE id = ?";				
			$stmt = $this->conn->prepare($queryDelete);
			$stmt->bind_param("i", $this->deleteUserId);	
			$stmt->execute();		
		}
	} 
    
}
?>
