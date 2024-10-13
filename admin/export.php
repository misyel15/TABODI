<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>
<div class="container-fluid" style="margin-top:100px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Export Class Schedule of:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <label for="" class="control-label col-md-2 offset-md-2"><b>Select Options</b></label>
                    <div class="col-md-4">
                        <select name="course" id="course" class="custom-select select2">
                            <option value="0" disabled selected>Select Course</option>
                            <?php 
                            $sections = $conn->query("SELECT * FROM courses ORDER BY id ASC");
                            while ($row = $sections->fetch_array()):
                            ?>
                                <option value="<?php echo $row['course'] ?>" <?php echo isset($_GET['course']) && $_GET['course'] == $row['course'] ? 'selected' : '' ?>>
                                    <?php echo $row['course'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <select name="sec_id" id="sec_id" class="custom-select select2">
                            <option value="0" disabled selected>Select Yr. & Sec.</option>
                            <?php 
                            $sections = $conn->query("SELECT * FROM section ORDER BY year ASC");
                            while ($row = $sections->fetch_array()):
                            ?>
                                <option value="<?php echo $row['year'] . "" . $row['section'] ?>" <?php echo isset($_GET['secid']) && $_GET['secid'] == $row['year'] . "" . $row['section'] ? 'selected' : '' ?>>
                                    <?php echo $row['year'] . "" . $row['section'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <select name="semester" id="semester" class="form-control">
                            <option value="0" disabled selected>Select Semester</option>
                            <?php 
                            $sql = "SELECT * FROM semester";
                            $query = $conn->query($sql);
                            while ($row = $query->fetch_array()):
                                $semester = $row['sem'];
                            ?>
                                <option value="<?php echo $semester ?>" <?php echo isset($_GET['semester']) && $_GET['semester'] == $row['sem'] ? 'selected' : '' ?>>
                                    <?php echo ucwords($semester) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div>
                    <?php 
                    if (isset($_GET['secid']) && isset($_GET['semester']) && isset($_GET['course'])) {
                        $secid = $_GET['secid'];
                        $semester = $_GET['semester'];
                        $course = $_GET['course'];
                    ?>
                    <form method="post" action="export_csv.php?secid=<?php echo $secid ?>&semester=<?php echo $semester ?>&course=<?php echo $course ?>" align="center">  
                        <input type="submit" name="export" value="Export Schedule" class="btn btn-success" />  
                    </form>
                    <br>
                    <div class="card">
                        <div class="card-header">
                            <center><label><h3> Class Schedule </h3></label></center>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="insloadtable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Time</th>
                                        <th class="text-center">Days</th>
                                        <th class="text-center">Course Code</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Units</th>
                                        <th class="text-center">Room</th>
                                        <th class="text-center">Instructor</th>
                                        <th class="text-center">Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_units = 0; // Initialize total units

                                    // Initialize the query with the base query string
                                    $query = "SELECT * FROM loading WHERE dept_id = '$dept_id'";

                                    // Check if secid and semester are set to filter further
                                    if (isset($_GET['secid'])) {
                                        $secid = $conn->real_escape_string($_GET['secid']);
                                        $query .= " AND course = '$secid'"; // Add course filter
                                    }

                                    if (isset($_GET['semester'])) {
                                        $semester = $conn->real_escape_string($_GET['semester']);
                                        $query .= " AND semester = '$semester'"; // Add semester filter
                                    }

                                    // Order by timeslot_sid
                                    $query .= " ORDER BY timeslot_sid ASC";

                                    $loads = $conn->query($query); // Execute the query

                                    if ($loads) {
                                        while ($lrow = $loads->fetch_assoc()) {
                                            $days = htmlspecialchars($lrow['days']);
                                            $timeslot = htmlspecialchars($lrow['timeslot']);
                                            $course = htmlspecialchars($lrow['course']);
                                            $subject_code = htmlspecialchars($lrow['subjects']);
                                            $room_id = htmlspecialchars($lrow['rooms']);
                                            $instid = htmlspecialchars($lrow['faculty']);
                                            $semester = htmlspecialchars($lrow['semester']);

                                            // Fetch subject details
                                            $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
                                            if ($subjects && $srow = $subjects->fetch_assoc()) {
                                                $description = htmlspecialchars($srow['description']);
                                                $units = htmlspecialchars($srow['total_units']);
                                            } else {
                                                $description = 'N/A';
                                                $units = 'N/A';
                                            }

                                            // Fetch faculty details
                                            $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id = '$instid'");
                                            if ($faculty && $frow = $faculty->fetch_assoc()) {
                                                $instname = htmlspecialchars($frow['name']);
                                            } else {
                                                $instname = 'N/A';
                                            }

                                            // Fetch room details
                                            $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = '$room_id'");
                                            if ($rooms && $roomrow = $rooms->fetch_assoc()) {
                                                $room_name = htmlspecialchars($roomrow['room_name']);
                                            } else {
                                                $room_name = 'N/A';
                                            }

                                            // Sum the units
                                            $total_units += $units !== 'N/A' ? (int)$units : 0;

                                            // Output the row
                                            echo '<tr>
                                                <td class="text-center">' . $timeslot . '</td>
                                                <td class="text-center">' . $days . '</td>
                                                <td class="text-center">' . $subject_code . '</td>
                                                <td class="text-center">' . $description . '</td>
                                                <td class="text-center">' . $units . '</td>
                                                <td class="text-center">' . $room_name . '</td>
                                                <td class="text-center">' . $instname . '</td>
                                                <td class="text-center">' . $semester . '</td>
                                            </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">Error: ' . $conn->error . '</td></tr>';
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
                <?php } ?>
                </div>
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
    $('#course, #sec_id, #semester').change(function() {
        secid = $('#sec_id').val();
        semester = $('#semester').val();
        course = $('#course').val();
        window.location.href = 'index.php?page=export&secid=' + secid + '&semester=' + semester + '&course=' + course;
    });

    $('.edit_schedule').click(function() {
        uni_modal("Manage Job Post", "manage_schedule.php?id=" + $(this).attr('data-id'), 'mid-large');
    });

    $('.delete_schedule').click(function() {
        _conf("Are you sure to delete this schedule?", "delete_schedule", [$(this).attr('data-id')], 'mid-large');
    });

    $('#print').click(function() {
        window.location.href = 'class_schedgenerate.php?secid=' + $(this).attr('data-secid') + '&semester=' + $(this).attr('data-semester');
    });
</script>


