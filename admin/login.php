<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) { // Check if the request is for forgot password
        $email = htmlspecialchars(trim($_POST['email']));

        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a unique reset token (you should store this in your database)
            $token = bin2hex(random_bytes(50));
            // Store the token and its expiration in the database (not shown here)
            // Send email with reset link (using PHPMailer or similar)
            $resetLink = "https://yourdomain.com/reset_password.php?token=" . $token;

            // Your email sending logic goes here...

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    } else { // Handle login request
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
                                        <a href="#" class="forgot-password-btn" data-toggle="modal" data-target="#forgotPasswordModal">Forgot Password?</a>
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

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgot-password-form">
                        <div class="form-group">
                            <label for="email">Enter your email address:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="course">Select Course</label>
                            <select class="form-control" name="course" id="course" required>
                                <option value="0" disabled selected>Select Course</option>
                                <?php 
                                $sql = "SELECT * FROM users";
                                $query = $conn->query($sql);
                                while($row= $query->fetch_array()):
                                    $course = $row['course'];
                                ?>
                                <option
