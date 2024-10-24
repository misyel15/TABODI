<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission, Vision & Core Values with Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
        }
        .calendar-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
        }
        .calendar {
            width: 30%;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .section {
            margin-bottom: 40px;
        }
        .section p {
            text-align: justify;
            padding: 0 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Our Mission, Vision, and Core Values</h1>

    <div class="calendar-container">
        <div class="calendar">
            <?php displayCalendar(-1); ?>
        </div>
        <div class="calendar">
            <?php displayCalendar(0); ?>
        </div>
        <div class="calendar">
            <?php displayCalendar(1); ?>
        </div>
    </div>

    <div class="section">
        <h2>Mission</h2>
        <p>Our mission is to provide quality education that empowers students with the knowledge, skills, and values necessary to thrive in their professional and personal lives.</p>
    </div>

    <div class="section">
        <h2>Vision</h2>
        <p>We envision a world where education is accessible to all, fostering innovation, leadership, and societal progress.</p>
    </div>

    <div class="section">
        <h2>Core Values</h2>
        <p>Our core values include integrity, inclusivity, excellence, and a commitment to continuous improvement and lifelong learning.</p>
    </div>
</div>

</body>
</html>

<?php
// Function to display a calendar for a given month offset (e.g., -1 for previous month, 0 for current, 1 for next)
function displayCalendar($monthOffset) {
    $currentMonth = date('n');
    $currentYear = date('Y');
    $targetMonth = $currentMonth + $monthOffset;
    $targetYear = $currentYear;

    if ($targetMonth < 1) {
        $targetMonth = 12;
        $targetYear--;
    } elseif ($targetMonth > 12) {
        $targetMonth = 1;
        $targetYear++;
    }

    // Get the first day of the month and total number of days in the month
    $firstDayOfMonth = mktime(0, 0, 0, $targetMonth, 1, $targetYear);
    $daysInMonth = date('t', $firstDayOfMonth);

    // Find out which day of the week the month starts on
    $startDay = date('w', $firstDayOfMonth);

    // Month and Year display
    $monthName = date('F', $firstDayOfMonth);
    echo "<h3>$monthName $targetYear</h3>";
    echo "<table>";
    echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr><tr>";

    // Fill in the empty days before the 1st day of the month
    for ($i = 0; $i < $startDay; $i++) {
        echo "<td></td>";
    }

    // Fill in the days of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        echo "<td>$day</td>";
        // Break to a new row after Saturday
        if (($day + $startDay) % 7 == 0) {
            echo "</tr><tr>";
        }
    }

    // Fill in the remaining empty cells at the end of the month
    while (($day + $startDay) % 7 != 1) {
        echo "<td></td>";
        $day++;
    }

    echo "</tr>";
    echo "</table>";
}
?>
