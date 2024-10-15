<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id'] ?? 0; // Get the department ID from the session, default to 0 if not set

// Function to handle opening the edit modal and populating it with data
function openEditModal($loadId) {
    global $conn;
    
    $query = "SELECT l.*, f.lastname, f.firstname, f.middlename 
              FROM loading l 
              JOIN faculty f ON l.faculty = f.id 
              WHERE l.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $loadId);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    
    if ($record) {
        $facultyName = $record['lastname'] . ', ' . $record['firstname'] . ' ' . $record['middlename'];
        echo json_encode([
            'status' => 'success',
            'data' => [
                'faculty' => $record['faculty'],
                'facultyName' => $facultyName,
                'semester' => $record['semester'],
                'course' => $record['course'],
                'yrsection' => $record['yrsection'],
                'subject' => $record['subjects'],
                'room' => $record['room_name'],
                'days' => $record['days'],
                'timeslot' => $record['timeslot'],
                'loadId' => $loadId
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Record not found.']);
    }
}

// Function to update a load entry
function updateLoad() {
    global $conn;
    
    $loadId = $_POST['load_id'];
    $faculty = $_POST['faculty'];
    $semester = $_POST['semester'];
    $course = $_POST['course'];
    $yrsection = $_POST['yrsection'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $days = $_POST['days'];
    $timeslot = $_POST['timeslot'];
    
    $query = "UPDATE loading 
              SET faculty = ?, semester = ?, course = ?, yrsection = ?, 
                  subjects = ?, room_name = ?, days = ?, timeslot = ? 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssssi", $faculty, $semester, $course, $yrsection, $subject, $room, $days, $timeslot, $loadId);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update record']);
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'edit_load':
            openEditModal($_GET['load_id']);
            exit;
        case 'update_load':
            updateLoad();
            exit;
    }
}

// Function to generate table rows
function generateTableRows($conn, $dept_id, $days) {
    $times = array();
    $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='$days' AND dept_id = '$dept_id' ORDER BY time_id");
    while ($t = $timesdata->fetch_assoc()) {
        $times[] = $t['timeslot'];
    }

    $rooms = array();
    $roomsdata = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY room_id");
    while ($r = $roomsdata->fetch_assoc()) {
        $rooms[] = $r['room_name'];
    }

    foreach ($times as $time) {
        echo "<tr><td>$time</td>";
        foreach ($rooms as $room) {
            $query = "SELECT * FROM loading WHERE timeslot='$time' AND room_name='$room' AND days='$days'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $course = $row['course'];
                $subject = $row['subjects'];
                $faculty = $row['faculty'];
                $load_id = $row['id'];
                $scheds = $subject . " " . $course;
                $faculty_name = $conn->query("SELECT concat(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id=$faculty")->fetch_assoc()['name'];
                $newSched = $scheds . " " . $faculty_name;
                echo '<td class="text-center content" data-id="' . $load_id . '" data-scode="' . $subject . '">' 
                    . $newSched 
                    . '<br>'
                    . '<span><button class="btn btn-sm btn-primary edit_load" type="button" data-id="' . $load_id . '" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i> Edit</button></span> '
                    . '<span><button class="btn btn-sm btn-danger delete_load" type="button" data-id="' . $load_id . '" data-scode="' . $subject . '"><i class="fa fa-trash-alt"></i> Delete</button></span>'
                    . '</td>';
            } else {
                echo "<td></td>";
            }
        }
        echo "</tr>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<div class="container-fluid" style="margin-top:100px; margin-left:-15px;">
    <div class="container-fluid mt-5">
        <!-- Table Panel for Monday/Wednesday -->
        <div class="card mb-4">
            <div class="card-header text-center">
                <h3>Monday/Wednesday</h3>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success btn-sm btn-flat mr-2" id="print">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-primary btn-sm" id="new_schedule_mw" data-toggle="modal" data-target="#newScheduleModal">
                        <i class="fa fa-user-plus"></i> New Entry
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered waffle no-grid" id="insloadtable">
                    <thead>
                        <tr>
                            <th class="text-center">Time</th>
                            <?php
                            $rooms = array();
                            $roomsdata = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY room_id");
                            while ($r = $roomsdata->fetch_assoc()) {
                                echo '<th class="text-center">' . $r['room_name'] . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php generateTableRows($conn, $dept_id, 'MW'); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Panel for Tuesday/Thursday -->
        <div class="card mb-4">
            <div class="card-header text-center">
                <h3>Tuesday/Thursday</h3>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success btn-sm btn-flat mr-2" id="printtth">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered waffle no-grid" id="insloadtable">
                    <thead>
                        <tr>
                            <th class="text-center">Time</th>
                            <?php
                            $rooms = array();
                            $roomsdata = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY room_id");
                            while ($r = $roomsdata->fetch_assoc()) {
                                echo '<th class="text-center">' . $r['room_name'] . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php generateTableRows($conn, $dept_id, 'TTH'); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Entry Modal -->
<div class="modal fade" id="newScheduleModal" tabindex="-1" role="dialog" aria-labelledby="newScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newScheduleModalLabel">New Schedule Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newScheduleForm">
                <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
                <div class="modal-body">
                    <!-- Form fields here -->
                    <!-- (Include all the form fields from your original code) -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Schedule Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editScheduleForm">
                <input type="hidden" id="edit_load_id" name="load_id">
                <div class="modal-body">
                    <!-- Form fields here -->
                    <!-- (Include all the form fields from your original code) -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle edit button click
    $('.edit_load').click(function() {
        var loadId = $(this).data('id');
        $.get('<?php echo $_SERVER['PHP_SELF']; ?>?action=edit_load&load_id=' + loadId, function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                $('#edit_load_id').val(data.data.loadId);
                $('#edit_faculty').val(data.data.faculty).trigger('change');
                $('#edit_semester').val(data.data.semester);
                $('#edit_course').val(data.data.course);
                $('#edit_yrsection').val(data.data.yrsection);
                $('#edit_subject').val(data.data.subject);
                $('#edit_room').val(data.data.room);
                $('#edit_days').val(data.data.days);
                $('#edit_timeslot').val(data.data.timeslot);
                $('#editModal').modal('show');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    // Handle form submission for editing
    $('#editScheduleForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo $_SERVER['PHP_SELF']; ?>?action=update_load',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#editModal').modal('hide');
                    Swal.fire('Success', response.message, 'success').then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while updating the record.', 'error');
            }
        });
    });

    // Add event listeners for other functionalities (e.g., new entry, delete, print)
});
</script>

</body>
</html>
