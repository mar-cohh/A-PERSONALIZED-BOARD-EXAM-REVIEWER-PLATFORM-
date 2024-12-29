<?php
class User {	
   
	private $userTable = 'exam_user';	

    private $courseTable = 'exam_course';
	private $conn;

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listUsers() {
        // Columns mapping for sorting
        
        // Start building the SQL query to fetch the user data
        $sqlQuery = "
            SELECT u.id, u.first_name, u.last_name, u.email, u.is_approved, c.course AS course
            FROM " . $this->userTable . " AS u
            LEFT JOIN " . $this->courseTable . " AS c ON u.course_id = c.id
        ";
        
        $columns = ['id', 'first_name', 'last_name', 'email', 'is_approved', 'course'];
    
        // Apply sorting if provided by DataTables
        if (!empty($_POST["order"])) {
            // Get the column index and direction for sorting
            $columnIndex = $_POST['order'][0]['column']; // Column index
            $columnName = $columns[$columnIndex]; // Map column index to column name
            $orderDirection = $_POST['order'][0]['dir']; // Ascending or descending
    
            // Add ORDER BY clause to the query
            $sqlQuery .= " ORDER BY " . $columnName . " " . $orderDirection;
        } else {
            // Default sorting by 'id'
            $sqlQuery .= " ORDER BY u.is_approved ASC";
        }
    
        // Apply pagination if specified
        if ($_POST["length"] != -1) {
            $sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
    
        // Execute the query to fetch the user data
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Get the total number of records for pagination
        $stmtTotal = $this->conn->prepare("
            SELECT COUNT(u.id) AS total
            FROM " . $this->userTable . " AS u
            LEFT JOIN " . $this->courseTable . " AS c ON u.course_id = c.id
        ");
        $stmtTotal->execute();
        $totalResult = $stmtTotal->get_result();
        $totalRecords = $totalResult->fetch_assoc()['total'];
    
        // Prepare data for DataTables response
        $displayRecords = $result->num_rows;
        $records = array();
        $counter = $_POST['start'] + 1;
    
        while ($user = $result->fetch_assoc()) {
            $rows = array();
            $rows[] = $counter++;  // Increment the row counter
            $rows[] = ucfirst($user['first_name'] . " " . $user['last_name']);
            $rows[] = $user['email'];
            $rows[] = $user['course'];
            $rows[] = $user['is_approved'] ? '<span class="label label-success">Approved</span>' : '<span class="label label-danger">Pending</span>';
    
            // Add action buttons based on approval status
            if (!$user['is_approved']) {
                $rows[] = '<button type="button" name="view" id="' . $user["id"] . '" class="btn btn-info btn-xs view"> View Details </button>';
                $rows[] = '<button type="button" name="approve" id="' . $user["id"] . '" class="btn btn-warning btn-xs approve">Accept</button>';
            } else {
                $rows[] = '<button type="button" name="view" id="' . $user["id"] . '" class="btn btn-info btn-xs view"> View Details </button>';
                $rows[] = '<button type="button" name="approve" id="' . $user["id"] . '" class="btn btn-success btn-xs approve" disabled> Accepted </button>';
                $rows[] = '<button type="button" name="delete" id="' . $user["id"] . '" class="btn btn-danger btn-xs delete"> Delete </button>';
            }
            $rows[] = '<button type="button" name="delete" id="' . $user["id"] . '" class="btn btn-danger btn-xs delete"> Decline </button>';
    
            $records[] = $rows;
        }
    
        // Prepare the response data
        $output = array(
            "draw" => intval($_POST["draw"]),
            "iTotalRecords" => $displayRecords,  // Records shown on this page
            "iTotalDisplayRecords" => $totalRecords,  // Total records available in the database
            "data" => $records  // Data to be displayed in the DataTable
        );
    
        // Output as JSON for DataTables
        echo json_encode($output);
    }
    

    public function getUserDetails($userId) {
        
        $query = "SELECT id, first_name, last_name, email, created FROM " . $this->userTable . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); 
        } else {
            return null;
        }
    }

	public function approve ($userId) {
        $query = "UPDATE " . $this->userTable . " SET is_approved = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userId);

        // Execute the query and return true if successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	public function delete() {    
		
		if($this->deleteUserId) {		          
			$queryDelete = "
				DELETE FROM ".$this->userTable." 
				WHERE id = ?";				
			$stmt = $this->conn->prepare($queryDelete);
			$stmt->bind_param("i", $this->deleteUserId);	
			$stmt->execute();		
		}
	} 
    
    public function register($course_id) {
        $checkEmailQuery = 'SELECT * FROM ' . $this->userTable . ' WHERE email = ? LIMIT 1';
        $checkStmt = $this->conn->prepare($checkEmailQuery);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $checkStmt->bind_param('s', $this->email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return [

                'status' => 'error',
                'message' => 'Email already in use',
            ]; 
        }

        $query = 'INSERT INTO ' . $this->userTable . ' (first_name, last_name, email, password, created, course_id) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->course_id = $course_id; 

    
        // Bind data using bind_param (notice the 's' stands for string)
        $stmt->bind_param('sssssi', $this->first_name, $this->last_name, $this->email, $this->password, $this->created, $this->course_id);
        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Registered Successfully!',
            ];
        }
    
        return false;
    }
}
?>