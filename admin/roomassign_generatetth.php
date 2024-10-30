<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connect.php');

// Check if department ID is set in the session
if (!isset($_SESSION['dept_id'])) {
    die("Department ID is not set in the session.");
}

$dept_id = $_SESSION['dept_id'];

// Function to generate table content for the specific department
function generateTableContent($conn, $dept_id) {
    $content = '<h4>TUESDAY/THURSDAY </h4>
    <table border="0.5" cellspacing="0" cellpadding="3" class="table table-bordered waffle no-grid" id="insloadtable">
        <thead>
            <tr>
                <th class="text-center">Time</th>';

    // Fetch room names for the department
    $rooms = [];
    $roomsResult = $conn->prepare("SELECT room_name FROM roomlist WHERE dept_id = ? ORDER BY room_id");
    $roomsResult->bind_param("i", $dept_id);
    $roomsResult->execute();
    $result = $roomsResult->get_result();
    while ($room = $result->fetch_assoc()) {
        $rooms[] = $room['room_name'];
    }

    // Fetch time slots for the department
    $times = [];
    $timesResult = $conn->prepare("SELECT timeslot FROM timeslot WHERE schedule='TTH' AND dept_id = ? ORDER BY time_id");
    $timesResult->bind_param("i", $dept_id);
    $timesResult->execute();
    $result = $timesResult->get_result();
    while ($time = $result->fetch_assoc()) {
        $times[] = $time['timeslot'];
    }

    // Add room headers to the table
    foreach ($rooms as $room) {
        $content .= '<th class="text-center">' . htmlspecialchars($room) . '</th>';
    }
    $content .= '</tr></thead><tbody>';

    // Populate the table with time slots and room assignments
    foreach ($times as $time) {
        $content .= '<tr><td>' . htmlspecialchars($time) . '</td>';
        foreach ($rooms as $room) {
            $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days='TTH' AND dept_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $time, $room, $dept_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $course = $row['course'];
                $subject = $row['subjects'];
                $faculty = $row['faculty'];
                $load_id = $row['id'];
                $scheds = $subject . " " . $course;

                // Fetch faculty name
                $facultyQuery = "SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                FROM faculty 
                WHERE id = ? AND dept_id = ?";
                $facultyStmt = $conn->prepare($facultyQuery);
                $facultyStmt->bind_param("ii", $faculty, $dept_id);
                $facultyStmt->execute();
                $facultyData = $facultyStmt->get_result();
                $instname = ($facultyData->num_rows > 0) ? $facultyData->fetch_assoc()['name'] : '';
                $content .= '<td class="text-center" data-id="' . $load_id . '" data-scode="' . $subject . '">' . htmlspecialchars($scheds . " " . $instname) . '</td>';
            } else {
                $content .= '<td></td>'; // Empty cell for no assignment
            }
        }
        $content .= '</tr>';
    }
    $content .= '</tbody></table>';

    return $content;
}

// Function to print the page with a specific department's data
function printPage($conn, $dept_id) {
    // Determine the header image based on the department ID
    switch ($dept_id) {
        case 4444:
            $headerImage = "assets/uploads/end.png";
            break;
        case 5858:
            $headerImage = "assets/uploads/EDU.png";
            break;
        case 3333:
            $headerImage = "assets/uploads/HM.jpg";
            break;
        case 12345:
            $headerImage = "assets/uploads/BA.png";
            break;
        default:
            $headerImage = "assets/uploads/default_header.png"; // Fallback to default header
            break;
    }

    // Check if the image file exists; if not, use a default image
    if (!file_exists($headerImage)) {
        $headerImage = "assets/uploads/default_header.png"; // Fallback if specific image doesn't exist
    }

    // Generate content for the table
    $content = generateTableContent($conn, $dept_id);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Printable Schedule</title>
        <link rel="icon" href="assets/uploads/mcclogo.jpg" type="image/jpg">
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
            .header img {
                width: 100%; /* Adjust width as necessary */
                height: auto; /* Maintain aspect ratio */
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
        </style>
    </head>
    <body onload="window.print()">
    <div class="header">
        <img src="<?php echo htmlspecialchars($headerImage); ?>" alt="Department Header">
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
printPage($conn, $dept_id);
?>
