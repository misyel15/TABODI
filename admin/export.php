<?php
session_start();
include('db_connect.php');


// Assuming you store the department ID in the session during login
// Example: $_SESSION['dept_id'] = $user['dept_id'];
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>
<div class="container-fluid" style="margin-top:100px;">

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					</div>
					<div class="card-body">
					<div class="row">
							<label for="" class="control-label col-md-2 offset-md-2"><b><h5>Export Class Schedule of:</h5></b></label>
							<div class=" col-md-4">
							<select name="course" id="course" class="custom-select select2">
							<option value="0" disabled selected>Select Course</option>
							<?php 
								$sections = $conn->query("SELECT * FROM courses WHERE dept_id = $dept_id ORDER BY id ASC");
								while($row= $sections->fetch_array()):
							?>
								<option value="<?php echo $row['course'] ?>" <?php echo isset($_GET['course']) && $_GET['course'] == $row['course'] ? 'selected' : '' ?>><?php echo $row['course'] ?></option>
							<?php endwhile; ?>
							</select>
							
							
						</select>
						<select name="sec_id" id="sec_id" class="custom-select select2">
							<option value="0" disabled selected>Select Yr. & Sec.</option>
							<?php 
								$sections = $conn->query("SELECT * FROM section WHERE dept_id = $dept_id order by year asc");
								while($row= $sections->fetch_array()):
							?>
								<option value="<?php echo $row['year']."".$row['section'] ?>" <?php echo isset($_GET['secid']) && $_GET['secid'] == $row['year']."".$row['section'] ? 'selected' : '' ?>><?php echo $row['year']."".$row['section'] ?></option>
							<?php endwhile; ?>
							</select>
							<select name="semester" id="semester" class="form-control">
								<option value="0" disabled selected>Select Semester</option>
								<?php 
										$sql = "SELECT * FROM semester";
										$query = $conn->query($sql);
										while($row= $query->fetch_array()):
											$semester = $row['sem'];
										?>
								<option value="<?php echo $semester ?>"<?php echo isset($_GET['semester']) && $_GET['semester'] == $row['sem'] ? 'selected' : '' ?>><?php echo ucwords($semester) ?></option>
							<?php endwhile; ?>
							</select>
							</div>
						</div>
						<br>
						<div>
							<?php 
							if(isset($_GET['secid']) and isset($_GET['semester']) and isset($_GET['course']) and isset($_GET['year'])){
								$secid =$_GET['secid'];
								$semester =$_GET['semester'];
								$year =$_GET['year'];
								$course =$_GET['course'];?>
								<form method="post" action="export_csv.php?secid=<?php echo  $secid?>&semester=<?php echo  $semester?>&year=<?php echo  $year?>&course=<?php echo  $course?>" align="center">  
                     <input type="submit" name="export" value="Export Schedule" class="btn btn-success" />  
                </form> 
				<br>
                <form method="post" action="export_fees.php?secid=<?php echo  $secid?>&semester=<?php echo  $semester?>&year=<?php echo  $year?>&course=<?php echo  $course?>" align="center">  
                     <!--<input type="submit" name="export" value="Export Fees" class="btn btn-success" />-->
                </form>  
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
                            
                            if(isset($_GET['secid']) && isset($_GET['semester'])){
                                $secid = $conn->real_escape_string($_GET['secid']);
                                $semester = $conn->real_escape_string($_GET['semester']);
                                $loads = $conn->query("SELECT * FROM loading WHERE course = '$secid' AND semester = '$semester' AND dept_id = '$dept_id' ORDER BY timeslot_sid ASC");
                            } else {
                                $loads = $conn->query("SELECT * FROM loading WHERE dept_id = '$dept_id' ORDER BY timeslot_sid ASC");
                            }

                            if ($loads) {
                                while($lrow = $loads->fetch_assoc()){
                                    $days = htmlspecialchars($lrow['days']);
                                    $timeslot = htmlspecialchars($lrow['timeslot']);
                                    $course = htmlspecialchars($lrow['course']);
                                    $subject_code = htmlspecialchars($lrow['subjects']);
                                    $room_id = htmlspecialchars($lrow['rooms']);
                                    $instid = htmlspecialchars($lrow['faculty']);
                                    $semester = htmlspecialchars($lrow['semester']);

                                    $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");
                                    if ($subjects && $srow = $subjects->fetch_assoc()) {
                                        $description = htmlspecialchars($srow['description']);
                                        $units = htmlspecialchars($srow['total_units']);
                                    } else {
                                        $description = 'N/A';
                                        $units = 'N/A';
                                    }

                                    $faculty = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as name FROM faculty WHERE id = '$instid'");
                                    if ($faculty && $frow = $faculty->fetch_assoc()) {
                                        $instname = htmlspecialchars($frow['name']);
                                    } else {
                                        $instname = 'N/A';
                                    }

                                    $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = '$room_id'");
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
						<?php	} ?>
						</div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>

<style>
	
	td{
		vertical-align: middle !important;
	}
</style>
<script>
	
	$('#course') + $('#sec_id') + $('#year') + $('#semester').change(function(){
		secid = $('#sec_id').val();
		semester = $('#semester').val();
		course = $('#course').val();
		year = $('#year').val();
		window.location.href = 'index.php?page=export&secid='+secid+'&semester='+semester+'&year='+year+'&course='+course;
	})
	$('.edit_schedule').click(function(){
		uni_modal("Manage Job Post","manage_schedule.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.delete_schedule').click(function(){
		_conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')],'mid-large')
	})
	$('#print').click(function(){
	//uni_modal('View Subjects','class_sched.php?secid='+$(this).attr('data-secid'))
	window.location.href = 'class_schedgenerate.php?secid='+$(this).attr('data-secid')+'&semester='+$(this).attr('data-semester');
	});

	function delete_schedule($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_schedule',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>
