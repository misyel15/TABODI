<?php
// Example condition in PHP (this could be based on a database query, form submission, etc.)
$newMessage = true;  // This could be set based on your application's logic

// Notification title and message (you can customize these dynamically based on the condition)
if ($newMessage) {
    $notificationTitle = "New Message";
    $notificationMessage = "You have a new message in your inbox!";
} else {
    $notificationTitle = "";
    $notificationMessage = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Example</title>
    <script>
        // Function to show browser notification
        function showNotification(title, message) {
            // Check if the browser supports notifications
            if (!("Notification" in window)) {
                alert("This browser does not support desktop notifications.");
            }
            // If permission is granted, show the notification
            else if (Notification.permission === "granted") {
                var notification = new Notification(title, { body: message });
            }
            // If permission is not yet granted, request it
            else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function (permission) {
                    if (permission === "granted") {
                        var notification = new Notification(title, { body: message });
                    }
                });
            }
        }

        // Trigger the notification if PHP condition is met
        window.onload = function() {
            <?php if ($newMessage) : ?>
                // Pass PHP values to JavaScript
                var title = "<?php echo $notificationTitle; ?>";
                var message = "<?php echo $notificationMessage; ?>";
                showNotification(title, message);
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <h1>PHP Conditional Notification Example</h1>

    <!-- Your HTML content here -->

</body>
</html>
