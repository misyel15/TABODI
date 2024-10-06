
<?php 
session_start();
include('admin/db_connect.php');
ob_start();
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }
        /* Make sure navbar adapts well to mobile */
        .navbar {
            margin-bottom: 20px;
        }
        .navbar-brand, .nav-link {
            font-size: 1.0rem;
            padding-left: 20px;
        }
        .dropdown-menu {
            left: auto;
            right: 0; /* Align dropdown to the right on mobile */
        }

        /* Ensure the dropdown menu is easily tappable */
        .dropdown-item {
            padding: 10px 20px;
            font-size: 1rem;
        }

        /* Improve layout for mobile devices */
        @media (max-width: 767px) {
            .navbar-brand {
                font-size: 1rem;
            }
            .nav-link {
                font-size: 0.9rem;
            }
            /* Adjust padding for smaller screens */
            .dropdown-item {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
        
  .navbar-brand img {
    width: 50px;
    height: 40px;
  }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
      <img src="back.png" alt="Logo"> School Faculty Scheduling System
    </a>       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Login
                    </a>
                    <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <a class="dropdown-item" href="Admin/login.php">Admin</a>
                        <a class="dropdown-item" href="home.php">Instructor</a>
                       
                </li>
            </ul>
        </div>
    </nav>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
