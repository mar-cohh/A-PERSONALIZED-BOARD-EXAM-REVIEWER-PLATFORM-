<?php
include "../login_action.php";
include_once "class/subject_class.php";

if (!isset($_SESSION['user_id'])) {
    session_destroy(); // Destroy the session
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Get subject_id from URL
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : die("Subject ID not provided.");

// Fetch user's answers for the specified subject
$query = "SELECT eqa.question_id, eqa.user_answer_option, eq.answer 
          FROM exam_answer_option eqa 
          JOIN exam_question eq ON eqa.question_id = eq.id 
          WHERE eqa.user_id = ? AND eqa.subject_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION['user_id'], $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Create CSV file
    $filename = "quiz_results_subject_" . $subject_id . "_user_" . $_SESSION['user_id'] . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    $output = fopen("php://output", "w");
    
    // Add CSV headers
    fputcsv($output, ["#", "Question", "User Answer", "Correct Answer", "Options"]);

    $count = 1;
    
    while ($row = $result->fetch_assoc()) {
        // Fetch the question
        $question_query = "SELECT question FROM exam_question WHERE id = ?";
        $question_stmt = $conn->prepare($question_query);
        $question_stmt->bind_param("i", $row['question_id']);
        $question_stmt->execute();
        $question_result = $question_stmt->get_result();
        $question_data = $question_result->fetch_assoc();

        // Fetch the options for the question
        $options_query = "SELECT option, title FROM exam_option WHERE question_id = ?";
        $options_stmt = $conn->prepare($options_query);
        $options_stmt->bind_param("i", $row['question_id']);
        $options_stmt->execute();
        $options_result = $options_stmt->get_result();

        // Prepare the options for CSV and create a mapping
        $options = [];
        $option_map = [];
        $letters = ['A', 'B', 'C', 'D']; // Assuming a maximum of 4 options

        while ($option_row = $options_result->fetch_assoc()) {
            $options[] = $option_row['title'];
            $option_map[$option_row['option']] = $letters[count($option_map)]; // Map ID to A, B, C, D
        }

        // Format options as a comma-separated string
        $options_list = implode(", ", $options);

        // Prepare the user answer and correct answer as letters
        $user_answer_letter = isset($option_map[$row['user_answer_option']]) ? $option_map[$row['user_answer_option']] : "N/A";
        $correct_answer_letter = isset($option_map[$row['answer']]) ? $option_map[$row['answer']] : "N/A";

        // Prepare the CSV row
        fputcsv($output, [
            $count++,
            $question_data['question'],
            $user_answer_letter, // User's answer as letter
            $correct_answer_letter, // Correct answer as letter
            $options_list // Options as a string
        ]);
    }

    fclose($output);
    exit();
} else {
    echo "No results found for the specified subject.";
}
?>
