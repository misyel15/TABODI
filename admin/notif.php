<?php
    include 'include/config.php';          

    // Count the number of unread notifications
    $unreadQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'";
    $unreadResult = mysqli_query($bd, $unreadQuery);
    $unreadData = mysqli_fetch_assoc($unreadResult);
    $unreadCount = $unreadData['unread_count'];

    // Fetch all notifications
    $rt = mysqli_query($bd, "SELECT * FROM notifications ORDER BY timestamp DESC");
    $num1 = mysqli_num_rows($rt);
    ?>