<?php
session_start();
include 'db_connect.php';

// Define max attempts and lock time
define('MAX_ATTEMPTS', 3);
define('LOCK_TIME', 5); // seconds

// Initialize or check existing session variables
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is locked out
    if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS && (time() - $_SESSION['last_attempt_time']) < LOCK_TIME) {
        $remaining_lock_time = LOCK_TIME - (time() - $_SESSION['last_attempt_time']);
        echo json_encode(['status' => 6, 'remaining_attempts' => 0, 'remaining_lock_time' => $remaining_lock_time]);
        exit;
    }

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
        echo json_encode(['status' => 5, 'remaining_attempts' => MAX_ATTEMPTS - $_SESSION['login_attempts']]);
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
                echo json_encode(['status' => 2, 'remaining_attempts' => MAX_ATTEMPTS - $_SESSION['login_attempts']]);
            } else {
                echo json_encode(['status' => 1, 'remaining_attempts' => MAX_ATTEMPTS - $_SESSION['login_attempts']]);
            }
        } else {
            echo json_encode(['status' => 4, 'remaining_attempts' => MAX_ATTEMPTS - $_SESSION['login_attempts']]);
        }
    } else {
        // Increase login attempt count
        $_SESSION['login_attempts']++;

        // Track the last attempt time
        $_SESSION['last_attempt_time'] = time();

        if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
            echo json_encode(['status' => 6, 'remaining_attempts' => 0]); // Max attempts reached
        } else {
            echo json_encode(['status' => 3, 'remaining_attempts' => MAX_ATTEMPTS - $_SESSION['login_attempts']]);
        }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
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
                                    <div class="g-recaptcha" data-sitekey="6LckZG8qAAAAAOaB5IlBAIcLTOiHW0jhSQeE0qOY"></div>
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
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $('#login-form button[type="submit"]').attr('disabled', 'disabled').html('Logging in...');

                $.ajax({
                    type: 'POST',
                    url: '', // Current page
                    data: formData,
                    success: function(resp) {
                        const data = JSON.parse(resp);

                        if (data.status == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful',
                                text: 'Redirecting...',
                                showConfirmButton: true
                            }).then(() => {
                                location.href = 'home.php'; // Redirect to the homepage
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Access Denied',
                                text: 'You do not have permission to access this area.'
                            });
                        } else if (data.status == 3) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Credentials',
                                text: 'You have ' + data.remaining_attempts + ' attempts remaining.'
                            });
                        } else if (data.status == 4) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Course Mismatch',
                                text: 'The selected course does not match your account.'
                            });
                        } else if (data.status == 5) {
                            Swal.fire({
                                icon: 'error',
                                title: 'CAPTCHA Failed',
                                text: 'Please complete the CAPTCHA.'
                            });
                        } else if (data.status == 6) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Max Attempts Reached',
                                text: 'You have reached the maximum number of login attempts. Please try again in ' + data.remaining_lock_time + ' seconds.'
                            });
                        }
                        $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error processing your request. Please try again.'
                        });
                        $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                    }
                });
            });
        });
    </script>
</body>
</html>
