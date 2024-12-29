<?php
include '../admin/config/dbconfig.php';


if (!isset($_SESSION['user_id'])) {
    session_destroy(); // Destroy the session
    header("Location: ../login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();


$user_id = $_SESSION['user_id'];


// Fetch user information
$query = "SELECT first_name, last_name, email FROM exam_user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";

    } else {
        // Prepare the update query
        if (!empty($new_password)) {
            // Update user information including new password
            $update_query = "UPDATE exam_user SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssssi", $first_name, $last_name, $email, $new_password, $user_id);
        } else {
            // Update user information without changing the password
            $update_query = "UPDATE exam_user SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
        }

        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Update Successfully!";
            header("Location: ../login.php");
            exit();
        } else {
            // Handle the error here
            $_SESSION['error'] = "Update failed. Please try again.";
        }
    }
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>


    
        <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> 

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    
    <style>
        .password-icon {
            cursor: pointer;
        }
    </style>
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
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
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
                <div class="col-md-3"></div>
                <div class="col-md-6" style="margin-top: -40px;">
                    <div class="panel">
                        <form method="POST" action="profile_update.php">
                            <div class="form-group" style="margin-top: -10px">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <label for="new_password">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="new_password" id="new_password" >
                                    <span class="input-group-addon password-icon" onclick="togglePassword('new_password', this)">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" >
                                    <span class="input-group-addon password-icon" onclick="togglePassword('confirm_password', this)">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning" style="margin-top: -10px" id="saveButton">Save</button>

                            <!-- <button type="submit" class="btn btn-warning" style="margin-top: -10px">Save</button> -->
                        </form>
                    </div>
                </div>
                <div class="col-md-3"></div>
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
function togglePassword(inputId, icon) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = icon.querySelector('i');

    // Toggle visibility of the current password input
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('glyphicon-eye-open');
        eyeIcon.classList.add('glyphicon-eye-close');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('glyphicon-eye-close');
        eyeIcon.classList.add('glyphicon-eye-open');
    }

    // Hide the other password input
    const otherInputId = inputId === 'new_password' ? 'confirm_password' : 'new_password';
    const otherInput = document.getElementById(otherInputId);
    const otherIcon = document.querySelector(`#${otherInputId} + .input-group-addon i`);

    if (otherInput.type === 'text') {
        otherInput.type = 'password';
        otherIcon.classList.remove('glyphicon-eye-close');
        otherIcon.classList.add('glyphicon-eye-open');
    }
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
<script>
    document.getElementById('saveButton').addEventListener('click', function(e) {
        e.preventDefault();  // Prevent the default form submission

        Swal.fire({
            title: 'Are you sure you want to update ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.querySelector('form').submit();
            }
        });
    });
</script>


