<?php

include "../login_action.php";

if (!isset($_SESSION['user_id'])) {
    session_destroy(); // Destroy the session
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

?>

<style>
.quote {
    display: block; 
    margin-top: 60px; 
    font-size: 1.2em; 
    color: #333; 
    font-style: italic; 
    text-align: center; 
}

.img-fluid {
    max-width: 80%;
    height: auto;
    margin-bottom: 50px /* Ensures the image maintains its aspect ratio */

}

.rounded {
    border-radius: 10px; /* Adds rounded corners */
}

.shadow {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Adds a subtle shadow for depth */
}

.text-center {
    text-align: center; /* Centers the image */
}

/* Adjust font size for username (Hello, <username>) */
.user-greeting {
    font-size: 15px;
        /* Set font size for the username */
}

/* Adjust font size for the logout link */
.log-out a {
    font-size: 16px;
    margin-right: 15px;
        /* Set font size for the logout link */
}

/* Optional: Add a hover effect to change font size of logout link */
.log-out a:hover {
    font-size: 17px; /* Increase font size on hover */
}
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../assets/img/images/favicon.png" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width= , initial-scale=1.0">

    <title>Personalized Board Exam Reviewer</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>



    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>

        <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


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
                <div class="col-md-12 text-center">
                    <img src="image/ctu.jpg" alt="Descriptive Alt Text" class="img-fluid rounded shadow ">
                </div>
                <div class="text-center">
                    <span class="quote">"Every page you study is a step toward your future; embrace the journey of learning. "<br><br>
                   ~Anonymous
                </span>
                </div>
            </div>
        </div>
    </div>

   <div class="row footer" style="margin-top: -35px;">
        <div class="col-md-4 box"></div>
        <div class="col-md-4 box">
            <a href="#" data-toggle="modal" data-target="#developers" style="color:lightyellow;"
                onmouseover="this.style('color:yellow')" target="new">
                <span style="font-size: 16px;">Personalized Board Exam Reviewer @ 2024 </span> <br><span
                    style="font-size: 13px; margin-right: -50px;">Prepared By: Collamar | Flores | Raman | Lelis</span>
            </a>
        </div>
</body>
</html>
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

