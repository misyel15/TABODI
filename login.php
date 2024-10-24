<?php 
include 'style.php'; 
include 'headers.php'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Basic Meta Tags -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  
  <!-- External Libraries and Stylesheets -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />
  <title>Mcc Faculty Scheduling</title>
  <link rel="icon" type="image/png" href="mcclogo.jpg">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap1.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Fonts Style -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />

 <style>
  /* Custom Styles */
  body {
    background-color: darkwhite; /* Light gray background */
  }

  .text-gradient {
    background: linear-gradient(315deg, #1e30f3 0%, #e21e80 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* Dropdown menu hover style */
  .dropdown-item:hover {
    background-color: lightgray;
    color: #fff;
  }

  /* Carousel image adjustments */
  .carousel-item img {
    border-radius: 50%;
    max-height: 400px;
    width: 100%;
    object-fit: cover;
  }

  .header_section {
    background-color: #eae6f5;
  }

  .navbar-brand img {
    border-radius: 50%;
    border: 2px solid #fff;
  }

  /* Adjust login dropdown button */
  .nav-link.dropdown-toggle {
    padding-right: 1rem;
  }
  
  .navbar-brand img {
    border: 2px solid #333; /* Adds a border around the logo */
    padding: 5px; /* Adds padding inside the border */
    background-color: #f8f9fa; /* Sets a background color behind the logo */
    border-radius: 50%; /* Ensures the logo stays rounded */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Adds a shadow effect */
    transition: transform 0.3s ease-in-out; /* Adds a transition effect */
    margin-right: 100%;
  }

  .navbar-brand img:hover {
    transform: scale(1.1); /* Scales up the logo on hover */
  }

  .logo-container {
    display: flex;
    align-items: center;
    margin-left: -10%;
  }

  .logo-text {
    font-size: 1.0rem; /* Adjust font size as needed */
    color: #333; /* Adjust text color as needed */
    margin-left: -95%; /* Space between image and text */
    font-weight: bold; /* Make the text bold */
  }

  .navbar-nav .nav-link,
  .navbar-nav .dropdown-item {
    color: black !important;
  }
</style>

</head>

<body>

  <!-- Header Section -->
  <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container">
        <a class="navbar-brand" href="index.php">
          <div class="logo-container d-flex align-items-center">
            <img src="mcclogo.jpg" width="45px" height="50px" alt="System Logo" class="img-thumbnail rounded-circle">
            <span class="logo-text ms-3">MFSS</span>
          </div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active" style="margin-left:10px; font-weight: bold;">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item" style="margin-left:10px; font-weight: bold;">
              <a class="nav-link" href="About.php">About</a>
            </li>
            <li class="nav-item dropdown" style="margin-left:10px; font-weight: bold;">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Login
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="home.php"><i class="fas fa-user-secret"></i> Instructor</a>
                <a class="dropdown-item" href="./admin/login.php"><i class="fas fa-user-cog"></i> Admin</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>

  <!-- Main Content Section -->
  <section class="about_section layout_padding">
    <div class="container">
      <div class="row">
        <!-- Text Section -->
        <div class="col-md-6">
          <div class="detail_box">
            <h1 class="display-3 fw-bolder mb-5">
              <marquee style="font-size: 50px;">
                <span class="text-gradient" style="font-family: Algerian;">Welcome to MCC Faculty Scheduling </span>
              </marquee>
            </h1>
            <p class="lead">Here you can manage the scheduling of all faculty members efficiently and effectively. Our system helps you stay organized and up-to-date with the latest changes in the schedule.</p>
          </div>
        </div>
        
        <!-- Image Carousel Section -->
        <div class="col-lg-6 col-md-6">
          <div class="img_content" style="background: transparent; border-radius: ; margin-top:30px;">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="end.jpg" class="d-block w-100" alt="Logo Image">
                </div>
                <div class="carousel-item">
                  <img src="end.jpg" class="d-block w-100" alt="End Image">
                </div>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- JS Libraries -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
