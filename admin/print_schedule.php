<?php
session_start();
include('db_connect.php');

// Ensure the connection is valid
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the department ID from the session
$dept_id = $_SESSION['dept_id'] ?? null;

// Get the selected room from the query parameter
$selected_room = isset($_GET['selected_room']) ? htmlspecialchars($_GET['selected_room']) : '';

// Function to print the department-specific header
function printPageHeader($dept_id) {
    $headers = [
        4444 => "assets/uploads/end.png",
        5858 => "assets/uploads/EDU.png",
        3333 => "assets/uploads/HM.jpg",
        12345 => "assets/uploads/BA.png",
    ];
    $headerImage = $headers[$dept_id] ?? "assets/uploads/default_header.png";

    // Check if the file exists, fallback to default
    if (!file_exists($headerImage)) {
        $headerImage = "assets/uploads/default_header.png";
    }

    echo '<img src="' . $headerImage . '" alt="Department Header" style="display: block; margin: 0 auto; width: 100%; max-width: 800px; margin-bottom: 20px;">';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule - <?php echo $selected_room ?: 'All Rooms'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
   <script>
    // Detect when the print dialog is closed
    window.onafterprint = function() {
        // Check if there is a previous history entry
        if (window.history.length > 1) {
            window.history.back(); // Go back to the previous page
        } else {
            // Redirect to a specific page if no history is available
            window.location.href = 'https://mccfacultyscheduling.com/admin/roomsched.php'; // Replace with your desired URL
        }
    };

    // Automatically open the print dialog
    window.onload = function() {
        window.print();
    };
</script>

</head>
<body>
    <div class="container">
        <?php printPageHeader($dept_id); ?>

        <center><h3 class="text-center">Room Schedule - <?php echo $selected_room ?: 'All Rooms'; ?></h3></center>
        <table>
            <thead>
                <tr>
                    <th class="text-center">Time</th>
                    <th class="text-center">MW</th>
                    <th class="text-center">TTH</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch timeslots for the department
                $timeslot_query = $conn->prepare("SELECT timeslot FROM timeslot WHERE dept_id = ? ORDER BY time_id");
                $timeslot_query->bind_param("i", $dept_id);
                $timeslot_query->execute();
                $timeslot_result = $timeslot_query->get_result();

                $times = [];
                while ($row = $timeslot_result->fetch_assoc()) {
                    $times[] = $row['timeslot'];
                }

                // Fetch all faculty names in advance
                $faculty_query = $conn->query("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty");
                $faculty_names = [];
                while ($row = $faculty_query->fetch_assoc()) {
                    $faculty_names[$row['id']] = $row['name'];
                }

                // Render schedule rows
                foreach ($times as $time) {
                    echo "<tr><td>" . htmlspecialchars($time) . "</td>";

                    foreach (['MW', 'TTH'] as $days) {
                        echo "<td>";
                        
                        $query = "SELECT course, subjects, faculty FROM loading WHERE timeslot = ? AND days = ?";
                        if ($selected_room) {
                            $query .= " AND room_name = ?";
                        }

                        $stmt = $conn->prepare($query);
                        if ($selected_room) {
                            $stmt->bind_param("sss", $time, $days, $selected_room);
                        } else {
                            $stmt->bind_param("ss", $time, $days);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $course = htmlspecialchars($row['course']);
                                $subject = htmlspecialchars($row['subjects']);
                                $faculty_id = $row['faculty'];
                                $faculty_name = $faculty_names[$faculty_id] ?? 'Unknown Faculty';

                                echo '<p>' . htmlspecialchars("$subject $course $faculty_name") . '</p>';
                            }
                        } else {
                            echo "&nbsp;"; // Empty cell placeholder
                        }

                        echo "</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
