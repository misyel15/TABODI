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
    <?php include 'includes/header.php'; ?>
    <?php include 'notif.php'; ?>
    <?php include 'mark_as_read.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="animsition">
    <div class="wrapper">
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
                            // Fetch notifications from the database
                            $query = "SELECT n.id, s.name, s.username, n.message, n.timestamp, s.course 
                                      FROM users s 
                                      INNER JOIN notifications n ON s.id = n.user_id 
                                      ORDER BY n.timestamp DESC"; // Fetch all notifications
                            $result = mysqli_query($conn, $query);

                            // Check if there are notifications
                            if (mysqli_num_rows($result) > 0) {
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
                                                        <button class="mark-as-read" data-id="<?php echo htmlentities($row['id']); ?>">Mark as Read</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p>No notifications available.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.mark-as-read').on('click', function() {
            var notificationId = $(this).data('id'); // Get the notification ID

            $.ajax({
                type: 'POST',
                url: 'mark_as_read.php',
                data: { notification_id: notificationId },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert("Notification marked as read.");
                        // Optionally, refresh the page or update the UI
                        location.reload(); // Refresh to show updated notifications
                    } else {
                        alert("Error: " + data.message);
                    }
                },
                error: function() {
                    alert("An error occurred while marking the notification as read.");
                }
            });
        });
    });
    </script>

    <style type="text/css">
        .unread {
            background-color: antiquewhite; /* Example background color for unread notifications */
        }
    </style>
</body>
</html>
