<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM schedules where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<p>Subject: <b><?php echo ucwords($course_code) ?></b></p>
	<p>Description: <b><?php echo $subject_description ?></b></p>
	<p>Units: <b><?php echo $units ?></b></p>
	<p>Course: <b><?php echo $course ?></b></p>
	<p>Year: <b><?php echo $year ?></b></p>
	<p>Section: <b><?php echo $section ?></b></p>
	<p>Location: </i> <b><?php echo $room ?></b></p>
	<p>Time Start: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_from)) ?></b></p>
	<p>Time End: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_to)) ?></b></p>
	<hr class="divider">
</div>
<div class="modal-footer display">
	<div class="row">
		<div class="col-md-12">
			<button class="btn float-right btn-secondary" type="button" data-dismiss="modal">Close</button>
			<button class="btn float-right btn-danger mr-2" type="button" id="delete_schedule">Delete</button>
			<button class="btn float-right btn-primary mr-2" type="button" id="edit">Edit</button>
		</div>
	</div>
</div>
<style>
	p{
		margin:unset;
	}
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display {
		display: block;
	}
</style>
<script>
	$('#edit').click(function(){
		uni_modal('Edit Schedule','manage_schedule.php?id=<?php echo $id ?>','mid-large')
	})
	$('#delete_schedule').click(function(){
		_conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')])
	})
	
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