<?php
include 'db_connect.php'; // Database connection

// Check if the notification_id is provided in the POST request
if (isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']); // Sanitize input

    // Prepare the SQL query to update the notification status
    $update_query = "UPDATE notifications SET status = 'read' WHERE id = ?";
    $update_stmt = $bd->prepare($update_query);
    $update_stmt->bind_param('i', $notification_id);
    $update_stmt->execute();

    // Check if any rows were affected
    if ($update_stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No rows updated.']);
    }

    // Close the prepared statement
    $update_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

