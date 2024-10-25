<?php
include('db_connect.php');

function generateTableContent($conn) {
    $content = '<h1>Monday/Wednesday</h1>
    <table border="0.5" cellspacing="0" cellpadding="3" class="table table-bordered waffle no-grid" id="insloadtable">
        <thead>
            <tr>
                <th class="text-center">Time</th>';

    // Get room names
    $rooms = [];
    $roomsResult = $conn->query("SELECT room_name FROM roomlist ORDER BY room_id");
    while ($room = $roomsResult->fetch_assoc()) {
        $rooms[] = $room['room_name'];
    }

    // Get time slots
    $times = [];
    $timesResult = $conn->query("SELECT timeslot FROM timeslot WHERE schedule='FS' ORDER BY time_id");
    while ($time = $timesResult->fetch_assoc()) {
        $times[] = $time['timeslot'];
    }

    // Add room headers to the table
    foreach ($rooms as $room) {
        $content .= '<th class="text-center">' . htmlspecialchars($room) . '</th>';
    }
    $content .= '</tr></thead><tbody>';

    // Add time slots and room assignments
    foreach ($times as $time) {
        $content .= '<tr><td>' . htmlspecialchars($time) . '</td>';
        foreach ($rooms as $room) {
            $query = "SELECT * FROM loading WHERE timeslot='$time' AND room_name='$room' AND days='MW'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $course = $row['course'];
                $subject = $row['subjects'];
                $faculty = $row['faculty'];
                $load_id = $row['id'];
                $scheds = $subject . " " . $course;

                $facultyQuery = "SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=$faculty";
                $facultyData = $conn->query($facultyQuery);
                $instname = ($facultyData->num_rows > 0) ? $facultyData->fetch_assoc()['name'] : '';
                $content .= '<td class="text-center" data-id="' . $load_id . '" data-scode="' . $subject . '">' . htmlspecialchars($scheds . " " . $instname) . '</td>';
            } else {
                $content .= '<td></td>';
            }
        }
        $content .= '</tr>';
    }
    $content .= '</tbody></table>';

    return $content;
}

function printPage($conn) {
    $content = generateTableContent($conn);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Printable Table</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                text-align: center;
            }
            .header {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
            }
            .header i {
                margin-right: 10px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 0 auto;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }
            th {
                background-color: #f2f2f2;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #e2e2e2;
            }
            .header img {
            width: 100%;
            height: 20%;
        }
        </style>
    </head>
    <body onload="window.print()">
    <div class="header">
  <img src="assets/uploads/end.png"  >
</div>

   <script>
            // Print the page and handle cancellation
            window.onload = function() {
                window.print();
            };

            // Detect when the print dialog is closed
            window.onafterprint = function() {
                // Redirect back if the print dialog was canceled
                window.history.back();
            };
        </script>      
        <?php echo $content; ?>
    </body>
    </html>
    <?php
}

// Call the function to display the print page
printPage($conn);
?>
