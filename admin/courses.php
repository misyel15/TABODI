<?php 
session_start();
include("db_connect.php");
include 'includes/style.php'; 
include 'includes/head.php'; 

$error = "";
$msg = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php'; // Adjust the path as needed
require 'phpmailer/src/PHPMailer.php'; // Adjust the path as needed
require 'phpmailer/src/SMTP.php'; // Adjust the path as needed

function sendEmail($email, $reset_token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com'; // Your SMTP username
        $mail->Password = 'your_app_password';    // Your SMTP password (use an App Password if using Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('mccschedsystem@gmail.com', 'MCC SCHED SYSTEM ADMIN');
        $mail->addAddress($email);

        // Reset link
        $resetLink = 'https://mccfacultyscheduling.com/admin/reset_password.php?email=' . urlencode($email) . '&token=' . $reset_token;

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your MCC SCHED-SYSTEM Account Password';
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

        if (mysqli_query($conn, $update) && sendEmail($email, $reset_token)) {
            echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "Success!",
                            text: "Reset password link sent to your email.",
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="School Faculty Scheduling System">
    <meta name="author" content="Your Name">
    <meta name="keywords" content="School, Faculty, Scheduling, System">

    <title>Password Reset</title>
    <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/jpg">
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <style>
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
    </style>
</head>
<body>
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a class="h1"><b>Retrieve</b> | Account</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
            <form method="POST" action="">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <button type="submit" name="reset" class="btn btn-primary btn-block">Request new password</button>
                    </div>
                    <div class="col-4">
                        <a href="login.php" class="btn btn-secondary btn-block">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
</body>
</html>
