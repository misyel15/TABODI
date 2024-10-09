<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css">
    <script src="js/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Main layout adjustments */
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
        /* Logo styling */
        #logo-img {
            width: 5em;
            height: 5em;
            object-fit: cover;
            object-position: center center;
            border-radius: 50%;
        }
        /* Make the layout responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }
            .h1 {
                font-size: 1.5rem;
            }
            #logo-img {
                width: 4em;
                height: 4em;
            }
            .btn {
                padding: 0.75rem 1rem;
            }
            .login-box {
                margin: 10px;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <img src="assets/uploads/back.png" alt="System Logo" class="img-thumbnail rounded-circle" id="logo-img">
            <a class="h1"><b>Retrieve</b>|Account</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
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
            <p class="mt-3 mb-1">
                <a href="index.php">Login</a>
            </p>
        </div>
    </div>
</div>


</html>
