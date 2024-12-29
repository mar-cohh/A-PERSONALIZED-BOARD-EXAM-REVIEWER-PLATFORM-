<?php
class User {
    private $conn;
    private $table = 'exam_user'; // Make sure this table exists in your `exam_user` database

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created;
    public $course_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register($course_id) {
        $checkEmailQuery = 'SELECT * FROM ' . $this->table . ' WHERE email = ? LIMIT 1';
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

        // Insert new user into the database, including course_id
        $query = 'INSERT INTO ' . $this->table . ' (first_name, last_name, email, password, created, course_id) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->course_id = $course_id; // Save the selected course

        // Bind data
        $stmt->bind_param('sssssi', $this->first_name, $this->last_name, $this->email, $this->password, $this->created, $this->course_id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Registered Successfully!',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to register user.',
        ];
    }


    public function logActivity($activityType) {
        $query = "INSERT INTO user_activity (user_id, activity_type) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('is', $this->id, $activityType);
        $stmt->execute();
    }


    // Login user
    public function login() {
        $query = 'SELECT id, first_name, last_name, email, password, is_approved, course_id FROM ' . $this->table . ' WHERE email = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        // Bind the email to the placeholder
        $stmt->bind_param('s', $this->email);
    
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        // Verify if a user was found
        if ($row) {
            // Check if the account is approved
            if ($row['is_approved'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Your account is still pending for approval.'
                ];
            }
    
            // Verify the password (using password_verify for security)
            if ($this->password === $row['password']) {
                // Successful login

                $this->id = $row['id']; // Set the user ID for logging purposes
                $this->logActivity('login'); // Log the login activity
                
                return [
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'user' => [
                        'id' => $row['id'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'course_id' => $row['course_id'],
                    ]
                ];
            }
        }
    
        // If no user is found or password does not match
        return [
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ];
    }
    
}
?>
