<?php
    include 'db_connect.php';          

    // Count the number of unread notifications
    $unreadQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'";
    $unreadResult = mysqli_query($conn, $unreadQuery);

    if (!$unreadResult) {
        die('Error with unread notifications query: ' . mysqli_error($conn));
    }

    $unreadData = mysqli_fetch_assoc($unreadResult);
    $unreadCount = isset($unreadData['unread_count']) ? $unreadData['unread_count'] : 0;

    // Fetch all notifications
    $rt = mysqli_query($conn, "SELECT * FROM notifications ORDER BY timestamp DESC");

    if (!$rt) {
        die('Error fetching notifications: ' . mysqli_error($conn));
    }
?>
