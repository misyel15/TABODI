<?php
// Optionally include a header file if you have a reusable header
include('header.php'); // Remove if not needed

// Variables for dynamic content
$pageTitle = "About Us";
$companyName = "Mcc Faculty Scheduling System";
$description = "We are committed to providing the best service possible with a focus on customer satisfaction.";
$yearFounded = 2006;
$location = "New York, USA";
$teamMembers = ["John Rey Ybanez", "Michelle Layos Cose", "James Tequillo", "Jeslyn Ybanez"];
$companyEmail = "info@mccfacultyscheduling.com"; // Use a valid email format

// Process the contact form submission
$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Email validation
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $to = $companyEmail;
        $subject = "New Contact Form Submission from $name";
        $headers = "From: $email\r\nReply-To: $email\r\n";
        $body = "Name: $name\nEmail: $email\n\n$message";

        // Use mail() function to send email
        if (mail($to, $subject, $body, $headers)) {
            $messageSent = true;
        } else {
            $error = "There was an error sending your message. Please try again.";
        }
    } else {
        $error = "Please fill out all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About Us - Learn more about our company, mission, and team.">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1><?php echo $companyName; ?></h1>
        <nav>
            <ul>
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main>
        <section class="about-section">
            <h2>About Us</h2>
            <p><?php echo $description; ?></p>

            <h3>Founded</h3>
            <p>We started our journey in <?php echo $yearFounded; ?> in <?php echo $location; ?>.</p>

            <h3>Meet Our Team</h3>
            <ul>
                <?php foreach ($teamMembers as $member) {
                    echo "<li>$member</li>";
                } ?>
            </ul>
        </section>

        <!-- Contact Form Section -->
        <section class="contact-section">
            <h3>Contact Us</h3>

            <?php if ($messageSent): ?>
                <p class="success-message">Thank you for reaching out! We’ll get back to you soon.</p>
            <?php else: ?>
                <?php if (!empty($error)): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>

                <form action="about.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo $companyName; ?>. All rights reserved.</p>
    </footer>

</body>
</html>
