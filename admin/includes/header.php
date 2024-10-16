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

</head>

<style>
    .header-desktop {
    position: fixed;
    margin-top: 0px;
    justify-content: flex-end; /* Align elements to the right */
    align-items: center; /* Vertically center elements */
    padding: 0 15px; /* Add some padding */
    background-color: #f8f9fa; /* Background color for the header */
}
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
/* General header styling */
.header-desktop {
    position: fixed;
   margin-top:0px;
    justify-content: space-between; /* Space out elements */
    align-items: center; /* Vertically center elements */
    padding: 0 15px; /* Add some padding */
    background-color: #f8f9fa; /* Background color for the header */
}



/* Notifications styling */
.noti-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

/* Account wrap styling */
.account-wrap {
    display: flex;
    align-items: center;
}

/* Account dropdown positioning */
.account-dropdown {
    position: absolute;
    right: 0;
    top: 100%; /* Position below the account item */
    margin-top: 10px; /* Space between account item and dropdown */
    min-width: 200px; /* Set minimum width for dropdown */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add shadow for better visibility */
    z-index: 1000; /* Ensure dropdown is above other elements */
}

/* Ensure image and content align properly */
.image img {
    border-radius: 50%;
    width: 50px;
    height: 40px;
}

</style>
<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                      <div>
                           
                    <img src="assets/uploads/mcclogo.jpg"style="height: 50px; width: 50px;" alt="Mcc Faculty Scheduling"  />
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
                        <li class="has-sub">
                        <li><a href="home.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
<li><a href="courses.php"><i class="fas fa-book"></i>Course</a></li>
<li><a href="subjects.php"><i class="fas fa-book-open"></i>Subject</a></li>
<li><a href="faculty.php"><i class="fa fa fa-user-tie"></i>Faculty</a></li>
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

                        </li>
                       
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
               
                    <img src="assets/uploads/mcclogo.jpg"style="height: 50px; width: 50px;" alt="Mcc Faculty Scheduling"  />
                    Mcc Faculty Scheduling
               
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        
                   <li><a href="home.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
<li><a href="courses.php"><i class="fas fa-book"></i>Course</a></li>
<li><a href="subjects.php"><i class="fas fa-book-open"></i>Subject</a></li>
<li><a href="faculty.php"><i class="fa fa fa-user-tie"></i>Faculty</a></li>
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

                        </li>
                       
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP--> 
             <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                       </form>
                            <div class="header-button">
                                <div class="noti-wrap" >
                                    
                                                               
<div class="noti__item js-item-menu">
    <i class="zmdi zmdi-notifications"></i>
    <span class="quantity"><?php echo htmlentities($unreadCount); ?></span>
    <div class="notifi-dropdown js-dropdown" style="max-height: 300px; overflow-y: auto;">
        <div class="notifi__title">
            <p>You have <?php echo htmlentities($unreadCount); ?> Notifications</p>
        </div>

        <?php while ($notification = mysqli_fetch_assoc($rt)): ?>
            <?php $class = $notification['status'] === 'read' ? 'read' : 'unread'; ?>
            <div class="notifi__item <?php echo $class; ?>" id="notification_<?php echo (int) $notification['id']; ?>" onclick="markAsRead(<?php echo (int) $notification['id']; ?>)">
                <div class="bg-c1 img-cir img-40">
                    <i class="zmdi zmdi-notifications"></i>
                </div>
                <div class="content">
                    <p><?php echo htmlentities($notification['message']); ?></p>
                    <span class="date"><?php echo date('F j, Y g:ia', strtotime($notification['timestamp'])); ?></span>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="notifi__footer">
            <a href="view_notif.php">All notifications</a>
        </div>
    </div>
</div>
                                <div class="account-wrap float-right">
                                    <div class="account-item clearfix js-item-menu">
                                       
                                        <div class="content">
                                            <!-- Check if session variable is set before using it -->
                                            
                                    <i class="fas fa-user"></i> 
                                    <?php echo $_SESSION['name']; ?>
                                </a>
                                
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                           
                                                <div class="content">
                                            
                                    <div class="info clearfix" style="display: flex; align-items: center;">
                                        <!-- User Icon -->
                                        <i class="fas fa-user" style="font-size: 24px; margin-right: 10px;"></i> 
                                        
                                        <!-- User Info (Name and Email) -->
                                        <div class="content">
                                            <h5 class="name" style="font-size: 17px; margin-left: -20%;">
                                            <?php echo $_SESSION['name']; ?>
                                            </h5>
                                            <span class="email" style="font-size: 14px; color: #888;">
                                                <?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

       
            <div class="account-dropdown__footer">
                <a href="ajax.php?action=logout">
                    <i class="zmdi zmdi-power"></i>Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#manage_my_account').click(function(){
        uni_modal("Manage Account", "manage_user.php?id=<?php echo isset($_SESSION['login_id']) ? $_SESSION['login_id'] : '0'; ?>&mtype=own");
    });
</script>

</header>
    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

  	
</head>
<script>
    $(document).ready(function () {
      $('.carousel').carousel({
        interval: 3000  // Change slide every 3 seconds
      });
    });
  </script>
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
     <script>
function markAsRead(notificationId) {
    // Send AJAX request to mark notification as read
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_as_read.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Reload the notification bell or redirect to all notifications page after marking as read
                window.location.href = 'view_notif.php?id=' + encodeURIComponent(notificationId); // Properly handled redirection
            } else {
                console.error('Error marking notification as read');
            }
        }
    };
    xhr.send('notification_id=' + encodeURIComponent(notificationId)); // Properly encoded data
}
</script>                               
</body>

</html>
