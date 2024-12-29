<?php
class Admin {
    private $conn;
    private $table = 'admin';

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register() {
       
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

        $query = 'INSERT INTO ' . $this->table . ' (first_name, last_name, email, password, created) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        /* $this->password = password_hash($this->password, PASSWORD_DEFAULT); */ // Hash the password
        $this->created = htmlspecialchars(strip_tags($this->created));
    
        // Bind data using bind_param (notice the 's' stands for string)
        $stmt->bind_param('sssss', $this->first_name, $this->last_name, $this->email, $this->password, $this->created);
    
        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Registered Successfully!',
            ];
        }
    
        return false;
    }
    // Login user
    public function login() {
        // Prepare the query
        $query = 'SELECT id, first_name, last_name, email, password FROM ' . $this->table . ' WHERE email = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $email = htmlspecialchars(strip_tags($this->email));
        
        // Bind the email to the placeholder
        $stmt->bind_param('s', $this->email);
        
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row) {
            // Directly compare the provided password with the stored password
            if ($this->password === $row['password']) {
                return [
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'admin' => [ 
                        'id' => $row['id'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                    ]
                   
                ];
            }
        }
        return [
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ];
    }
    
    
    
    
}
?>
