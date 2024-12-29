<?php
include 'admin/config/dbconfig.php';
include_once 'class_user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->created = date('Y-m-d H:i:s');

    // Capture the selected course ID
    $course_id = $_POST['course'];

    // Pass the course ID to the register method
    $registerResult = $user->register($course_id);

    session_start();

    if ($registerResult['status'] === 'error') {
        $_SESSION['error'] = $registerResult['message'];
        header("Location: register.php"); // Redirect with error message
        exit();
    } else {
        $_SESSION['success'] = $registerResult['message'];
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/img/images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets_login/style.css">
    <title>Personalized Board Exam Reviewer</title>
    
       <!--   alertify -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

</head>
<body>
     <div class="container d-flex justify-content-center align-items-center min-vh-100">
       <div class="row border rounded-5 p-3 bg-white shadow box-area">
       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #82d4d9;">
           <div class="featured-image mb-3">
            <img src="./assets/img/images/logo.png" class="img-fluid" style="width: 300px; height: 300px;">
           </div>
       </div> 
       <div class="col-md-6 right-box">
          <div class="row align-items-center">
                <div class="header-text mb-2" style="text-align: center;">
                     <h2>Create Account</h2>
                </div>
            <form  action="" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" name="first_name" placeholder="First Name" required>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control form-control-lg bg-light fs-6" name="email" placeholder="Email" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control form-control-lg bg-light fs-6" name="password" placeholder="Password" required>
                </div>
                <div class="input-group mb-3">
                    <select name="course" class="form-control form-control-lg bg-light fs-6" required>
                         <!-- <option value="" disabled selected>Licensure Examination</option> -->
                         <?php
                        // Connect to the database and fetch course options
                        $query = "SELECT * FROM exam_course WHERE id = 1"; // assuming exam_course has columns `id` and `course_name`
                        $result = $db->query($query);

                        while ($row = $result->fetch_assoc()) {
                            echo "<option readonly selected value='".$row['id']."'>".$row['description']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mb-5">
                    <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Sign Up</button>
                </div>
                <div class="row" style="text-align: center; margin-top: -40px;">
                    <small>Already have an account? <a href="login.php">Sign In</a></small>
                </div>
                <!-- Add this section to the registration form in `register.php` -->
               
            </form>
          </div>
       </div> 

      </div>
    </div>

</body>
</html>
<?php

// Include Alertify messages
if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
    echo '<script>
            alertify.set("notifier", "position", "top-right");
            alertify.success("' . $_SESSION['success'] . '"); 
          </script>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
    echo '<script>
            alertify.set("notifier", "position", "top-right");
            alertify.error("' . $_SESSION['error'] . '"); 
          </script>';
    unset($_SESSION['error']);
}
?>

