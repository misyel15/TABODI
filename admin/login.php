<?php
session_start();
include 'db_connect.php';

// Define max attempts
define('MAX_ATTEMPTS', 3);
define('LOCK_TIME', 5); // seconds

// Check if the user has reached max attempts
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
    // Check if the lock time has passed
    $time_diff = time() - $_SESSION['last_attempt_time'];
    if ($time_diff < LOCK_TIME) {
        echo "You have reached the maximum number of login attempts. Please try again in " . (LOCK_TIME - $time_diff) . " seconds.";
        exit;
    } else {
        // Reset failed attempts after lock time
        $_SESSION['login_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $course = htmlspecialchars(trim($_POST['course']));
    $captcha_response = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $secret_key = '6LckZG8qAAAAAKts8tP7BtqhVOio5v5YVAnjJQlM';
    $captcha_verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    $captcha_response_data = json_decode($captcha_verify);

    if (!$captcha_response_data->success) {
        echo 5; // CAPTCHA verification failed
        exit;
    }

    // Prepare and execute the login query
    $stmt = $conn->prepare("SELECT id, name, username, course, dept_id, type FROM users WHERE username = ? AND password = ?");
    $hashed_password = md5($password);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        if ($user_data['course'] === $course) {
            // Reset login attempts on successful login
            $_SESSION['login_attempts'] = 0;

            // Store user information in session
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['dept_id'] = $user_data['dept_id'];
            $_SESSION['username'] = htmlspecialchars($user_data['username']);
            $_SESSION['name'] = htmlspecialchars($user_data['name']);
            $_SESSION['login_type'] = $user_data['type'];

            if ($_SESSION['login_type'] != 1) {
                session_unset();
                echo 2; // User is not allowed
            } else {
                echo 1; // Successful login
            }
        } else {
            echo 4; // Course mismatch
        }
    } else {
        // Increase login attempt count
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;

        // Track the last attempt time
        $_SESSION['last_attempt_time'] = time();

        if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
            echo "You have reached the maximum number of login attempts. Please try again in 5 seconds.";
        } else {
            echo 3; // Invalid username/password
        }
    }
    exit;
}


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
    
    <!-- External CSS and Scripts -->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" media="all">

    <!-- Include reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        .form-group .g-recaptcha {
            transform: scale(0.85); /* Adjust scale to fit */
            transform-origin: 0 0; /* Set origin to top-left */
        }

        @media (max-width: 600px) {
            .form-group .g-recaptcha {
                transform: scale(0.75); /* Smaller for smaller screens */
                transform-origin: 0 0;
            }
        }
    </style>

</head>

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
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control" name="course" required>
                                        <option value="" disabled selected>Select Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSBA">BSBA</option>
                                        <option value="BSHM">BSHM</option>
                                        <option value="BSED">BSED</option>
                                    </select>
                                </div>

                                <!-- reCAPTCHA Widget -->
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LckZG8qAAAAAOaB5IlBAIcLTOiHW0jhSQeE0qOY"></div>
                                </div>

                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Login</button>
                                <a href="forgot.php" class="forgot-password-btn">Forgot Password?</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery JS -->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <script src="vendor/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'login.php', 
                    data: formData,
                    success: function(resp) {
                        if (resp == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful',
                                text: 'Redirecting...',
                                showConfirmButton: false
                            }).then(() => {
                                location.href = 'home.php'; 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: 'Please check your credentials or reCAPTCHA.'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error processing your request. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
