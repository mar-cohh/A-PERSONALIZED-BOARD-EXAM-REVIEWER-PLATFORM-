<?php
    include ("login_action_admin.php");

    if(isset($_SESSION['admin_id'])) {
        header('location: index.php');
    }
    if(isset($_SESSION['user_id'])) {
        header('location: ../user/dashboard.php');
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets_login/style.css">

       <!--   alertify -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <title>Personalized Board Exam Reviewer</title>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: #82d4d9;">
                <div class="featured-image mb-3">
                    <img src="../assets/img/images/logo.png" class="img-fluid" style="width: 300px; height: 300px;">
                </div>
            </div>
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4" style="text-align: center;">
                        <h2>ADMIN</h2>
                    </div>
                    <form action="login_action_admin.php" method="post">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-lg bg-light fs-6" name="email"
                                placeholder="Email" required>
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="input-group mb-2">

                        </div>
                        <div class="input-group mb-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                        </div>
                    </form>
                    <div class="row" style="text-align: center; margin-top: -40px;">
                        <small>Don't have account? <a href="register.php">Sign Up</a></small>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>

<?php
session_start();

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
