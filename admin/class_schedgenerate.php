<?php
session_start();
include('db_connect.php');

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session

function getHeaderImage($dept_id) {
    switch ($dept_id) {
        case 4444:
            return "assets/uploads/end.png";
        case 5858:
            return "assets/uploads/EDU.png"; // Fixed ID from 5858 to 5555
        case 3333:
            return "assets/uploads/HM.png";
        case 12345:
            return "assets/uploads/BA.png";
        default:
            return 'assets/uploads/default_header.png'; // Default header image
    }
}

function generateRow($conn, $secid, $semester) {
    $content = '<tbody>';
    $totalUnits = 0; // Initialize total units variable

    if (isset($secid) && isset($semester)) {
        $loads = $conn->query("SELECT * FROM loading WHERE course='$secid' AND semester='$semester' ORDER BY timeslot_sid ASC");
        while ($lrow = $loads->fetch_assoc()) {
            $days = $lrow['days'];
            $timeslot = $lrow['timeslot'];
            $course = $lrow['course'];
            $subject_code = $lrow['subjects'];
            $room_id = $lrow['rooms'];
            $instid = $lrow['faculty'];

            // Fetch subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            $srow = $subjects->fetch_assoc(); // Directly fetch one row since subject_code should be unique
            $description = $srow['description'];
            $units = $srow['total_units'];

            // Add units to total units
            $totalUnits += $units;

            // Fetch faculty details
            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id=".$instid);
            $frow = $faculty->fetch_assoc(); // Directly fetch one row since instid should be unique
            $instname = $frow['name'];

            // Fetch room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = ".$room_id);
            $roomrow = $rooms->fetch_assoc(); // Directly fetch one row since room_id should be unique
            $room_name = $roomrow['room_name'];

            // Append row content
            $content .= '<tr>
                <td width="100px" align="center">'.$timeslot.'</td>
                <td width="40px" align="center">'.$days.'</td>
                <td align="center">'.$subject_code.'</td>
                <td width="130px" align="center">'.$description.'</td>
                <td width="40px" align="center">'.$units.'</td>
                <td align="center">'.$room_name.'</td>
                <td align="center">'.$instname.'</td>
            </tr>';
        }
    } else {
        // Fetch all records if no section or semester is selected
        $loads = $conn->query("SELECT * FROM loading ORDER BY timeslot_sid ASC");
        while ($lrow = $loads->fetch_assoc()) {
            $days = $lrow['days'];
            $timeslot = $lrow['timeslot'];
            $subject_code = $lrow['subjects'];
            $room_id = $lrow['rooms'];
            $instid = $lrow['faculty'];

            // Fetch subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            $srow = $subjects->fetch_assoc();
            $description = $srow['description'];
            $units = $srow['total_units'];

            // Add units to total units
            $totalUnits += $units;

            // Fetch faculty details
            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id=".$instid);
            $frow = $faculty->fetch_assoc();
            $instname = $frow['name'];

            // Fetch room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = ".$room_id);
            $roomrow = $rooms->fetch_assoc();
            $room_name = $roomrow['room_name'];

            // Append row content
            $content .= '<tr>
                <td align="center">'.$timeslot.'</td>
                <td width="40px" align="center">'.$days.'</td>
                <td align="center">'.$subject_code.'</td>
                <td width="130px" align="center">'.$description.'</td>
                <td width="40px" align="center">'.$units.'</td>
                <td align="center">'.$room_name.'</td>
                <td align="center">'.$instname.'</td>
            </tr>';
        }
    }

    // Add the total units row
    $content .= '<tr>
        <td colspan="4" align="right"><strong>Total Units:</strong></td>
        <td align="center"><strong>'.$totalUnits.'</strong></td>
        <td colspan="2"></td>
    </tr>';

    $content .= '</tbody>';
    return $content;
}

function generateTableContent($conn) {
    $secid = $_GET['secid'] ?? null;
    $semester = $_GET['semester'] ?? null;
    $tableHeader = '<thead>
        <tr>
            <th>Timeslot</th>
            <th>Days</th>
            <th>Subject Code</th>
            <th>Description</th>
            <th>Units</th>
            <th>Room</th>
            <th>Instructor</th>
        </tr>
    </thead>';

    $tableContent = generateRow($conn, $secid, $semester);
    
    return '<table border="1">'.$tableHeader.$tableContent.'</table>';
}

function printPage($conn) {
    $secid = $_GET['secid'] ?? 'N/A'; // Get section id
    $semester = $_GET['semester'] ?? 'N/A'; // Get semester

    // Get the header image based on the dept_id from the session
    $dept_id = $_SESSION['dept_id'];
    $headerImage = getHeaderImage($dept_id);

    $content = generateTableContent($conn);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Class Schedule</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                text-align: center;
                font-size: 14px; /* Set the base font size */
            }
            .header {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 0 auto;
                font-size: 12px; /* Set the table font size */
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
                height: auto; /* Changed to auto for proper aspect ratio */
            }
            .section-info {
                font-size: 18px; /* Font size for section and semester info */
                margin-bottom: 10px;
                font-weight: bold;
            }
            .table-header {
                font-size: 16px; /* Larger font size for the table header */
            }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header">
            <img src="<?php echo htmlspecialchars($headerImage); ?>" alt="Header Image">
        </div>

        <div class="section-info">
            Class Section: <?php echo htmlspecialchars($secid); ?><br>
            Semester: <?php echo htmlspecialchars($semester); ?>
        </div>

        <?php echo $content; ?>
        <script>
            // Detect when the print dialog is closed
            window.onafterprint = function() {
                // Redirect back if the print dialog was canceled
                window.history.back();
            };
        </script>
    </body>
    </html>
    <?php
}

// Call the function to display the print page
printPage($conn);
?>
