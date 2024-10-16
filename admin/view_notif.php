<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    date_default_timezone_set('Asia/Manila'); // Change according to timezone
    $currentTime = date('d-m-Y h:i:s A', time());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notifications</title>
  <?php include 'includes/head.php'; ?>
  <?php include 'notif.php'; ?>
</head>
<body class="animsition">
    <div class="wrapper">

        <div class="content-wrapper">
            <br>
            <section class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">

                            <div class="au-card-title" style="background-image:url('<?php echo $data['Image']; ?>');">
                                <div class="bg-overlay bg-overlay--blue"></div>
                                <h3>
                                    <button class="btn-sm" style="margin-right: 1px;" onclick="location.href='home.php'">
                                        <i class="fa fa-arrow-circle-left"></i>
                                    </button>
                                    <i class="zmdi zmdi-notifications"></i> Notifications
                                </h3>
                            </div>

                            <?php
                            $id = intval($_GET['id']);
                            include 'include/config.php';

                            // Adjust the SQL query according to your notification table structure
                            $query = mysqli_prepare($bd, "SELECT p.lastname, p.firstname, p.middlename, n.message, n.timestamp, p.barangay 
                                                           FROM profiling p 
                                                           INNER JOIN notifications n ON p.id = n.user_id 
                                                           WHERE n.id = ?");
                            mysqli_stmt_bind_param($query, 'i', $id);
                            mysqli_stmt_execute($query);
                            $result = mysqli_stmt_get_result($query);

                            // Fetch results
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <div class="au-task js-list-load">
                                    <div class="au-task-list js-scrollbar3">
                                        <div class="au-task__item au-task__item--primary">
                                            <div class="au-task__item-inner">
                                                <div class="text">
                                                    <strong><p>Information:</p></strong>
                                                    <h5 class="name"><?php echo htmlentities($row['message']); ?></h5>
                                                    <br>
                                                    <strong><p>Awardee Name:</p></strong>
                                                    <strong><p><?php echo htmlentities($row['firstname']); ?> <?php echo htmlentities($row['middlename']); ?> <?php echo htmlentities($row['lastname']); ?></p></strong>
                                                    <br>
                                                    <strong><p>Address:</p></strong>
                                                    <p><?php echo htmlentities($row['barangay']); ?></p>
                                                    <span class="date"><?php echo date('F j, Y g:ia', strtotime($row['timestamp'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            mysqli_stmt_close($query);
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style type="text/css">
        .unread {
            background-color: antiquewhite; /* Example background color for unread notifications */
            /* Add any other styles you want for unread notifications */
        }
    </style>
</body>
</html>
