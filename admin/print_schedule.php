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
    echo "<h4 class='text-center'>$day_type </h4>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead><tr><th class='text-center'>Time</th><th class='text-center'>$selected_room</th></tr></thead><tbody>";

    // Fetch time slots for the specific day type
    $times = [];
    $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='$day_type' AND dept_id = '$dept_id' ORDER BY time_id");
    while ($t = $timesdata->fetch_assoc()) {
        $times[] = $t['timeslot'];
    }

    // Display each time slot
    foreach ($times as $time) {
        echo "<tr><td class='text-center'>" . htmlspecialchars($time) . "</td>";

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

                echo "<td class='text-center'>" . htmlspecialchars("$subject $course - $faculty_name") . "</td>";
            }
        } else {
            echo "<td class='text-center'>No class scheduled</td>";
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
            table { width: 100%; }
        }
        .table {
            margin: 20px auto;
            width: 90%;
        }
      
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2, h4 {
        color: #333;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .text-center {
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    /* Table Styles */
    .table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        background-color: #fff;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        color: #333;
        font-size: 18px;
        font-weight: bold;
    }

    .table td {
        font-size: 16px;
        color: #555;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    /* Print Styles */
    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none;
        }

        .container {
            width: 100%;
            box-shadow: none;
            padding: 10px;
        }

        h2, h4 {
            color: #000;
        }

        .table th, .table td {
            padding: 8px;
            font-size: 14px;
        }

        .table {
            margin-top: 10px;
        }

        .btn-primary {
            display: none;
        }
    }

    /* Custom Text Styling */
    .table td {
        word-wrap: break-word;
        hyphens: auto;
    }

    .text-center h2 {
        font-size: 24px;
        color: #007bff;
    }

    .table td {
        text-align: left;
    }
</style>

    </style>
</head>
<body onload="window.print()">
 <script>
            // Detect when the print dialog is closed
            window.onafterprint = function() {
                // Redirect back if the print dialog was canceled
                window.history.back();
            };
        </script>
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
