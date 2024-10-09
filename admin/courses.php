<?php 
session_start();
include("db_connect.php");
include 'includes/style.php'; 
include 'includes/head.php'; 
$error="";
$msg="";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendemail($email, $reset_token)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'zeninmacky05@gmail.com'; // SMTP username
        $mail->Password = 'frut mage zsxu mzsd';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('mccschedsystem@gmail.com', 'MCC SCHED SYSTEM ADMIN');
        $mail->addAddress($email);

        //Reset link
        $resetLink = 'http://localhost/SCHED4/admin/reset_password.php?email=' . urlencode($email) . '&token=' . $reset_token;

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Here is your link to Reset the password of your MCC SCHED-SYSTEM Account';
        $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                }
                .container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .button {
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 4px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <p>Hello,</p>
                <p>We received a request to reset your password. Click the button below to reset it:</p>
                <p><a href='" . $resetLink . "' class='button'>Reset Password</a></p>
                <p>If you did not request a password reset, please ignore this email.</p>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);

    if ($result && mysqli_num_rows($result) == 1) {
        $reset_token = bin2hex(random_bytes(10));
        $update = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";

        if (mysqli_query($conn, $update) && sendemail($email, $reset_token)) {
            echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "Success!",
                            text: "Reset password link sent to your email",
                            icon: "success"
                        });
                    };
                  </script>';
        } else {
            echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to send reset password link. Please try again later.",
                            icon: "error"
                        });
                    };
                  </script>';
        }
    } else {
        echo '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Error!",
                        text: "No account associated with this email. Please check your email.",
                        icon: "error"
                    });
                };
              </script>';
    }
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="School Faculty Scheduling System">
    <meta name="author" content="Your Name">
    <meta name="keywords" content="School, Faculty, Scheduling, System">

    <!-- Title Page-->
    <title>Login</title>
    <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/jpg">
    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <style>
        /* Main layout adjustments */
        body {
            background-color: #f4f4f4;
            font-family: 'Source Sans Pro', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: lightgray;
            color: black;
            text-align: center;
            padding: 1.5rem;
            border-radius: 20px 20px 0 0;
        }
        .h1 {
            font-size: 1.75rem;
            font-weight: bold;
        }
        .card-body {
            padding: 2rem;
        }
        .input-group-text {
            background-color: #f4f4f4;
        }
        .btn {
            background-color: #007bff;
            border: none;
        }
        /* Logo styling */
        #logo-img {
            width: 5em;
            height: 5em;
            object-fit: cover;
            object-position: center center;
            border-radius: 50%;
        }
        /* Make the layout responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }
            .h1 {
                font-size: 1.5rem;
            }
            #logo-img {
                width: 4em;
                height: 4em;
            }
            .btn {
                padding: 0.75rem 1rem;
            }
            .login-box {
                margin: 10px;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <img src="assets/uploads/back.png" alt="System Logo" class="img-thumbnail rounded-circle" id="logo-img">
            <a class="h1"><b>Retrieve</b>|Account</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="reset" class="btn btn-primary btn-block">Request new password</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="index.php">Login</a>
            </p>
        </div>
    </div>
</div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS -->
    <script src="vendor/slick/slick.min.js"></script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js"></script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

    <!-- Main JS-->
    <script src="js/main.js"></script>


</html>
