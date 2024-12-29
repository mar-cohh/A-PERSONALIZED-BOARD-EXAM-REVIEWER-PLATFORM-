<?php
// download.php

include 'config/dbconfig.php'; // Include DB config
include_once 'class/class_question.php'; // Include your Question class if needed


$database = new Database();
$db = $database->getConnection();

// Get the subject_id from the URL
if (isset($_GET['subject_id'])) {
    $subjectId = $_GET['subject_id'];  // Get the subject_id from the URL
} else {
    echo "Subject ID is required.";
    exit;
}

// Set headers to prompt a file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="question_template.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open the output stream
$output = fopen('php://output', 'w');

// Write the header row to the CSV (you can modify this header if needed)
$headers = ['subject_id', 'question_title', 'answer_option', 'option_1', 'option_2', 'option_3', 'option_4'];
fputcsv($output, $headers);

// Fetch the subject name (or any other data you want) based on the subject_id
$query = "SELECT id FROM exam_subject WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $subjectId);
$stmt->execute();
$result = $stmt->get_result();

// Check if subject exists
if ($result->num_rows > 0) {
    $subject = $result->fetch_assoc();

    // Now, create the CSV rows based on the specific subject
    // Here, we will include the subject_id for each row in the CSV
    // You can adjust the number of rows or the data as needed
    $row = [
        $subject['id'],           // subject_id (from the database)
        '',                       // question_title (leave empty for admin to fill in)
        '',                       // answer_option (leave empty for admin to fill in)
        '',                       // option_1 (leave empty for admin to fill in)
        '',                       // option_2 (leave empty for admin to fill in)
        '',                       // option_3 (leave empty for admin to fill in)
        '',                       // option_4 (leave empty for admin to fill in)
    ];

    // Write each row for the CSV
    fputcsv($output, $row);
} else {
    echo "Subject not found!";
    exit;
}

// Close the file pointer (output will be sent to the browser)
fclose($output);
exit;
?>
