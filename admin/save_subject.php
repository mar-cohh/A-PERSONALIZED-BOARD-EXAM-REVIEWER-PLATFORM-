<?php

// dbconfig.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reviewer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is available
if (isset($_POST['exam_subject']) && isset($_POST['total_question'])) {
    // Get form data
    $exam_subject = $_POST['exam_subject'];
    $total_question = $_POST['total_question'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO admin_subject (exam_subject, total_question) VALUES (?, ?)");

    // Check if prepare was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("si", $exam_subject, $total_question); // 's' for string, 'i' for integer

    // Execute the query
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "Required data not provided.";
}
?>

