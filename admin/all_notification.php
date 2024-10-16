<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to login page if session is not active
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Include database connection
include 'db_connect.php';  // Ensure this is correctly included


// Set timezone and get the current time
date_default_timezone_set('Asia/Manila');
$currentTime = date('d-m-Y h:i:s A', time());

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
        
        <br><br><br>

        <div class="content-wrapper">
            <br>
            <section class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                            <div class="au-card-title" style="background-image:url('<?php echo htmlentities($data['Image']); ?>');">
                                <div class="bg-overlay bg-overlay--blue"></div>

                                <?php
include 'db_connect.php';
                                // Count the number of unread notifications
                                $unreadQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'";
                                $unreadResult = mysqli_query($bd, $unreadQuery);  // Ensure $bd is defined here
                                if ($unreadResult) {
                                    $unreadData = mysqli_fetch_assoc($unreadResult);
                                    $unreadCount = $unreadData['unread_count'];
                                } else {
                                    echo "Error: " . mysqli_error($bd);  // Optionally handle query error
                                    $unreadCount = 0;  // Fallback if error occurs
                                }

                                // Fetch all notifications ordered by timestamp
                                $rt = mysqli_query($bd, "SELECT * FROM notifications ORDER BY timestamp DESC");
                                if (!$rt) {
                                    echo "Error fetching notifications: " . mysqli_error($bd);  // Handle query error
                                }
                                ?>
                                <h3>
                                    <i class="zmdi zmdi-account-calendar"></i> You have <?php echo htmlentities($unreadCount); ?> Notifications
                                </h3>
                                <button class="au-btn-plus">
                                    <a href="home.php" style="color:white">Back</a>
                                </button>
                            </div>

                            <div class="au-task js-list-load">
                                <div class="au-task-list js-scrollbar3">
                                    <div class="au-task__item au-task__item--primary">
                                        <div class="au-task__item-inner">
                                            <?php if ($rt): ?>
                                                <?php while ($notification = mysqli_fetch_assoc($rt)): ?>
                                                    <?php $class = $notification['status'] == 'read' ? 'read' : 'unread'; ?>
                                                    <div class="notifi__item <?php echo $class; ?>" id="notification_<?php echo $notification['id']; ?>" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                                                        <div class="bg-c1 img-cir img-40">
                                                            <i class="zmdi zmdi-account-box"></i>
                                                        </div>
                                                        <div class="content">
                                                            <p><?php echo htmlentities($notification['message']); ?>&nbsp;<?php echo htmlentities($notification['user_id']); ?></p>
                                                            <span class="date"><?php echo htmlentities($notification['timestamp']); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <p>No notifications found.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="au-task__footer">
                                    <button class="au-btn au-btn-load js-load-btn">View All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style type="text/css">
        .unread {
            background-color: antiquewhite; /* Background color for unread notifications */
        }
    </style>

    <script>
        function markAsRead(notificationId) {
            // Send AJAX request to mark notification as read
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'mark_as_read.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Optionally handle successful read marking, e.g., change UI
                        console.log('Notification marked as read');
                        document.getElementById('notification_' + notificationId).classList.remove('unread');
                    } else {
                        console.error('Error marking notification as read');
                    }
                }
            };
            xhr.send('notification_id=' + notificationId);
        }
    </script>
</body>
</html>
