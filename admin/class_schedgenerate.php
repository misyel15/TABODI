<?php include('db_connect.php'); ?>

<?php
function generateRow($conn, $secid, $semester){
    $content = '<tbody>';

    if(isset($secid) && isset($semester)){
        $i = 1;
        $loads = $conn->query("SELECT * FROM loading WHERE course='$secid' AND semester='$semester' ORDER BY timeslot_sid ASC");
        while($lrow = $loads->fetch_assoc()){
            $days = $lrow['days'];
            $timeslot = $lrow['timeslot'];
            $course = $lrow['course'];
            $subject_code = $lrow['subjects'];
            $room_id = $lrow['rooms'];
            $instid = $lrow['faculty'];

            // Fetch subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            while($srow = $subjects->fetch_assoc()){
                $description = $srow['description'];
                $units = $srow['total_units'];
            }

            // Fetch faculty details
            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id=".$instid);
            while($frow = $faculty->fetch_assoc()){
                $instname = $frow['name'];
            }

            // Fetch room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = ".$room_id);
            while($roomrow = $rooms->fetch_assoc()){
                $room_name = $roomrow['room_name'];
            }

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
        $i = 1;
        $loads = $conn->query("SELECT * FROM loading ORDER BY timeslot_sid ASC");
        while($lrow = $loads->fetch_assoc()){
            $days = $lrow['days'];
            $timeslot = $lrow['timeslot'];
            $subject_code = $lrow['subjects'];
            $room_id = $lrow['rooms'];
            $instid = $lrow['faculty'];

            // Fetch subject details
            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
            while($srow = $subjects->fetch_assoc()){
                $description = $srow['description'];
                $units = $srow['total_units'];
            }

            // Fetch faculty details
            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id=".$instid);
            while($frow = $faculty->fetch_assoc()){
                $instname = $frow['name'];
            }

            // Fetch room details
            $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = ".$room_id);
            while($roomrow = $rooms->fetch_assoc()){
                $room_name = $roomrow['room_name'];
            }

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
                height: 20%;
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
            <img src="assets/uploads/end.png" alt="Logo">
        </div>

        <div class="section-info">
            Class Section: <?php echo htmlspecialchars($secid); ?><br>
            Semester: <?php echo htmlspecialchars($semester); ?>
        </div>

        <?php echo $content; ?>
    </body>
    </html>
    <?php
}

// Call the function to display the print page
printPage($conn);
?>
