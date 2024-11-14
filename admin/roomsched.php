<?php
// Start session and include database connection
session_start();
include('db_connect.php');

// Get the department ID from the session
$dept_id = $_SESSION['dept_id'];

// Get the room from the query parameter
$selected_room = isset($_GET['room']) ? $_GET['room'] : '';

// Check if the room parameter is empty
if (!$selected_room) {
    echo "No room selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Room Schedule</title>
    <link rel="stylesheet" href="path_to_your_css/bootstrap.min.css">
    <style>
        /* Add any specific styles for printing here */
        @media print {
            body * { visibility: hidden; }
            #print-content, #print-content * { visibility: visible; }
            #print-content { position: absolute; top: 0; left: 0; }
        }
    </style>
</head>
<body>

<div id="print-content" class="container mt-5">
    <h3 class="text-center">Room Schedule for <?php echo htmlspecialchars($selected_room); ?></h3>

    <!-- Monday/Wednesday Section -->
    <div class="card-body">
        <h4>Monday/Wednesday</h4>
        <table class="table table-bordered waffle no-grid">
            <thead>
                <tr>
                    <th class="text-center">Time</th>
                    <th class="text-center"><?php echo htmlspecialchars($selected_room); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $times = array();
                $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='MW' AND dept_id = '$dept_id' ORDER BY time_id;");
                while ($t = $timesdata->fetch_assoc()) {
                    $times[] = $t['timeslot'];
                }

                foreach ($times as $time) {
                    echo "<tr><td>" . htmlspecialchars($time) . "</td>";
                    
                    // Fetch data for the specific time and room
                    $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days='MW'";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ss', $time, $selected_room);
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
                        echo "<td></td>"; // No data for this time and room
                    }
                    
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Tuesday/Thursday Section -->
    <div class="card-body">
        <h4>Tuesday/Thursday</h4>
        <table class="table table-bordered waffle no-grid">
            <thead>
                <tr>
                    <th class="text-center">Time</th>
                    <th class="text-center"><?php echo htmlspecialchars($selected_room); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $times = array();
                $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='TTH' AND dept_id = '$dept_id' ORDER BY time_id;");
                while ($t = $timesdata->fetch_assoc()) {
                    $times[] = $t['timeslot'];
                }

                foreach ($times as $time) {
                    echo "<tr><td>" . htmlspecialchars($time) . "</td>";
                    
                    // Fetch data for the specific time and room
                    $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days='TTH'";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ss', $time, $selected_room);
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
                        echo "<td></td>"; // No data for this time and room
                    }
                    
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Friday/Saturday Section -->
    <div class="card-body">
        <h4>Friday/Saturday</h4>
        <table class="table table-bordered waffle no-grid">
            <thead>
                <tr>
                    <th class="text-center">Time</th>
                    <th class="text-center"><?php echo htmlspecialchars($selected_room); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $times = array();
                $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='FS' AND dept_id = '$dept_id' ORDER BY time_id;");
                while ($t = $timesdata->fetch_assoc()) {
                    $times[] = $t['timeslot'];
                }

                foreach ($times as $time) {
                    echo "<tr><td>" . htmlspecialchars($time) . "</td>";
                    
                    // Fetch data for the specific time and room
                    $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days='FS'";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ss', $time, $selected_room);
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
                        echo "<td></td>"; // No data for this time and room
                    }
                    
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Automatically open the print dialog when the page loads
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
