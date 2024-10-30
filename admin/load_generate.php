<?php
session_start();
include('db_connect.php');

// Get the department ID from the session
$dept_id = isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : null; // Assuming dept_id is set during login

// Function to generate table rows based on the faculty id
function generateTableContent($conn, $id) {
    $content = '';

    if (isset($id)) {
        $sumtu = 0;
        $sumh = 0;
        $loads = $conn->query("SELECT * FROM loading WHERE faculty='$id' ORDER BY timeslot_sid ASC");

        while ($lrow = $loads->fetch_assoc()) {
            $days = $lrow['days'];
            $timeslot = $lrow['timeslot'];
            $course = $lrow['course'];
            $subject_code = $lrow['subjects'];
            $room_id = $lrow['rooms'];
            $fid = $lrow['faculty'];

            // Faculty details
            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=" . $fid);
            $frow = $faculty->fetch_assoc();
            $instname = $frow ? $frow['name'] : "Unknown Instructor"; // Fallback if not found

            // Subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            $srow = $subjects->fetch_assoc();
            $description = $srow ? $srow['description'] : "Unknown Subject";
            $units = $srow ? $srow['total_units'] : 0;
            $lec_units = $srow ? $srow['Lec_Units'] : 0;
            $lab_units = $srow ? $srow['Lab_Units'] : 0;
            $hours = $srow ? $srow['hours'] : 0;

            $sumh += $hours;
            $sumtu += $units;

            // Room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE id = " . $room_id);
            $roomrow = $rooms->fetch_assoc();
            $room_name = $roomrow ? $roomrow['room_name'] : "Unknown Room";

            // Generate the row content
            $content .= '<tr>
                <td align="center">' . htmlspecialchars($subject_code) . '</td>
                <td align="center">' . htmlspecialchars($description) . '</td>
                <td align="center">' . htmlspecialchars($days) . '</td>
                <td align="center">' . htmlspecialchars($timeslot) . '</td>
                <td align="center">' . htmlspecialchars($course) . '</td>
                <td align="center">' . htmlspecialchars($lec_units) . '</td>
                <td align="center">' . htmlspecialchars($lab_units) . '</td>
                <td align="center">' . htmlspecialchars($units) . '</td>
                <td align="center">' . htmlspecialchars($hours) . '</td>
            </tr>';
        }

        // Add total units and hours row
        $content .= '<tr>
            <td colspan="7" style="text-align:right;"><strong>Total Number of Units/Hours (Basic)</strong></td>
            <td align="center">' . $sumtu . '</td>
            <td align="center">' . $sumh . '</td>
        </tr>';
    }

    return $content;
}

// Function to determine the header image based on department ID
function getHeaderImage($dept_id) {
    // Determine the header image based on the department ID
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
            return "assets/uploads/default_header.png"; // Fallback to default header
    }
}

// Function to print the page content
function printPage($conn, $id, $dept_id) {
    // Fetch the instructor's name
    $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=" . $id);

    // Check if the query returned a result
    if ($faculty && $faculty->num_rows > 0) {
        $frow = $faculty->fetch_assoc();
        $instname = $frow['name'];
    } else {
        // Handle the error if no faculty is found
        $instname = "Unknown Instructor"; // Default value or handle as needed
    }

    // Get the header image based on the faculty's department
    $headerImage = getHeaderImage($dept_id); // Pass dept_id here

    // Generate the table content
    $content = generateTableContent($conn, $id);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Instructor's Load</title>
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
                height: auto;
            }
            .instname {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header">
            <img src="<?php echo $headerImage; ?>" alt="Department Logo">
        </div>

        <div class="instname">
            Instructor's Load: <?php echo htmlspecialchars($instname); ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Days</th>
                    <th>Timeslot</th>
                    <th>Course</th>
                    <th>Lec Units</th>
                    <th>Lab Units</th>
                    <th>Total Units</th>
                    <th>Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $content; ?>
            </tbody>
        </table>

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

// Get the faculty ID from the URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Call the function to display the print page
if ($id) {
    printPage($conn, $id, $dept_id); // Pass dept_id to printPage
} else {
    echo "No faculty ID provided.";
}
?>
