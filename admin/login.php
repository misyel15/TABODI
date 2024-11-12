<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input to prevent XSS attacks
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $course = htmlspecialchars(trim($_POST['course']));
    $captcha_response = $_POST['h-captcha-response']; // Get the hCaptcha response

    // Verify hCaptcha
    $secret_key = 'ES_69e..'; // Replace with your secret key
    $captcha_verify = file_get_contents("https://hcaptcha.com/siteverify?secret=$secret_key&response=$captcha_response");
    $captcha_response_data = json_decode($captcha_verify);

    if (!$captcha_response_data->success) {
        echo 5; // CAPTCHA verification failed
        exit;
    }

    // Prepare and execute the login query
    $stmt = $conn->prepare("
        SELECT id, name, username, course, dept_id, type 
        FROM users 
        WHERE username = ? 
        AND password = ?
    ");
    $hashed_password = md5($password); // Use md5 or a stronger hashing algorithm
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        if ($user_data['course'] === $course) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['dept_id'] = $user_data['dept_id'];
            $_SESSION['username'] = htmlspecialchars($user_data['username']);
            $_SESSION['name'] = htmlspecialchars($user_data['name']);
            $_SESSION['login_type'] = $user_data['type'];

            if ($_SESSION['login_type'] != 1) {
                session_unset();
                echo 2;
            } else {
                echo 1;
            }
        } else {
            echo 4;
        }
    } else {
        echo 3;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="School Faculty Scheduling System">
    <meta name="author" content="Your Name">
    <meta name="keywords" content="School, Faculty, Scheduling, System">

    <title>Login</title>
    <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/jpg">
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</head>

<style>
    body.animsition {
        background-color: #f0f2f5;
    }

    .page-wrapper {
        background-color: #eae6f5;
        padding-top: 50px;
    }

    .login-wrap {
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .login-content {
        background-color: #ffffff;
    }

    .password-container {
        position: relative;
        width: 100%;
    }

    .au-input {
        width: 100%;
        padding-right: 40px;
    }

    .eye-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .form-group .h-captcha {
        transform: scale(0.85);
        transform-origin: 0 0;
    }

    @media (max-width: 600px) {
        .form-group .h-captcha {
            transform: scale(0.75);
            transform-origin: 0 0;
        }
    }
</style>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge4">
            <div class="container">
                <div class="login-wrap" style="margin-top:0%; max-width: 450px; padding: 20px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="assets/uploads/mcclogo.jpg" style="width:150px; height:90px;" alt="CoolAdmin">
                            </a>
                            <h3> Welcome Admin</h3>
                        </div>
                        <div class="login-form">
                            <form id="login-form">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="au-input au-input--full" type="email" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="password-container">
                                        <input class="au-input au-input--full" type="password" id="password" name="password" placeholder="Password" required>
                                        <i class="fas fa-eye-slash eye-icon" id="togglePassword"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Course</label>
                                    <div class="col-sm-13">
                                        <select class="form-control" name="course" id="course" required>
                                            <option value="" disabled selected>Select Course</option>
                                            <option value="BSIT">BSIT</option>
                                            <option value="BSBA">BSBA</option>
                                            <option value="BSHM">BSHM</option>
                                            <option value="BSED">BSED</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="h-captcha" data-sitekey="3a6addfe-f2ec-40b2-9bf6-51b24478fcdc"></div>
                                </div>
                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Login</button>
                                <a href="https://mccfacultyscheduling.com/login.php" class="au-btn au-btn--block au-btn--green m-b-20" style="text-align:center;">Home</a>
                                <center>
                                    <a href="forgot.php" class="forgot-password-btn">Forgot Password?</a>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <script src="vendor/slick/slick.min.js"></script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        $('#login-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            $.ajax({
                url: 'login.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response == 1) {
                        window.location.href = 'dashboard.php';
                    } else if (response == 2) {
                        Swal.fire('Error', 'Invalid user type', 'error');
                    } else if (response == 3) {
                        Swal.fire('Error', 'Invalid username or password', 'error');
                    } else if (response == 4) {
                        Swal.fire('Error', 'Invalid course selection', 'error');
                    } else if (response == 5) {
                        Swal.fire('Error', 'CAPTCHA verification failed', 'error');
                    } else {
                        Swal.fire('Error', 'An unknown error occurred', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to communicate with the server', 'error');
                }
            });
        });
    </script>
</body>
</html>
