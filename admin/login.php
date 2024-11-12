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
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
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

    .form-group .g-recaptcha {
        transform: scale(0.85);
        transform-origin: 0 0;
    }

    @media (max-width: 600px) {
        .form-group .g-recaptcha {
            transform: scale(0.75);
            transform-origin: 0 0;
        }
    }

    #cookie-consent {
        background-color: #333;
        color: white;
        font-size: 14px;
        padding: 15px;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 9999;
        display: none;
    }

    #cookie-consent button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
    }

    #cookie-consent button:hover {
        background-color: #45a049;
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
                                    <input class="au-input au-input--full" type="text" name="course" placeholder="Course" required>
                                </div>
                                <div class="form-group">
                                    <label>CAPTCHA</label>
                                    <div class="form-group form-group--2">
                                        <div class="g-recaptcha" data-sitekey="your-site-key"></div>
                                    </div>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20">Login</button>
                                <center><a href="#" style="font-size:12px;">Forgot Password?</a></center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Cookie Consent Script
        function checkCookieConsent() {
            if (!getCookie("cookieConsent")) {
                document.getElementById("cookie-consent").style.display = "block";
            }
        }

        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }

        function setCookie(name, value, days) {
            let d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        document.getElementById("accept-cookies").addEventListener("click", function() {
            setCookie("cookieConsent", "true", 365);
            document.getElementById("cookie-consent").style.display = "none";
        });

        window.onload = function() {
            checkCookieConsent();
        };

        // Toggle password visibility
        document.getElementById("togglePassword").addEventListener("click", function () {
            const passwordField = document.getElementById("password");
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;
            this.classList.toggle("fa-eye-slash");
            this.classList.toggle("fa-eye");
        });

        // Handle login form submission
        document.getElementById("login-form").addEventListener("submit", function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch("login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data == 1) {
                    window.location.href = "dashboard.php";
                } else if (data == 2) {
                    Swal.fire({
                        title: 'Access Denied!',
                        text: 'Only authorized users can log in!',
                        icon: 'error'
                    });
                } else if (data == 3) {
                    Swal.fire({
                        title: 'Incorrect Username/Password',
                        text: 'Please check your username and password.',
                        icon: 'error'
                    });
                } else if (data == 4) {
                    Swal.fire({
                        title: 'Course Mismatch',
                        text: 'The course entered is incorrect.',
                        icon: 'error'
                    });
                } else if (data == 5) {
                    Swal.fire({
                        title: 'CAPTCHA Failed',
                        text: 'Please complete the CAPTCHA verification.',
                        icon: 'error'
                    });
                } else if (data == 6) {
                    Swal.fire({
                        title: 'Too Many Attempts!',
                        text: 'You have been temporarily locked out due to too many failed login attempts. Please try again later.',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
</body>
</html>
