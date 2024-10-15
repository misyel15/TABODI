<?php
session_start();
include('db_connect.php');


// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session

// Function to generate table content based on faculty loads
function generateTableContent($conn, $dept_id) {
    $content = '';

    $sumloads = 0;
    $sumotherl = 0;
    $sumoverl = 0;
    $totalloads = 0;
    $instname = '';

    // Query to get the faculty loads for a specific department
    $loads = $conn->query("
        SELECT faculty, GROUP_CONCAT(DISTINCT sub_description ORDER BY sub_description ASC SEPARATOR ', ') AS subject, 
               SUM(total_units) AS totunits 
        FROM loading 
        WHERE dept_id = '$dept_id' 
        GROUP BY faculty
    ");

    // Check if the query is successful
    if ($loads) {
        while ($lrow = $loads->fetch_assoc()) {
            $subjects = $lrow['subject'];
            $faculty_id = $lrow['faculty'];
            $sumloads = $lrow['totunits'];
            $totalloads = $sumloads + $sumotherl; // Total loads is the sum of sumloads and sumotherl (assuming sumotherl is calculated elsewhere)

            // Fetch faculty details from the selected department
            $faculty = $conn->query("
                SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                FROM faculty 
                WHERE id='$faculty_id' AND dept_id='$dept_id' 
                ORDER BY CONCAT(lastname, ', ', firstname, ' ', middlename) ASC
            ");

            // Check if faculty query returns any rows
            if ($faculty && $faculty->num_rows > 0) {
                $frow = $faculty->fetch_assoc();
                $instname = $frow['name'];
            } else {
                // If no faculty details are found, display a placeholder name
                $instname = 'Unknown Faculty';
            }

            // Add rows to content
            $content .= '<tr>
                            <td width="150px" align="center">' . $instname . '</td>
                            <td width="200px" align="center">' . $subjects . '</td>
                            <td width="40px" align="center">' . $sumloads . '</td>
                            <td width="40px" align="center">' . $sumotherl . '</td>
                            <td width="40px" align="center">' . $sumoverl . '</td>
                            <td width="40px" align="center">' . $totalloads . '</td>
                        </tr>';
        }
    } else {
        // If the initial loads query fails, return a message
        $content .= '<tr><td colspan="6" align="center">No faculty load data available.</td></tr>';
    }

    $content .= '</tbody>'; // Close tbody tag
    return $content; // Return the generated table content
}

// Function to print the page with the generated content
function printPage($conn, $dept_id) {
    $content = generateTableContent($conn, $dept_id); // Generate table content based on department ID
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Printable Faculty Load</title>
      
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
            .buttons {
                margin-top: 20px;
            }
            .close-btn {
                padding: 10px 20px;
                background-color: #f44336;
                color: white;
                border: none;
                cursor: pointer;
                font-size: 16px;
            }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header">
            <img src="assets/uploads/end.png" alt="Logo">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Subjects</th>
                    <th>Total Loads</th>
                    <th>Other Loads</th>
                    <th>Overload</th>
                    <th>Total All Loads</th>
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

// Call the function to print the page
printPage($conn, $dept_id);
?>
