<?php
include('db_connect.php');

// Function to generate table rows based on the faculty id
function generateTableContent($conn, $id) {
    $content = '';
    
    if (isset($id)) {
        $i = 1;
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
            $instname = $frow['name'];

            // Subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            $srow = $subjects->fetch_assoc();
            $description = $srow['description'];
            $units = $srow['total_units'];
            $lec_units = $srow['Lec_Units'];
            $lab_units = $srow['Lab_Units'];
            $hours = $srow['hours'];
            $sumh += $hours;
            $sumtu += $units;

            // Room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE id = " . $room_id);
            $roomrow = $rooms->fetch_assoc();
            $room_name = $roomrow['room_name'];

            // Generate the row content
            $content .= '<tr>
                <td width="50px" align="center">' . $subject_code . '</td>
                <td width="100px" align="center">' . $description . '</td>
                <td width="50px" align="center">' . $days . '</td>
                <td width="50px" align="center">' . $timeslot . '</td>
                <td width="50px" align="center">' . $course . '</td>
                <td width="80px" align="center">' . $lec_units . '</td>
                <td width="50px" align="center">' . $lab_units . '</td>
                <td width="50px" align="center">' . $units . '</td>
                <td width="50px" align="center">' . $hours . '</td>
            </tr>';
        }

        // Add total units and hours row
        $content .= '<tr style="height: 20px">
            <td class="s4"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s10 softmerge">
                <div class="softmerge-inner" style="width:298px;left:-1px">
                    <span style="font-weight:bold; font-size:10px;">Total Number of Units/Hours (Basic)</span>
                </div>
            </td>
            <td class="s11"></td>
            <td class="text-center" align="center">' . $sumtu . '</td>
            <td class="text-center" align="center">' . $sumh . '</td>
        </tr>';
    }

    $content .= '</tbody>';
    return $content;
}

$id = $_GET['id']; // Get faculty ID from URL parameter

// Function to print the page content
function printPage($conn, $id) {
    // Fetch the instructor's name
    $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=" . $id);
    $frow = $faculty->fetch_assoc();
    $instname = $frow['name'];

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
                height: 20%;
            }
            .instname {
                font-weight: bold;
                font-size: 24px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header">
            <img src="assets/uploads/end.png" alt="Logo">
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
    </body>
    </html>
    <?php
}

// Call the function to display the print page
printPage($conn, $id);
?>
