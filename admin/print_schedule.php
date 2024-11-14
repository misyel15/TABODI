<?php
session_start();
include('db_connect.php');

// Get the department ID and selected room from the session and request
$dept_id = $_SESSION['dept_id'];
$selected_room = isset($_GET['selected_room']) ? $_GET['selected_room'] : '';

if (!$selected_room) {
    echo "No room selected for printing.";
    exit;
}

// Helper function to display schedules by day type
function display_schedule($day_type, $selected_room, $dept_id, $conn) {
    echo "<h4 class='text-center'>$day_type Schedule</h4>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th class='text-center'>Time</th><th class='text-center'>$selected_room</th></tr></thead><tbody>";

    // Fetch time slots for the specific day type
    $times = [];
    $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='$day_type' AND dept_id = '$dept_id' ORDER BY time_id");
    while ($t = $timesdata->fetch_assoc()) {
        $times[] = $t['timeslot'];
    }

    // Display each time slot
    foreach ($times as $time) {
        echo "<tr><td>" . htmlspecialchars($time) . "</td>";

        // Prepare statement to fetch loading data
        $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $time, $selected_room, $day_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $course = htmlspecialchars($row['course']);
                $subject = htmlspecialchars($row['subjects']);
                $faculty_id = htmlspecialchars($row['faculty']);

                // Fetch faculty name
                $faculty_stmt = $conn->prepare("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=?");
                $faculty_stmt->bind_param('i', $faculty_id);
                $faculty_stmt->execute();
                $faculty_name_result = $faculty_stmt->get_result();
                $faculty_name = $faculty_name_result->fetch_assoc()['name'] ?? 'Unknown Faculty';

                echo "<td class='text-center'>" . htmlspecialchars("$subject $course $faculty_name") . "</td>";
            }
        } else {
            echo "<td></td>"; // No data for this time slot
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Room Schedule - <?php echo htmlspecialchars($selected_room); ?></title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Include Bootstrap CSS -->
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <div class="text-center">
        <h2>Room Schedule for <?php echo htmlspecialchars($selected_room); ?></h2>
        <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
    </div>

    <?php
    // Display each schedule by day type
    display_schedule("MW", $selected_room, $dept_id, $conn);
    display_schedule("TTH", $selected_room, $dept_id, $conn);
    display_schedule("FS", $selected_room, $dept_id, $conn);
    ?>

</div>

</body>
</html>
