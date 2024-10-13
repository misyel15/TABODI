<?php 
session_start();
include('db_connect.php');
include 'includes/header.php';

// Check if department ID is set in session
if (!isset($_SESSION['dept_id'])) {
    die("Department ID not set.");
}

// Handle the AJAX request to fetch schedule data
if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
    $dept_id = $_SESSION['dept_id']; // Get department ID from session

    // Fetch schedule based on room_id and dept_id
    $stmt = $conn->prepare("SELECT * FROM loading WHERE rooms = ? AND dept_id = ? ORDER BY timeslot ASC");
    $stmt->bind_param("ii", $room_id, $dept_id);
    
    // Execute the statement
    if (!$stmt->execute()) {
        echo "Error executing query: " . htmlspecialchars($stmt->error);
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $output = '';
        while ($row = $result->fetch_assoc()) {
            $time = htmlspecialchars($row['timeslot']);
            $monday = htmlspecialchars($row['Monday'] ?? '');
            $tuesday = htmlspecialchars($row['Tuesday'] ?? '');
            $wednesday = htmlspecialchars($row['Wednesday'] ?? '');
            $thursday = htmlspecialchars($row['Thursday'] ?? '');
            $friday = htmlspecialchars($row['Friday'] ?? '');
            $saturday = htmlspecialchars($row['Saturday'] ?? '');

            // Append rows with the sub-descriptions for each day
            $output .= '<tr>
                <td class="text-center">' . $time . '</td>
                <td class="text-center">' . $monday . '</td>
                <td class="text-center">' . $tuesday . '</td>
                <td class="text-center">' . $wednesday . '</td>
                <td class="text-center">' . $thursday . '</td>
                <td class="text-center">' . $friday . '</td>
                <td class="text-center">' . $saturday . '</td>
            </tr>';
        }
        echo $output;
    } else {
        echo '<tr><td colspan="7" class="text-center">No schedule found.</td></tr>';
    }
    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule Load</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media (max-width: 768px) {
            .card-header {
                text-align: center;
            }
            .table thead th, .table td {
                font-size: 12px;
            }
            .modal-dialog {
                max-width: 90%;
                margin: 1.75rem auto;
            }
        }
        td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="margin-top:100px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <center><h3>Room Schedule's Load</h3></center>
                </div>
                <div class="card-body">
                    <div class="row">
                        <label for="room_name" class="control-label col-md-2 offset-md-2">View Loads of:</label>
                        <div class="col-md-4">
                            <select name="room_name" id="room_name" class="custom-select select2" onchange="fetchRoomSchedule(this.value)">
                                <option value="">Select Room</option>
                                <?php
                                // Prepare the SQL statement to fetch room names based on dept_id
                                $stmt = $conn->prepare("SELECT * FROM roomlist WHERE dept_id = ? ORDER BY id ASC");
                                $stmt->bind_param("i", $_SESSION['dept_id']); // Assuming dept_id is an integer
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . ucwords(htmlspecialchars($row['room_name'])) . '</option>';
                                    }
                                } else {
                                    echo 'Error: ' . htmlspecialchars($conn->error);
                                }

                                $stmt->close();
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="insloadtable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="100px">Time</th>
                                    <th class="text-center">Monday</th>
                                    <th class="text-center">Tuesday</th>
                                    <th class="text-center">Wednesday</th>
                                    <th class="text-center">Thursday</th>
                                    <th class="text-center">Friday</th>
                                    <th class="text-center">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Schedule data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
function fetchRoomSchedule(roomId) {
    if(roomId) {
        $.ajax({
            url: '', // Current page
            type: 'POST',
            data: { room_id: roomId },
            beforeSend: function() {
                $('#insloadtable tbody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>'); // Show loading message
            },
            success: function(response) {
                console.log(response); // Log the response for debugging
                $('#insloadtable tbody').html(response); // Update table body with response data
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
                $('#insloadtable tbody').html('<tr><td colspan="7" class="text-center">Error loading schedule.</td></tr>'); // Show error message
            }
        });
    } else {
        $('#insloadtable tbody').html(''); // Clear table if no room is selected
    }
}
</script>
</body>
</html>
