<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is locked out due to failed attempts
    if (isset($_SESSION['lock_time']) && $_SESSION['lock_time'] !== null) {
        if (time() - $_SESSION['lock_time'] < 5) {
            $remaining_time = 5 - (time() - $_SESSION['lock_time']);
            echo "Locked out. Please try again in $remaining_time seconds.";
            exit;
        } else {
            // Reset attempts after lockout period
            $_SESSION['lock_time'] = null;
        }
    }

    // Sanitize user input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $course = htmlspecialchars(trim($_POST['course']));

    // CAPTCHA verification
    $captcha_response = $_POST['h-captcha-response'];
    $secret_key = 'ES_7f358ad256b1474aa1262e98acc952ae';
    $captcha_verify = file_get_contents("https://hcaptcha.com/siteverify?secret=$secret_key&response=$captcha_response");
    $captcha_response_data = json_decode($captcha_verify);
    if (!$captcha_response_data->success) {
        echo 'CAPTCHA Failed. Please try again.';
        exit;
    }

    // Prepare and execute login query
    $stmt = $conn->prepare("
        SELECT id, name, username, course, dept_id, type 
        FROM users 
        WHERE username = ? 
        AND password = ? 
    ");
    $hashed_password = md5($password);
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

            // Optionally, you can log or store the latitude and longitude
            $_SESSION['latitude'] = isset($_POST['latitude']) ? $_POST['latitude'] : null;
            $_SESSION['longitude'] = isset($_POST['longitude']) ? $_POST['longitude'] : null;

            if ($_SESSION['login_type'] != 1) {
                session_unset();
                echo 'Access Denied. You do not have permission to access this area.';
            } else {
                echo 'Login Successful. Redirecting...';
            }
        } else {
            echo 'Course Mismatch. The selected course does not match your account.';
        }
    } else {
        echo 'Login Failed. Username or password is incorrect.';
        $_SESSION['lock_time'] = time(); // Lockout the user for 5 seconds
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
    
          <!-- reCAPTCHA Widget -->
      <script src="https://hcaptcha.com/1/api.js" async defer></script>

   
</head>

<style>
    .cookie-consent-content button {
    background-color: #4caf50; /* Green for Accept */
    border: none;
    color: white;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    margin-right: 10px; /* Space between buttons */
}

.cookie-consent-content button#declineCookie {
    background-color: #f44336; /* Red for Decline */
}

.cookie-consent-content button:hover {
    opacity: 0.8;
}

  .cookie-consent-banner {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #333;
  color: white;
  padding: 10px;
  text-align: center;
  z-index: 9999;
  display: block;
}

.cookie-consent-banner .cookie-consent-content {
  display: inline-block;
}

.cookie-consent-banner button {
  margin-left: 10px;
  padding: 5px 15px;
  cursor: pointer;
}


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
                                        <input class="au-input au-input--full" type="password" name="password" placeholder="Password" id="password" required>
                                        <i class="fa fa-eye-slash eye-icon" id="toggle-password"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Course</label>
                                    <input class="au-input au-input--full" type="text" name="course" placeholder="Course" required>
                                </div>
                                <!-- CAPTCHA -->
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY_HERE"></div>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

    <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Toggle the eye icon
        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });

    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Add form submission code here
        let formData = new FormData(this);
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data
            });
        })
        .catch(error => console.error('Error:', error));
    });
    </script>
</body>
</html>
