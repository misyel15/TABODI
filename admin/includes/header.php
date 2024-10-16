<?php 
include 'db_connect.php'; 
include 'notif.php';

// Check if the user is logged in and has a dept_id
if (!isset($_SESSION['username']) || !isset($_SESSION['dept_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Mcc Faculty Scheduling</title>
    <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/png">

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" media="all">
    
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

    <style>
        .header-mobile {
            position: fixed; /* Fixes the header at the top */
            top: 0;
            left: 0;
            height: 20px;
            width: 100%; /* Ensures the header spans the full width */
            z-index: 9999; /* Keeps it above other elements */
        }

        .header-mobile__bar {
            display: flex; /* Flexbox for layout */
            justify-content: space-between; /* Space between the logo and button */
            align-items: center; /* Center-align items vertically */
            background-color: #fff; /* Background color (adjust as needed) */
            padding: 10px 15px; /* Padding around content */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional shadow */
        }

        .header-desktop {
            position: fixed;
            justify-content: space-between; /* Space out elements */
            align-items: center; /* Vertically center elements */
            padding: 0 15px; /* Add some padding */
            background-color: #f8f9fa; /* Background color for the header */
        }

        .noti-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .account-wrap {
            display: flex;
            align-items: center;
        }

        .account-dropdown {
            position: absolute;
            right: 0;
            top: 100%; /* Position below the account item */
            margin-top: 10px; /* Space between account item and dropdown */
            min-width: 200px; /* Set minimum width for dropdown */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add shadow for better visibility */
            z-index: 1000; /* Ensure dropdown is above other elements */
        }

        .image img {
            border-radius: 50%;
            width: 50px;
            height: 40px;
        }
    </style>
</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <div>
                            <img src="assets/uploads/mcclogo.jpg" style="height: 50px; width: 50px;" alt="Mcc Faculty Scheduling" />
                            Mcc Faculty Scheduling
                        </div>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li><a href="home.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                        <li><a href="courses.php"><i class="fas fa-book"></i>Course</a></li>
                        <li><a href="subjects.php"><i class="fas fa-book-open"></i>Subject</a></li>
                        <li><a href="faculty.php"><i class="fas fa-chalkboard-teacher"></i>Faculty</a></li>
                        <li><a href="room.php"><i class="fas fa-door-open"></i>Room</a></li>
                        <li><a href="timeslot.php"><i class="fas fa-clock"></i>Timeslot</a></li>
                        <li><a href="section.php"><i class="fas fa-users"></i>Section</a></li>
                        <li><a href="roomassigntry.php"><i class="fas fa-clipboard-list"></i>Room Assignment</a></li>
                        <li><a href="roomsched.php"><i class="fas fa-calendar-alt"></i>Room Schedule</a></li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Other Reports</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li><a href="class_sched.php"><i class="fas fa-calendar"></i>Class Schedule</a></li>
                                <li><a href="load.php"><i class="fas fa-tasks"></i>Instructor's Load</a></li>
                                <li><a href="summary.php"><i class="fas fa-file-alt"></i>Summary</a></li>
                                <li><a href="export.php"><i class="fas fa-file-export"></i>Export CSV</a></li>
                            </ul>
                        </li>
                        <li><a href="users.php"><i class="fas fa-user"></i>User</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <img src="assets/uploads/mcclogo.jpg" style="height: 50px; width: 50px;" alt="Mcc Faculty Scheduling" />
                Mcc Faculty Scheduling
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li><a href="home.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                        <li><a href="courses.php"><i class="fas fa-book"></i>Course</a></li>
                        <li><a href="subjects.php"><i class="fas fa-book-open"></i>Subject</a></li>
                        <li><a href="faculty.php"><i class="fas fa-chalkboard-teacher"></i>Faculty</a></li>
                        <li><a href="room.php"><i class="fas fa-door-open"></i>Room</a></li>
                        <li><a href="timeslot.php"><i class="fas fa-clock"></i>Timeslot</a></li>
                        <li><a href="section.php"><i class="fas fa-users"></i>Section</a></li>
                        <li><a href="roomassigntry.php"><i class="fas fa-clipboard-list"></i>Room Assignment</a></li>
                        <li><a href="roomsched.php"><i class="fas fa-calendar-alt"></i>Room Schedule</a></li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Other Reports</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li><a href="class_sched.php"><i class="fas fa-calendar"></i>Class Schedule</a></li>
                                <li><a href="load.php"><i class="fas fa-tasks"></i>Instructor's Load</a></li>
                                <li><a href="summary.php"><i class="fas fa-file-alt"></i>Summary</a></li>
                                <li><a href="export.php"><i class="fas fa-file-export"></i>Export CSV</a></li>
                            </ul>
                        </li>
                        <li><a href="users.php"><i class="fas fa-user"></i>User</a></li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop d-none d-lg-flex">
                <div class="container-fluid">
                    <div class="header-desktop__content">
                        <div class="header-desktop__logo">
                            <img src="assets/uploads/mcclogo.jpg" style="height: 50px; width: 50px;" alt="Mcc Faculty Scheduling" />
                        </div>
                        <div class="header-desktop__notif">
                            <div class="noti-wrap">
                                <div class="noti__item js-item-menu">
                                    <i class="fas fa-bell"></i>
                                    <?php if ($unreadCount > 0) : ?>
                                        <span class="quantity"><?php echo $unreadCount; ?></span>
                                    <?php endif; ?>
                                    <div class="mess-dropdown js-dropdown">
                                        <div class="mess__title">
                                            <p>You have <?php echo $unreadCount; ?> notifications</p>
                                        </div>
                                        <div class="mess__item">
                                            <span>Check out new assignments!</span>
                                        </div>
                                        <div class="mess__footer">
                                            <a href="#">View all notifications</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item account-item--style2 clearfix js-item-menu">
                                        <div class="image">
                                            <img src="assets/uploads/profile.jpg" alt="Profile" />
                                        </div>
                                        <div class="content">
                                            <span class="username"><?php echo $_SESSION['username']; ?></span>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="profile.php"><i class="fas fa-user"></i>Profile</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="container-fluid">
                    <!-- Your content goes here -->
                </div>
            </div>
            <!-- END MAIN CONTENT-->
        </div>
        <!-- END PAGE CONTAINER-->
    </div>

    <!-- Jquery JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>

    <!-- Vendor JS-->
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

    <!-- Main JS-->
    <script src="js/main.js"></script>
</body>

</html>
