<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input to prevent XSS attacks
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare and execute the login query
    $stmt = $conn->prepare("
        SELECT id, name, username, dept_id, type FROM users 
        WHERE username = ? 
        AND password = ?
    ");
    $hashed_password = md5($password); // Use md5 or a stronger hashing algorithm
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

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
</head>

<style>
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
</style>
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
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
                                    <div class="col-sm-13">
                                        <label for="course" class="control-label">Department</label>
                                        <select class="form-control" name="course" id="course" required>
                                            <option value="0" disabled selected>Select Course</option>
                                            <?php 
                                            $sql = "SELECT * FROM users";
                                            $query = $conn->query($sql);
                                            while($row= $query->fetch_array()):
                                                $course = $row['course'];
                                            ?>
                                            <option value="<?php echo  $course ?>"><?php echo ucwords($course) ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="home.php">Forgot Password?</a>
                                    </label>
                                </div>
                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Login</button>
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

    <!-- Custom JS for Password Toggle and Form Handling -->
    <script>
    $(document).ready(function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the eye icon classes
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        $('#login-form').submit(function(e) {
            e.preventDefault();
            $('#login-form button[type="submit"]').attr('disabled', true).html('Logging in...');
            if ($(this).find('.alert-danger').length > 0)
                $(this).find('.alert-danger').remove();
            
            $.ajax({
                url: '', // No URL is needed here; we are submitting to the same page
                method: 'POST',
                data: $(this).serialize(),
                error: function(err) {
                    console.log(err);
                    // Display SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later.'
                    });
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                },
                success: function(resp) {
                    if (resp == 1) {
                        // Display SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: 'Redirecting...',
                            showConfirmButton: true
                        }).then(() => {
                            location.href = 'home.php'; // Redirect to the homepage
                        });
                    } else if (resp == 2) {
                        // Display SweetAlert for access denied
                        Swal.fire({
                            icon: 'error',
                            title: 'Access Denied',
                            text: 'You do not have permission to access this area.'
                        });
                        $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                    } else {
                        // Display SweetAlert for login failure
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: 'Username or password is incorrect.'
                        });
                        $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                    }
                }
            });
        });
    });
    </script>

    <!-- Anti-inspect JavaScript -->
    <script>
    // Disable right-click
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    }, false);

    // Disable F12 (Inspect Element) and Ctrl+Shift+I
    document.addEventListener('keydown', function (e) {
        // F12
        if (e.keyCode === 123) {
            e.preventDefault();
        }
        // Ctrl + Shift + I
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
        }
    }, false);

    // Disable Ctrl+U (View Source)
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
        }
    }, false);
    </script>
</body>
</html>
