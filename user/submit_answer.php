<?php
include '../admin/config/dbconfig.php';

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'];
$subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : die("Subject ID missing.");

// Get all answers from the form
$answers = isset($_POST['answers']) ? $_POST['answers'] : [];

// Ensure course_id is set in POST data
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : die("Course ID missing.");

$subject_key = 'shuffled_questions_' . $subject_id;
$shuffled_questions = isset($_SESSION[$subject_key]) ? $_SESSION[$subject_key] : [];

// If no shuffled questions exist, show an error and prevent submission
if (empty($shuffled_questions)) {
    $_SESSION['error'] = "No questions found in the session!";
    header("Location: take_quiz.php?subject_id=" . $subject_id);
    exit();
}

// Get only the IDs of the shuffled questions for validation
$shuffled_question_ids = array_map(function($question) {
    return $question['id'];
}, $shuffled_questions);

// Check if all shuffled questions have been answered
$unanswered_questions = array_diff($shuffled_question_ids, array_keys($answers));

if (!empty($unanswered_questions)) {
    $_SESSION['error'] = "You must answer all questions!";
    header("Location: take_quiz.php?subject_id=" . $subject_id);  // Redirect back to quiz page
    exit();
}

// Insert user's answers into the database
$query_insert = "INSERT INTO exam_answer_option (user_id, question_id, subject_id, course_id, user_answer_option) 
                 VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($query_insert);

// Loop through each answer and insert it into the database
foreach ($answers as $question_id => $user_answer_option) {
    $stmt_insert->bind_param("iiisi", $user_id, $question_id, $subject_id, $course_id, $user_answer_option);
    $stmt_insert->execute();
}

// Calculate score
$score = 0;
$total_questions = count($answers);
$correct_answers = [];

// Fetch the correct answers from the database for only the shuffled questions
$query_answers = "SELECT id, answer FROM exam_question WHERE subject_id = ? AND id IN (" . implode(',', $shuffled_question_ids) . ")";
$stmt_answers = $conn->prepare($query_answers);
$stmt_answers->bind_param("i", $subject_id);
$stmt_answers->execute();
$result_answers = $stmt_answers->get_result();

// Store correct answers
while ($row = $result_answers->fetch_assoc()) {
    $correct_answers[$row['id']] = $row['answer'];
}

// Calculate the score based on user's answers
foreach ($answers as $question_id => $user_answer) {
    if (isset($correct_answers[$question_id]) && $user_answer == $correct_answers[$question_id]) {
        $score++;
    }
}

// Store the score in session
$_SESSION['score'] = $score;
$_SESSION['total_questions'] = $total_questions;
$_SESSION['course_id'] = $course_id;

// Insert the result into exam_result table
$query_result_insert = "INSERT INTO exam_result (user_id, subject_id, course_id, score, total_questions) 
                        VALUES (?, ?, ?, ?, ?)";
$stmt_result_insert = $conn->prepare($query_result_insert);
$stmt_result_insert->bind_param("iiiii", $user_id, $subject_id, $course_id, $score, $total_questions);
$stmt_result_insert->execute();

$_SESSION['success'] = "Successfully Submitted";
header("Location: quiz_result.php");
exit();
?>
