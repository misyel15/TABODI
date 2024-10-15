<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>
<div class="container-fluid" style="margin-top:100px;">
    <!-- Table Panel -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <?php
                    if (isset($_GET['secid']) && isset($_GET['semester'])) {
                        $secid = $conn->real_escape_string($_GET['secid']);
                        $semester = $conn->real_escape_string($_GET['semester']);
                        echo '<b>Class Schedule of '.$secid.' | '.$semester.'</b>';
                        echo '<button type="button" class="btn btn-success btn-sm float-right" id="print" data-secid="'.$secid.'" data-semester="'.$semester.'"><span class="glyphicon glyphicon-print"></span><i class="fas fa-print"></i> Print</button>';
                    } else {
                        echo '<center><h3>Class Schedule</h3></center>';
                    }
                ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <label for="sec_id" class="control-label col-md-2 offset-md-2">View Class Schedule of:</label>
                    <div class="col-md-4">
                        <select name="sec_id" id="sec_id" class="custom-select select2">
                            <option value=""></option>
                            <?php 
                                // Get sections based on dept_id
                                $sections = $conn->query("SELECT * FROM section WHERE dept_id = '$dept_id' ORDER BY year ASC");
                                if ($sections) {
                                    while ($row = $sections->fetch_array()):
                            ?>
                            <option value="<?php echo htmlspecialchars($row['year'].$row['section']); ?>" <?php echo isset($_GET['secid']) && $_GET['secid'] == $row['year'].$row['section'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['year'].$row['section']); ?></option>
                            <?php 
                                    endwhile;
                                } else {
                                    echo '<option value="">Error: '.$conn->error.'</option>';
                                }
                            ?>
                        </select>
                        <select name="semester" id="semester" class="form-control">
                            <option value="0" disabled selected>Select Semester</option>
                            <?php 
                            $semesters = $conn->query("SELECT * FROM semester");
                                if ($semesters) {
                                    while ($row = $semesters->fetch_array()):
                                        $semester = htmlspecialchars($row['sem']);
                            ?>
                            <option value="<?php echo $semester; ?>" <?php echo isset($_GET['semester']) && $_GET['semester'] == $row['sem'] ? 'selected' : ''; ?>><?php echo ucwords($semester); ?></option>
                            <?php 
                                    endwhile;
                                } else {
                                    echo '<option value="">Error: '.$conn->error.'</option>';
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
                                <th class="text-center">Time</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Course code</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Units</th>
                                <th class="text-center">Room</th>
                                <th class="text-center">Instructor</th>
                                <th class="text-center">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $total_units = 0;  // Initialize total units
                            
                            if (isset($_GET['secid']) && isset($_GET['semester'])) {
                                $secid = $conn->real_escape_string($_GET['secid']);
                                $semester = $conn->real_escape_string($_GET['semester']);
                                $loads = $conn->query("SELECT * FROM loading WHERE course = '$secid' AND semester = '$semester' AND dept_id = '$dept_id' ORDER BY timeslot_sid ASC");
                            } else {
                                $loads = $conn->query("SELECT * FROM loading WHERE dept_id = '$dept_id' ORDER BY timeslot_sid ASC");
                            }

                            if ($loads) {
                                while ($lrow = $loads->fetch_assoc()) {
                                    $days = htmlspecialchars($lrow['days']);
                                    $timeslot = htmlspecialchars($lrow['timeslot']);
                                    $course = htmlspecialchars($lrow['course']);
                                    $subject_code = htmlspecialchars($lrow['subjects']);
                                    $room_id = htmlspecialchars($lrow['rooms']);
                                    $instid = htmlspecialchars($lrow['faculty']);
                                    $semester = htmlspecialchars($lrow['semester']);

                                  $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code' AND dept_id = '$dept_id'");

                                    if ($subjects && $srow = $subjects->fetch_assoc()) {
                                        $description = htmlspecialchars($srow['description']);
                                        $units = htmlspecialchars($srow['total_units']);
                                    } else {
                                        $description = 'N/A';
                                        $units = 'N/A';
                                    }
                                   $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id = '$instid' AND dept_id = '$dept_id'");

                                    if ($faculty && $frow = $faculty->fetch_assoc()) {
                                        $instname = htmlspecialchars($frow['name']);
                                    } else {
                                        $instname = 'N/A';
                                    }

                              $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = '$room_id' AND dept_id = '$dept_id'");

                                    if ($rooms && $roomrow = $rooms->fetch_assoc()) {
                                        $room_name = htmlspecialchars($roomrow['room_name']);
                                    } else {
                                        $room_name = 'N/A';
                                    }

                                    // Sum the units
                                    $total_units += $units !== 'N/A' ? (int)$units : 0;

                                    echo '<tr>
                                        <td class="text-center">'.$timeslot.'</td>
                                        <td class="text-center">'.$days.'</td>
                                        <td class="text-center">'.$subject_code.'</td>
                                        <td class="text-center">'.$description.'</td>
                                        <td class="text-center">'.$units.'</td>
                                        <td class="text-center">'.$room_name.'</td>
                                        <td class="text-center">'.$instname.'</td>
                                        <td class="text-center">'.$semester.'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">Error: '.$conn->error.'</td></tr>';
                            }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total Units:</th>
                                <th class="text-center"><?php echo $total_units; ?></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Table Panel -->
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body">
                <div class="scheddays"><p>Schedule: </p><span></span></div>
                <div class="time"><p>Time: </p><span></span></div>
                <div class="course"><p>Course Code: </p><span></span></div>
                <div class="description"><p>Description: </p><span></span></div>
                <div class="units"><p>Units: </p><span></span></div>
                <div class="room"><p>Room: </p><span></span></div>
                <div class="instructor"><p>Instructor: </p><span></span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    td {
        vertical-align: middle !important;
    }
</style>

<script>
    $('#sec_id, #semester').change(function(){
        var secid = $('#sec_id').val();
        var semester = $('#semester').val();
        if (secid && semester) {
            window.location.href = 'class_sched.php?page=class_sched&secid=' + secid + '&semester=' + semester;
        }
    });

    $('.edit_schedule').click(function(){
        var id = $(this).data('id');
        uni_modal("Manage Job Post", "manage_schedule.php?id=" + id, 'mid-large');
    });

    $('.delete_schedule').click(function(){
        var id = $(this).data('id');
        _conf("Are you sure to delete this schedule?", "delete_schedule", [id], 'mid-large');
    });

    $('#print').click(function(){
        var secid = $(this).data('secid');
        var semester = $(this).data('semester');
        window.location.href = 'class_schedgenerate.php?secid=' + secid + '&semester=' + semester;
    });

    function delete_schedule(id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_schedule',
            method: 'POST',
            data: {id: id},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
