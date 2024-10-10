<?php 
session_start();
require 'vendor/autoload.php';

include("db_connect.php");
include 'includes/style.php'; 
include 'includes/head.php'; 
$error="";
$msg="";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mcc Faculty Scheduling</title>
    <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/png">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</head>

<body>

<!-- Trigger Button (outside of modal) -->
<div class="container text-center mt-5">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#passwordModal">
        Request new password
    </button>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">Retrieve | Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You forgot your password? Here you can easily retrieve a new password.</p>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
