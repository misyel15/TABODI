<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-fees">
				<div class="card">
					<div class="card-header">
						    Fees
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
						<label for="course" class="col-sm-3 control-label">Course</label>

						<div class="col-sm-12">
						<select class="form-control" name="course" id="course" required onchange="populateYear(this.value)">
							<option value="0" disabled selected>Select Course</option>
							<?php 
									$sql = "SELECT * FROM courses";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
										$course = $row['course'];
									?>
							<option value="<?php echo $course ?>" <?php echo isset($meta['coursedesc']) && $meta['coursedesc'] == $course ? 'selected' : '' ?>><?php echo ucwords($course) ?></option>
						<?php endwhile; ?>
						</select>
						</div>
					</div>
					<div class="form-group">
						<label for="year" class="col-sm-6 control-label">Year</label>

						<div class="col-sm-12">
						<select class="form-control" name="year" id="year" required>
							<option value="0" disabled selected>Select Year</option>
							<?php 
									$sql = "SELECT DISTINCT(year) FROM section ";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
										$year = $row['year'];
									?>
							<option value="<?php echo $year ?>"><?php echo ucwords($year) ?></option>
						<?php endwhile; ?>
						</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<label for="" class="control-label">Semester</label>
							<select name="semester" class="form-control">
								<option value="0" disabled selected>Select Semester</option>
								<?php 
										$sql = "SELECT * FROM semester";
										$query = $conn->query($sql);
										while($row= $query->fetch_array()):
											$semester = $row['sem'];
										?>
								<option value="<?php echo $semester ?>" <?php echo isset($meta['semester']) && $meta['semester'] == $semester ? 'selected' : '' ?>><?php echo ucwords($semester) ?></option>
							<?php endwhile; ?>
							</select>
							</div>
						</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Library Fees</label>
								<input type="number" class="form-control" name="library" required>
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Computer Fees</label>
								<input type="number" class="form-control" name="computer">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">School ID Fees</label>
								<input type="number" class="form-control" name="school_id">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Athletic Fees</label>
								<input type="number" class="form-control" name="athletic">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Admission Fees</label>
								<input type="number" class="form-control" name="admission">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Development Fees</label>
								<input type="numer" class="form-control" name="development">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Guidance Office Fees</label>
								<input type="numer" class="form-control" name="guidance">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Handbook Fees</label>
								<input type="numer" class="form-control" name="handbook">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Entrance Fees</label>
								<input type="numer" class="form-control" name="entrance">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Registration Fees</label>
								<input type="numer" class="form-control" name="registration">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Medical Fees</label>
								<input type="numer" class="form-control" name="medical">
							</div>
							</div>
							<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">Cultural Fees</label>
								<input type="numer" class="form-control" name="cultural">
							</div>
							</div>
							</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Add Fees</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Subject List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Subject</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$subject = $conn->query("SELECT * FROM fees order by id asc");
								while($row=$subject->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>Course: <b><?php echo $row['course'] ?></b></p>
										<p>Year: <small><b><?php echo $row['year'] ?></b></small></p>
										<p>Semester: <small><b><?php echo $row['semester'] ?></b></small></p>
										<p>Library Fees: <small><b><?php echo $row['library'] ?></b></small></p>
										<p>Computer Fees: <small><b><?php echo $row['computer'] ?></b></small></p>
										<p>School ID Fees: <small><b><?php echo $row['school_id'] ?></b></small></p>
										<p>Athletic Fees: <small><b><?php echo $row['athletic'] ?></b></small></p>
										<p>Admission Fees: <small><b><?php echo $row['admission'] ?></b></small></p>
										<p>Development Fees: <small><b><?php echo $row['development'] ?></b></small></p>
										<p>Guidance Office Fees: <small><b><?php echo $row['guidance'] ?></b></small></p>
										<p>Handbook Fees: <small><b><?php echo $row['handbook'] ?></b></small></p>
										<p>Entrance Fees: <small><b><?php echo $row['entrance'] ?></b></small></p>
										<p>Registration Fees: <small><b><?php echo $row['registration'] ?></b></small></p>
										<p>Medical Fees: <small><b><?php echo $row['medical'] ?></b></small></p>
										<p>Cultural Fees: <small><b><?php echo $row['cultural'] ?></b></small></p>
										
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_fees" type="button" data-id="<?php echo $row['id'] ?>" data-course="<?php echo $row['course'] ?>" data-semester="<?php echo $row['semester'] ?>" data-year="<?php echo $row['year'] ?>" data-library="<?php echo $row['library'] ?>" data-computer="<?php echo $row['computer'] ?>" data-school_id="<?php echo $row['school_id'] ?>" data-athletic="<?php echo $row['athletic'] ?>" data-admission="<?php echo $row['admission'] ?>" data-development="<?php echo $row['development'] ?>" data-guidance="<?php echo $row['guidance'] ?>" data-handbook="<?php echo $row['handbook'] ?>" data-entrance="<?php echo $row['entrance'] ?>" data-registration="<?php echo $row['registration'] ?>" data-medical="<?php echo $row['medical'] ?>" data-cultural="<?php echo $row['cultural'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_fees" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
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
	function _reset(){
		$('#manage-fees').get(0).reset()
		$('#manage-fees input,#manage-fees textarea').val('')
	}
	$('#manage-fees').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_fees',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_fees').click(function(){
		start_load()
		var cat = $('#manage-fees')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='course']").val($(this).attr('data-course'))
		cat.find("[name='year']").val($(this).attr('data-year'))
		cat.find("[name='semester']").val($(this).attr('data-semester'))
		cat.find("[name='library']").val($(this).attr('data-library'))
		cat.find("[name='computer']").val($(this).attr('data-computer'))
		cat.find("[name='school_id']").val($(this).attr('data-school_id'))
		cat.find("[name='athletic']").val($(this).attr('data-athletic'))
		cat.find("[name='admission']").val($(this).attr('data-admission'))
		cat.find("[name='development']").val($(this).attr('data-development'))
		cat.find("[name='guidance']").val($(this).attr('data-guidance'))
		cat.find("[name='handbook']").val($(this).attr('data-handbook'))
		cat.find("[name='entrance']").val($(this).attr('data-entrance'))
		cat.find("[name='registration']").val($(this).attr('data-registration'))
		cat.find("[name='medical']").val($(this).attr('data-medical'))
		cat.find("[name='cultural']").val($(this).attr('data-cultural'))
		end_load()
	})
	$('.delete_fees').click(function(){
		_conf("Are you sure to delete this subject?","delete_fees",[$(this).attr('data-id')])
	})
	function delete_fees($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_fees',
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
	$('table').dataTable()
</script>