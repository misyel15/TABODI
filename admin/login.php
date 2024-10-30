<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input to prevent XSS attacks
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $course = htmlspecialchars(trim($_POST['course']));
    $captcha_response = $_POST['g-recaptcha-response']; // Get the reCAPTCHA response

    // Verify reCAPTCHA
    $secret_key = '6LckZG8qAAAAAKts8tP7BtqhVOio5v5YVAnjJQlM'; // Replace with your secret key
    $captcha_verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
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

        // Check if the course matches
        if ($user_data['course'] === $course) {
            // Store only necessary user information in the session
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['dept_id'] = $user_data['dept_id'];
            $_SESSION['username'] = htmlspecialchars($user_data['username']); // Prevent XSS when outputting username
            $_SESSION['name'] = htmlspecialchars($user_data['name']); // Prevent XSS when outputting name
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
        echo 3; // Invalid username/password
    }
    exit;
}
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
    
    <!-- Include reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<style>
    body.animsition {
        background-color: #f0f2f5; /* Light gray background color */
    }

    .page-wrapper {
        background-color: #eae6f5; /* White background for the page wrapper */
        padding-top: 50px; /* Add some spacing at the top */
    }

    .login-wrap {
        background-color: #ffffff; /* White background for the login card */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for the card */
    }

    .login-content {
        background-color: #ffffff; /* Background for content */
    }

    .password-container {
        position: relative;
        width: 100%;
    }

    .au-input {
        width: 100%;
        padding-right: 40px; /* Adjust to make space for the icon */
    }

    .eye-icon {
        position: absolute;
        right: 10px; /* Adjust according to your design */
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
 
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

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge4">
            <div class="container">
                <div class="login-wrap" style="margin-top:5%; max-width: 450px; padding: 20px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
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
                                <!-- Course Field -->
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
 
                                <!-- reCAPTCHA Widget -->
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LckZG8qAAAAAOaB5IlBAIcLTOiHW0jhSQeE0qOY"></div> <!-- Adjust width and height as needed -->
                                </div>

                                <div class="login-checkbox">
                                    <!-- Terms Agreement Checkbox -->
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="terms" required> I agree the <a href="terms.html" target="_blank">terms and conditions</a><br>
                                            <a href="forgot.php" class="forgot-password-btn">Forgot Password?</a>
                                        </label>
                                    </div>
                                   
                                </div>

                            

                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Login</button>
                                <a href="https://mccfacultyscheduling.com/login.php" class="au-btn au-btn--block au-btn--green m-b-20" style="text-align:center;">Home</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js"></script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });

        // Handle form submission
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $('#login-form button[type="submit"]').attr('disabled', 'disabled').html('Logging in...');

                $.ajax({
                    type: 'POST',
                    url: 'login.php', // Update to the correct URL of your PHP script
                    data: formData,
                    success: function(resp) {
                        if (resp == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful',
                                text: 'Redirecting...',
                                showConfirmButton: true
                            }).then(() => {
                                location.href = 'home.php'; // Redirect to the homepage
                            });
                        } else if (resp == 2) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Access Denied',
                                text: 'You do not have permission to access this area.'
                            });
                            $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                        } else if (resp == 4) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Course Mismatch',
                                text: 'The selected course does not match your account.'
                            });
                            $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                        } else if (resp == 5) {
                            Swal.fire({
                                icon: 'error',
                                title: 'CAPTCHA Failed',
                                text: 'Please complete the CAPTCHA.'
                            });
                            $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: 'Username or password is incorrect.'
                            });
                            $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                        }
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
