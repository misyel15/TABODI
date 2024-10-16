<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('db_connect.php');

// Check if the user is logged in and has a dept_id
if (!isset($_SESSION['username']) || !isset($_SESSION['dept_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

date_default_timezone_set('Asia/Manila'); // Change according to timezone
$currentTime = date('d-m-Y h:i:s A', time());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>
    <?php include 'include/head.php'; ?>
    <?php include 'notif.php'; ?>
</head>
<body class="animsition">
    <div class="wrapper">
        <nav class="main-header">
            <?php include 'include/header.php'; ?>
        </nav>
        <aside class="main-sidebar">
            <?php include 'include/sidebar.php'; ?>    
        </aside>
        <br><br><br>

        <div class="content-wrapper">
            <br>
            <section class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('<?php echo isset($data['Image']) ? htmlentities($data['Image']) : ''; ?>');">
                                <div class="bg-overlay bg-overlay--blue"></div>
                                <h3>
                                    <button class="btn-sm" style="margin-right: 1px;" onclick="location.href='home.php'">
                                        <i class="fa fa-arrow-circle-left"></i>
                                    </button>
                                    <i class="zmdi zmdi-notifications"></i> Notifications
                                </h3>
                            </div>

                            <?php
                            // Ensure 'id' is present in the URL
                            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                                $id = intval($_GET['id']);
                            } else {
                                echo "<p style='color:red;'>Notification ID is missing or invalid.</p>";
                                // Optionally, redirect the user or provide a link back to notifications
                                echo "<p><a href='notifications_list.php'>Go back to notifications list</a></p>";
                                exit; // Stop further execution
                            }

                            // Use the correct connection variable
                            $query = mysqli_prepare($conn, "SELECT s.name, s.username, n.message, n.timestamp, s.course
                                                            FROM users s
                                                            INNER JOIN notifications n ON s.id = n.user_id
                                                            WHERE n.id = ?");
                            mysqli_stmt_bind_param($query, 'i', $id);
                            mysqli_stmt_execute($query);
                            $result = mysqli_stmt_get_result($query);

                            // Fetch and display the results
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
                                                    <strong><p><?php echo htmlentities($row['name']); ?> <?php echo htmlentities($row['username']); ?></p></strong>
                                                    <br>
                                                    <strong><p>Course:</p></strong>
                                                    <p><?php echo htmlentities($row['course']); ?></p>
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
        }
    </style>
</body>
</html>
