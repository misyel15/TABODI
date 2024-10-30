<?php
session_start();
include('db_connect.php');

$dept_id = $_SESSION['dept_id'];

// Function to generate table content for the specific department
function generateTableContent($conn, $dept_id) {
    $content = '';

    $loads = $conn->prepare("
        SELECT faculty, 
               GROUP_CONCAT(DISTINCT sub_description ORDER BY sub_description ASC SEPARATOR ', ') AS subject, 
               SUM(total_units) AS totunits 
        FROM loading 
        WHERE dept_id = ? 
        GROUP BY faculty
    ");
    $loads->bind_param("i", $dept_id);
    $loads->execute();
    $result = $loads->get_result();

    if ($result && $result->num_rows > 0) {
        while ($lrow = $result->fetch_assoc()) {
            $subjects = $lrow['subject'];
            $faculty_id = $lrow['faculty'];
            $sumloads = $lrow['totunits'];
            $sumotherl = 0; // Assign as per your logic or fetch from the database if applicable
            $sumoverl = 0; // Assign as per your logic or fetch from the database if applicable
            $totalloads = $sumloads + $sumotherl + $sumoverl;

            $faculty = $conn->prepare("
                SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                FROM faculty 
                WHERE id = ? AND dept_id = ?
            ");
            $faculty->bind_param("ii", $faculty_id, $dept_id);
            $faculty->execute();
            $facultyResult = $faculty->get_result();

            $instname = ($facultyResult && $facultyResult->num_rows > 0) ? $facultyResult->fetch_assoc()['name'] : 'Unknown Faculty';

            $content .= '<tr>
                            <td width="150px" align="center">' . htmlspecialchars($instname) . '</td>
                            <td width="200px" align="center">' . htmlspecialchars($subjects) . '</td>
                            <td width="40px" align="center">' . htmlspecialchars($sumloads) . '</td>
                            <td width="40px" align="center">' . htmlspecialchars($sumotherl) . '</td>
                            <td width="40px" align="center">' . htmlspecialchars($sumoverl) . '</td>
                            <td width="40px" align="center">' . htmlspecialchars($totalloads) . '</td>
                        </tr>';
        }
    } else {
        $content .= '<tr><td colspan="6" align="center">No faculty load data available.</td></tr>';
    }

    return $content;
}

// Function to determine the header image based on the department ID
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
            return "assets/uploads/default_header.png"; // Fallback header
    }
}

// Function to print the page
function printPage($conn, $dept_id) {
    $content = generateTableContent($conn, $dept_id);
    $headerImage = getHeaderImage($dept_id);
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Printable Faculty Load</title>
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
                height: auto; /* Maintain aspect ratio */
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="<?php echo htmlspecialchars($headerImage); ?>" alt="Department Header">
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
    </body>
    </html>
    <?php
}

// Call the function to display the print page
printPage($conn, $dept_id);
?>
