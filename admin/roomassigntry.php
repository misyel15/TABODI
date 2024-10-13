<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>
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
                            $roomsdata = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY room_id;");
                            while ($r = $roomsdata->fetch_assoc()) {
                                $rooms[] = $r['room_name'];
                                echo '<th class="text-center">' . $r['room_name'] . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $times = array();
                        $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='MW' AND dept_id = '$dept_id' ORDER BY time_id;");
                        while ($t = $timesdata->fetch_assoc()) {
                            $times[] = $t['timeslot'];
                        }

                        foreach ($times as $time) {
                            echo "<tr><td>$time</td>";
                            foreach ($rooms as $room) {
                                $query = "SELECT * FROM loading WHERE timeslot='$time' AND room_name='$room' AND days='MW' AND dept_id='$dept_id'";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    $course = $row['course'];
                                    $subject = $row['subjects'];
                                    $faculty = $row['faculty'];
                                    $load_id = $row['id'];
                                    $scheds = $subject . " " . $course;
                                    $faculty_name = $conn->query("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                                        FROM faculty 
                                        WHERE id = $faculty AND dept_id = $dept_id")
                                        ->fetch_assoc()['name'];
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
                        ?>
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
                    <button class="btn btn-primary btn-sm" id="new_schedule_tth" data-toggle="modal" data-target="#newScheduleModal">
                        <i class="fa fa-user-plus"></i> New Entry
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered waffle no-grid" id="insloadtabletth">
                    <thead>
                        <tr>
                            <th class="text-center">Time</th>
                            <?php
                            foreach ($rooms as $room) {
                                echo '<th class="text-center">' . $room . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $times = array();
                        $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='TTH' AND dept_id = '$dept_id' ORDER BY time_id;");
                        while ($t = $timesdata->fetch_assoc()) {
                            $times[] = $t['timeslot'];
                        }

                        foreach ($times as $time) {
                            echo "<tr><td>$time</td>";
                            foreach ($rooms as $room) {
                                $query = "SELECT * FROM loading WHERE timeslot='$time' AND room_name='$room' AND days='TTH' AND dept_id='$dept_id'";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    $course = $row['course'];
                                    $subject = $row['subjects'];
                                    $faculty = $row['faculty'];
                                    $load_id = $row['id'];
                                    $scheds = $subject . " " . $course;
                                    $faculty_name = $conn->query("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                                        FROM faculty 
                                        WHERE id = $faculty AND dept_id = $dept_id")
                                        ->fetch_assoc()['name'];
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
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <!-- ... (rest of the edit modal code remains the same) ... -->
</div>

<!-- New Entry Modal -->
<div class="modal fade" id="newScheduleModal" tabindex="-1" role="dialog" aria-labelledby="newScheduleModalLabel" aria-hidden="true">
    <!-- ... (rest of the new entry modal code remains the same) ... -->
</div>

<script>
$(document).ready(function() {
    // Edit load
    $('.edit_load').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'ajax.php?action=get_schedule',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                if(resp) {
                    resp = JSON.parse(resp);
                    $('#editForm [name="faculty"]').val(resp.faculty);
                    $('#editForm [name="semester"]').val(resp.semester);
                    $('#editForm [name="course"]').val(resp.course);
                    $('#editForm [name="yrsection"]').val(resp.yrsection);
                    $('#editForm [name="subject"]').val(resp.subject);
                    $('#editForm [name="room"]').val(resp.room);
                    $('#editForm [name="days"]').val(resp.days);
                    $('#editForm [name="timeslot_id"]').val(resp.timeslot_id);
                    $('#editModal').modal('show');
                }
            }
        });
    });

    // Delete load
    $('.delete_load').click(function() {
        var id = $(this).data('id');
        var scode = $(this).data('scode');
        if(confirm("Are you sure you want to delete this schedule?")) {
            $.ajax({
                url: 'ajax.php?action=delete_schedule',
                method: 'POST',
                data: {id: id, scode: scode},
                success: function(resp) {
                    if(resp == 1) {
                        alert("Schedule successfully deleted");
                        location.reload();
                    }
                }
            });
        }
    });

    // New schedule
    $('#newScheduleForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=save_schedule',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if(resp == 1) {
                    alert("New schedule successfully added");
                    location.reload();
                }
            }
        });
    });

    // Edit schedule
    $('#editForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=update_schedule',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if(resp == 1) {
                    alert("Schedule successfully updated");
                    location.reload();
                }
            }
        });
    });

    // Print functionality
    $('#print').click(function() {
        var nw = window.open("print_schedule.php?schedule=MW", "_blank", "height=600,width=800");
        setTimeout(function() {
            nw.print()
            setTimeout(function() {
                nw.close()
            }, 500)
        }, 1000);
    });

    $('#printtth').click(function() {
        var nw = window.open("print_schedule.php?schedule=TTH", "_blank", "height=600,width=800");
        setTimeout(function() {
            nw.print()
            setTimeout(function() {
                nw.close()
            }, 500)
        }, 1000);
    });
});

function populateYear(course) {
    // AJAX call to get years and sections for the selected course
    $.ajax({
        url: 'ajax.php?action=get_years_sections',
        method: 'POST',
        data: {course: course},
        success: function(resp) {
            $('#yrsection').html(resp);
        }
    });
}

function populateSubjects() {
    var course = $('#course').val();
    var yrsection = $('#yrsection').val();
    // AJAX call to get subjects for the selected course and year/section
    $.ajax({
        url: 'ajax.php?action=get_subjects',
        method: 'POST',
        data: {course: course, yrsection: yrsection},
        success: function(resp) {
            $('#subject').html(resp);
        }
    });
}
</script>
