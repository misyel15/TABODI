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
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <label class="control-label col-md-2 offset-md-2">
                        <b><h5>Export Class Schedule of:</h5></b>
                    </label>
                    <div class="col-md-4">
                        <select name="course" id="course" class="custom-select select2">
                            <option value="0" disabled selected>Select Course</option>
                            <?php 
                            $courses = $conn->query("SELECT * FROM courses WHERE dept_id = $dept_id ORDER BY id ASC");
                            while($row = $courses->fetch_array()): ?>
                                <option value="<?php echo $row['course'] ?>" 
                                    <?php echo isset($_GET['course']) && $_GET['course'] == $row['course'] ? 'selected' : '' ?>>
                                    <?php echo $row['course'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <select name="sec_id" id="sec_id" class="custom-select select2">
                            <option value="0" disabled selected>Select Yr. & Sec.</option>
                            <?php 
                            $sections = $conn->query("SELECT * FROM section WHERE dept_id = $dept_id ORDER BY year ASC");
                            while($row = $sections->fetch_array()): ?>
                                <option value="<?php echo $row['year'].$row['section'] ?>" 
                                    <?php echo isset($_GET['secid']) && $_GET['secid'] == $row['year'].$row['section'] ? 'selected' : '' ?>>
                                    <?php echo $row['year'] . " " . $row['section'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <select name="semester" id="semester" class="form-control">
                            <option value="0" disabled selected>Select Semester</option>
                            <?php 
                            $semesters = $conn->query("SELECT * FROM semester");
                            while($row = $semesters->fetch_array()): ?>
                                <option value="<?php echo $row['sem'] ?>" 
                                    <?php echo isset($_GET['semester']) && $_GET['semester'] == $row['sem'] ? 'selected' : '' ?>>
                                    <?php echo ucwords($row['sem']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div>
                    <?php 
                    if (isset($_GET['secid']) && isset($_GET['semester']) && isset($_GET['course']) && isset($_GET['year'])) {
                        $secid = $_GET['secid'];
                        $semester = $_GET['semester'];
                        $year = $_GET['year'];
                        $course = $_GET['course'];
                    ?>
                        <form method="post" action="export_csv.php?secid=<?php echo $secid ?>&semester=<?php echo $semester ?>&year=<?php echo $year ?>&course=<?php echo $course ?>" align="center">
                            <button type="submit" name="export" class="btn btn-success">
                                <i class="fas fa-file-csv"></i> Export Schedule
                            </button>
                        </form>
                        <br>
                        <form method="post" action="export_fees.php?secid=<?php echo $secid ?>&semester=<?php echo $semester ?>&year=<?php echo $year ?>&course=<?php echo $course ?>" align="center">
                            <button type="submit" name="export" class="btn btn-info">
                                <i class="fas fa-file-invoice-dollar"></i> Export Fees
                            </button>
                        </form>

                        <div class="card">
                            <div class="card-header text-center">
                                <h3>Class Schedule</h3>
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
                                        $total_units = 0;
                                        $loads = $conn->query("SELECT * FROM loading WHERE course = '$secid' AND semester = '$semester' AND dept_id = '$dept_id' ORDER BY timeslot_sid ASC");
                                        if ($loads) {
                                            while ($lrow = $loads->fetch_assoc()) {
                                                $subject_code = htmlspecialchars($lrow['subjects']);
                                                $units = $conn->query("SELECT total_units FROM subjects WHERE subject = '$subject_code'")->fetch_assoc()['total_units'] ?? 'N/A';
                                                $total_units += is_numeric($units) ? (int)$units : 0;
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['timeslot']) ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['days']) ?></td>
                                                    <td class="text-center"><?php echo $subject_code ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['description'] ?? 'N/A') ?></td>
                                                    <td class="text-center"><?php echo $units ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['rooms'] ?? 'N/A') ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['faculty'] ?? 'N/A') ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($lrow['semester']) ?></td>
                                                </tr>
                                        <?php } 
                                        } else {
                                            echo '<tr><td colspan="8" class="text-center">No data found</td></tr>';
                                        } ?>
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
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    td { vertical-align: middle !important; }
</style>

<script>
    $('#course, #sec_id, #semester').change(function() {
        let secid = $('#sec_id').val();
        let semester = $('#semester').val();
        let course = $('#course').val();
        window.location.href = 'export.php?page=export&secid=' + secid + '&semester=' + semester + '&course=' + course;
    });
</script>
