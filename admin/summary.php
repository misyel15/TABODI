<?php include('db_connect.php');?>
<?php include 'includes/header.php'; ?>
<div class="container-fluid" style="margin-top:100px;">

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					<button type="button" class="btn btn-success btn-sm float-right" id="print"><span class="glyphicon glyphicon-print"></span><i class="fas fa-print"></i> Print</button>
					
						<center><h3>Summary of Teaching Loads</h3></center>
						<form method="POST" class="form-inline" id="printra" action="summary_generate.php" style="margin-left: 520px;">
						</form>
						
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Name of Instructors</th>
									<th class="text-center">Subjects/Course</th>
									<th class="text-center">Load</th>
									<th class="text-center">Other Load</th>
									<th class="text-center">Overload</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sumloads=0;
								$sumotherl=0;
								$sumoverl=0;
								$totalloads=0;
								$subject = '';
								$subjectarr = array();
								$instname = '';
							
								
			
									$loads = $conn->query("SELECT `faculty`, GROUP_CONCAT(DISTINCT `sub_description` ORDER BY `sub_description` ASC SEPARATOR ', ') AS `subject`, SUM(`total_units`) AS `totunits` FROM `loading` GROUP BY `faculty`");
								while($lrow=$loads->fetch_assoc()){
									$subjects = $lrow['subject'];
									$faculty_id = $lrow['faculty'];
									$sumloads = $lrow['totunits'];
									// $subject = $lrow['sub_description'];
									// $subjectarr[] = $lrow['sub_description'];
									// $newsubjects = implode(", ", $subjectarr);
									// print_r($subjectarr);
									$totalloads = $sumloads + $sumotherl;

									$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty WHERE id='$faculty_id' order by concat(lastname,', ',firstname,' ',middlename) asc");
									while($frow=$faculty->fetch_assoc()){
										$instname = $frow['name'];
									}
								

									

									echo'<tr>
											<td class="text-center">'.$instname.'</td>
											<td class="text-center">'.$subjects.'</td>
											<td class="text-center">'.$sumloads.'</td>
											<td class="text-center">'.$sumotherl.'</td>
											<td class="text-center">'.$sumoverl.'</td>
											<td class="text-center">'.$totalloads.'</td>
										</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
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
<style>
	
	td{
		vertical-align: middle !important;
	}
</style>
<script>
	$('.edit_schedule').click(function(){
		uni_modal("Manage Job Post","manage_schedule.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.delete_schedule').click(function(){
		_conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')],'mid-large')
	})
	$('#print').click(function(e){
    e.preventDefault();
    $('#printra').attr('action', 'summary_generate.php');
    $('#printra').submit();
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