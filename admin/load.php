<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you store the department ID in the session during login
if (isset($_SESSION['dept_id'])) {
    $dept_id = $_SESSION['dept_id']; // Get the department ID from the session
} else {
    die('Department ID is not set.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor's Load</title>
    <style>
        @media (max-width: 768px) {
            .card-header {
                text-align: center;
            }
            .table thead th {
                font-size: 12px;
            }
            .table td {
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
    <!-- Table Panel -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php
                    if (isset($_GET['id'])) {
                        $fid = $_GET['id'];
                        
                        // Prepare query for faculty name
                        $stmt = $conn->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id = ?");
                        if ($stmt) {
                            $stmt->bind_param("ii", $fid,); // Bind both fid and dept_id as integers
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result && $result->num_rows > 0) {
                                while ($frow = $result->fetch_assoc()) {
                                    $instname = $frow['name'];
                                }
                                echo '<b>Instructor\'s Load of ' . htmlspecialchars($instname) . '</b>';
                                echo '<button type="button" class="btn btn-success btn-sm float-right" id="print" data-id="' . htmlspecialchars($fid) . '"><i class="fas fa-print"></i> Print</button>';
                            } else {
                                echo 'No result for this faculty. Query Error: ' . $conn->error;
                            }
                            $stmt->close();
                        } else {
                            echo 'Query preparation failed: ' . $conn->error;
                        }
                    } else {
                        echo '<center><h3>Instructor\'s Load</h3></center>';
                    }
                    ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <label for="" class="control-label col-md-2 offset-md-2">View Loads of:</label>
                        <div class="col-md-4">
                            <select name="faculty_id" id="faculty_id" class="custom-select select2">
                                <option value=""></option>
                                <?php
                                $stmt = $conn->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty ORDER BY CONCAT(lastname, ', ', firstname, ' ', middlename) ASC");
                                if ($stmt) {
                                    $stmt->bind_param("i", $id); // Bind dept_id as integer
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($row['id']) . '"' . (isset($_GET['id']) && $_GET['id'] == $row['id'] ? ' selected' : '') . '>' . ucwords(htmlspecialchars($row['name'])) . '</option>';
                                        }
                                    } else {
                                        echo 'Error: ' . $conn->error;
                                    }
                                    $stmt->close();
                                } else {
                                    echo 'Query preparation failed: ' . $conn->error;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="insloadtable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="100px">Code</th>
                                    <th class="text-center">Descriptive Title</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Time</th>
                                    <th class="text-center">Section</th>
                                    <th class="text-center">Units (lec)</th>
                                    <th class="text-center">Units (lab)</th>
                                    <th class="text-center">Total Units</th>
                                    <th class="text-center">Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['id'])) {
                                    $faculty_id = $_GET['id'];
                                    $stmt = $conn->prepare("SELECT * FROM loading WHERE faculty = ? ORDER BY timeslot_sid ASC");
                                    if ($stmt) {
                                        $stmt->bind_param("ii", $faculty_id, ); // Bind faculty_id and dept_id as integers
                                        $stmt->execute();
                                        $loads = $stmt->get_result();
                                        if ($loads && $loads->num_rows > 0) {
                                            $sumtu = 0;
                                            $sumh = 0;
                                            while ($lrow = $loads->fetch_assoc()) {
                                                // Process rows as before
                                            }
                                        } else {
                                            echo '<tr><td colspan="9" class="text-center"><div class="alert alert-warning" role="alert">No scheduled loads found for this instructor.</div></td></tr>';
                                        }
                                        $stmt->close();
                                    } else {
                                        echo 'Query preparation failed: ' . $conn->error;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
