<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

    // Start session to access user ID
    session_start();
    include 'db_connect.php'; // Include your database connection

    // Get the user ID from the session (assuming user ID is stored in the session)
    $user_id = $_SESSION['user_id'];

    // Ensure the user is logged in and the user_id is available
    if (!isset($user_id)) {
        die('User not logged in');
    }

    // Count the number of unread notifications for the logged-in user
    $unreadQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread' AND user_id = $user_id";
    $unreadResult = mysqli_query($conn, $unreadQuery);

    if (!$unreadResult) {
        die('Error with unread notifications query: ' . mysqli_error($conn));
    }

    // Fetch unread count from the query result
    $unreadData = mysqli_fetch_assoc($unreadResult);
    $unreadCount = isset($unreadData['unread_count']) ? $unreadData['unread_count'] : 0;

    // Fetch all notifications for the logged-in user
    $notificationsQuery = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY timestamp DESC";
    $notificationsResult = mysqli_query($conn, $notificationsQuery);

    if (!$notificationsResult) {
        die('Error fetching notifications: ' . mysqli_error($conn));
    }
?>
