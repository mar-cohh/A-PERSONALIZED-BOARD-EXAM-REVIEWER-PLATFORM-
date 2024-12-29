<?php

//finalcode
include_once "class/subject_class.php";
include "../login_action.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if course_id is set in the session
$course_name = '';

// If course_id is available in the session, fetch the course name
if (isset($_SESSION['course_id'])) {
    $course_id = $_SESSION['course_id'];

    // Create database connection
    $database = new Database();
    $db = $database->getConnection();

    // Query to get the course name from the database
    $query = "SELECT course FROM exam_course WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $course_id);  // Bind the course_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $course_name = $row['course'];  // Fetch the course name
    } else {
        $course_name = 'Course Not Found';  // Fallback if course not found
    }
} else {
    $course_name = 'No Course Selected';  // If no course is selected
}
//final code 
// Get subject_id from URL
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : die("Subject ID not provided.");

// Ensure a unique key for each subject's shuffled questions
$subject_key = 'shuffled_questions_' . $subject_id;

$database = new Database();
$conn = $database->getConnection();

// Add this check to reset the session if it's a retake attempt.
if (isset($_GET['retake']) && $_GET['retake'] == 'true') {
    // Clear the shuffled questions session so new questions can be selected
    unset($_SESSION[$subject_key]);
}

if (isset($_GET['cls']) && $_GET['cls'] == '0') {
    // Clear the shuffled questions session so new questions can be selected
    unset($_SESSION[$subject_key]);
}

//Clear the session variable for shuffled questions to randomize on page reload
// unset($_SESSION[$subject_key]);

$query_course = "SELECT course_id, question_limit FROM exam_subject WHERE id = ?";
$stmt_course = $conn->prepare($query_course);
$stmt_course->bind_param("i", $subject_id);
$stmt_course->execute();
$result_course = $stmt_course->get_result();
$course = $result_course->fetch_assoc();
$course_id = $course['course_id'];
$question_limit = $course['question_limit'];

// Fetch all questions for the subject
$query_questions = "SELECT * FROM exam_question WHERE subject_id = ? /*ORDER BY RAND() LIMIT 10*/";
$stmt_questions = $conn->prepare($query_questions);
$stmt_questions->bind_param("i", $subject_id);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();
$questions = $result_questions->fetch_all(MYSQLI_ASSOC);

// If no questions found, handle the error
if (empty($questions)) {
    $_SESSION['error'] = "No questions found for this subject.";
    header("Location: quiz.php");
    exit(); 
}

// // Shuffle questions and Limit to number of questions 
if (!isset($_SESSION[$subject_key])) {
    shuffle($questions);
    $_SESSION[$subject_key] = array_slice($questions, 0, $question_limit); // Limit to number of questions
}

// Get the question number from the URL
$question_number = isset($_GET['n']) ? intval($_GET['n']) : 1; // Defaults to first question

// Ensure the question number does not exceed the number of questions in the session
$question_number = min($question_number, count($_SESSION[$subject_key]));

// Fetch the current question
$current_question = $_SESSION[$subject_key][$question_number - 1];

// Fetch the options for the current question
$query_options = "SELECT * FROM exam_option WHERE question_id = ? ORDER BY RAND()";
$stmt_options = $conn->prepare($query_options);
$stmt_options->bind_param("i", $current_question['id']);
$stmt_options->execute();
$result_options = $stmt_options->get_result();
$options = $result_options->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    foreach ($_POST['answers'] as $question_id => $answer) {
        $_SESSION['answers'][$question_id] = $answer; // Save answer in session
    }
}

?>
<style>
.user-greeting {
    font-size: 15px;
}
.log-out a {
    font-size: 16px;
    margin-right: 15px;
}
.log-out a:hover {
    font-size: 17px; /* Increase font size on hover */
}
.funkyradio-success input:checked + label:before {
        background-color: #5cb85c;
        border-color: #5cb85c;
    }
</style>

<!-- final code -->

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../assets/img/images/favicon.png" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personalized Board Exam Reviewer</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>

       
        <!-- SweetAlert CSS -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> 

<!-- Include SweetAlert CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"> -->

<!-- Include SweetAlert JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!--   alertify -->
     <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>


</head>

<body>
    <div class="header" style="background-color: skyblue;">
        <div class="row">
            <div class="col-lg-6" style="margin-top: 20px;">
                <span class="logo" style="font-size: 20px;">Personalized Board Exam Reviewer | <?php echo htmlspecialchars($course_name); ?></span>
            </div>
            <div class="col-md-4 col-md-offset-2" style="margin-top: 9px;">
                <?php
                    include "class/alert.php";
                    // Check if the user is logged in
                    if (isset($_SESSION['user_name'])) {
                        echo '<span class="pull-right top title1">';
                        echo '<span class="user-greeting" style="color:white;">';
                        echo '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Hello, ' . htmlspecialchars($_SESSION['user_name']) . ' </span>';
                        echo '<span class="log-out" style="color:lightyellow;">
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:void(0);" style="color:lightyellow" onclick="confirmLogout()">
                            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Logout
                        </a>
                    </span>';
                        echo '</span>';
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="bg">
        <nav class="navbar navbar-default title1">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand"
                        href="#"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="dashboard.php" style="font-size: 15px;">
                                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li><a href="quiz.php" style="font-size: 15px;">
                                <span class="fi fi-ss-quiz-alt" aria-hidden="true"></span>&nbsp;Quiz
                            </a>
                        </li>
                        <li><a href="profile.php" style="font-size: 15px;">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;Profile
                            </a>
                        </li>
                        <li><a href="studentperformance.php" style="font-size: 15px;">
                            <span class="fi fi-ss-dashboard-monitor" aria-hidden="true"></span>&nbsp;Student Performance
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
               
                    <div class="panel" style="margin-right:5%;margin-left:5%;border-radius:10px;margin-top: -10px">
                        <div style="text-align: left; font-size: 12px; font-weight: bold; margin-left: 940px; color: gray; margin-bottom: 12px;">
                            <?php echo $question_number . " / " . count($_SESSION[$subject_key]); ?>
                        </div>
                        <pre style="background-color:white"><div style="font-size:15px;font-weight:bold;font-family:calibri;margin:5px"> <?php echo $question_number . ". " . $current_question['question']; ?></div></pre>
                        <form id="qform" action="submit_answer.php" method="POST" class="form-horizontal">
                            <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">

                            <!-- Question Display -->
                            <div class="question">
                                <div class="funkyradio">
                                    <?php foreach ($options as $option): ?>
                                        <div class="funkyradio-success">
                                            <input type="radio" id="option<?php echo $option['id']; ?>" name="answers[<?php echo $current_question['id']; ?>]" value="<?php echo $option['option']; ?>"
                                                onclick="storeAnswer(<?php echo $current_question['id']; ?>, '<?php echo $option['option']; ?>')"
                                                <?php echo (isset($_SESSION['answers'][$current_question['id']]) && $_SESSION['answers'][$current_question['id']] == $option['title']) ? 'checked' : ''; ?> />
                                            <label for="option<?php echo $option['id']; ?>" style="width:50%">
                                                <div style="color:black;font-size:12px;word-wrap:break-word">
                                                    &nbsp;&nbsp;
                                                    <?php echo $option['title']; ?>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <div class="navigation">
                                <?php if ($question_number > 1): ?>
                                    <a href="take_quiz.php?subject_id=<?php echo $subject_id; ?>&n=<?php echo ($question_number - 1); ?>"
                                        class="btn btn-primary" style="height:30px" onclick="previousQuestion(<?php echo $current_question['id']; ?>)">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true" style="font-size:12px"></span> Previous
                                    </a>
                                <?php endif; ?>

                                <?php if ($question_number < $question_limit): ?>
                                    <a href="take_quiz.php?subject_id=<?php echo $subject_id; ?>&n=<?php echo ($question_number + 1); ?>"
                                        class="btn btn-info" style="height:30px" onclick="nextQuestion(<?php echo $current_question['id']; ?>)">
                                        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true" style="font-size:12px"></span> Next
                                    </a>
                                <?php else: ?>
                                    <!-- Submit Button for the last question -->
                                    <button type="submit" class="btn btn-default" id="sbutton" style="height:30px">
                                        <span class="glyphicon glyphicon-ok" style="font-size:12px" aria-hidden="true"></span>
                                        <font style="font-size:12px;font-weight:bold"> Submit Answers</font>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="row footer" style="margin-top: 40px;">
            <div class="col-md-4 box"></div>
            <div class="col-md-4 box">
                <a href="#" data-toggle="modal" data-target="#developers" style="color:lightyellow;"
                    onmouseover="this.style('color:yellow')" target="new">
                    <span style="font-size: 12px;">Personalized Board Exam Reviewer @ 2024 </span> <br><span
                        style="font-size: 9px; margin-right: -50px;">Prepared By: Collamar | Flores | Raman |
                        Lelis</span>
                </a>
            </div>
        </div>
    
</body>

</html>


<script>
    // Function to store answers in localStorage
    function storeAnswer(questionId, optionId) {
        let storedAnswers = JSON.parse(localStorage.getItem('quiz_answers')) || {};
        storedAnswers[questionId] = optionId; // Store the user's selected answer
        localStorage.setItem('quiz_answers', JSON.stringify(storedAnswers));
    }

    // Function to restore answers from localStorage
    function restoreAnswers() {
        let storedAnswers = JSON.parse(localStorage.getItem('quiz_answers')) || {};
        for (let questionId in storedAnswers) {
            let optionId = storedAnswers[questionId];
            let optionElement = document.querySelector(`input[name="answers[${questionId}]"][value="${optionId}"]`);
            if (optionElement) {
                optionElement.checked = true; // Check the stored answer
            }
        }
    }

    // Before submitting, add all stored answers into hidden fields
document.getElementById('qform').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Get the total number of questions
    const totalQuestions = <?php echo count($_SESSION[$subject_key]); ?>; // Use PHP to get the total questions
    const answers = JSON.parse(localStorage.getItem('quiz_answers')) || {};

    // Check if all questions are answered
        if (Object.keys(answers).length === totalQuestions) {
            // Create hidden input fields for each answer in the storedAnswers
            for (let questionId in answers) {
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'answers[' + questionId + ']';
                hiddenInput.value = answers[questionId];
                this.appendChild(hiddenInput); // Add hidden inputs to the form
            }

            // Clear localStorage after successful submission
            localStorage.removeItem('quiz_answers');

            // Now submit the form
            this.submit(); // Submit the form
        } else {
            // Show an error message to the user
            swal({
            title: "FAILED TO SUBMIT",
            text: "You Must Answer All Questions First!",
            icon: "error", // Use the error icon
            button: "Okay", // Custom button text
        });
        }
    });

    // Call restoreAnswers when the page loads
    window.onload = function() {
        restoreAnswers();
    };

    // Example of how to handle Next and Previous buttons
    function nextQuestion(currentQuestionId) {
        // Logic to navigate to the next question
        // For example, you might hide the current question and show the next one
        // Make sure to call restoreAnswers() if needed
        restoreAnswers();
    }

    function previousQuestion(currentQuestionId) {
        // Logic to navigate to the previous question
        // For example, you might hide the current question and show the previous one
        // Make sure to call restoreAnswers() if needed
        restoreAnswers();
    }
</script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            text: "You will be logged out from your account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout.php to perform the logout action
                window.location.href = '../logout.php';
            }
        });
    }
</script>



