<?php
// Optionally include a header file if you have a reusable header
include('header.php'); // Remove this line if not needed

// Optional variables for dynamic content
$pageTitle = "About Us";
$companyName = "Your Company Name";
$description = "We are committed to providing the best service possible with a focus on customer satisfaction.";
$yearFounded = 2005;
$location = "New York, USA";
$teamMembers = ["John Doe", "Jane Smith", "Alice Johnson", "Michael Brown"];
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
                <li><a href="About.php" class="active">About</a></li>
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
                <?php
                foreach ($teamMembers as $member) {
                    echo "<li>$member</li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo $companyName; ?>. All rights reserved.</p>
    </footer>

</body>
</html>
