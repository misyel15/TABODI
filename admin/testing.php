<?php include('db_connect.php');?>

<div class="container-fluid">
<b>Room Assignment</b>
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Monday/Wednesday</b>
						<span class="float:right">
							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right"  id="new_schedule_mw">
					<i class="fa fa-plus"></i> New Entry
				</button>
				<div class="dropdown">
				<button class="btn btn-danger btn-sm col-sm-2 float-right dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Delete Table
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item delete_MW" data-day="MW">Monday/Wednesday</a>
					<a class="dropdown-item delete_TTh" data-day="TTh">Tuesday/Thursday</a>
					<a class="dropdown-item delete_Fri" data-day="F">Friday</a>
					<a class="dropdown-item delete_Sat" data-day="Sat">Saturday</a>
				</div>
				</div>
				<form method="POST" class="form-inline" id="printra" action="roomassign_generate.php">
						<button type="button" class="btn btn-success btn-sm btn-flat" id="print"><span class="glyphicon glyphicon-print"></span> Print</button>
						</form></span>
						
                        </div>
					<div class="card-body">
						<table class="table table-bordered waffle no-grid" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Time</th>
									<?php 
									$room = "";
								$rooms = $conn->query("SELECT DISTINCT room_name FROM roomlist order by room_id asc");
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
                                    
                                echo '<th class="text-center">'. $room.'</th>';

								}
                                ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$time_id="";
								$timeslots = $conn->query("SELECT * FROM timeslot Where schedule ='MWTTh' and time_id='2'");
								foreach ($timeslots as $timerow) {
									$time = $timerow['timeslot'];
									$time_id =$timerow['id'];
									
									$finaltbl ='<tr>
									<td class="s8 text-center content">'.$time.'</td>';
		
								?>
				<?php
						
						$i = 1;
						$timeslot_id="";
						$room_name = "";
						$faculty_id="";
						$scheds = "";
						$load_id = "";
						$subject = "";
						$timeslot = "";
						$course = "";
						$newSched = "";
						$room_id ="";
						$loads = $conn->query("SELECT * from loading Where days ='MW' order by rooms asc");
						foreach ($loads as $load) {
						$timeslot_id = $load['timeslot_id'];
						$timeslot = $load['timeslot'];
						$room_name = $load['rooms'];
						$faculty_id = $load['faculty'];
						$course = $load['course'];
						$subject = $load['subjects'];
						$scheds = $subject." ".$course;
						$load_id = $load['id'];	

						$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty_id);	
						foreach ($faculty as $frow) {
							$instname = $frow['name'];
							$newSched= $scheds." ".$instname;
						}

						$roomlists = $conn->query("SELECT * FROM roomlist");
							$num = $roomlists->num_rows;
							print_r($num);
							while($roomlrow=$roomlists->fetch_assoc()){
								$room = $roomlrow['room_name'];
								$room_id = $roomlrow['room_id'];
							}

						if($timeslot_id == $time_id || $room_name == $room_id){

						$finaltbl .= '<td class="s9 text-center content" data-id = "'.$load_id.'" data-scode="'.$subject.'" dir="ltr">'.$newSched.'<br><span><button class="btn btn-sm btn-primary edit_load" type="button" data-id="'.$load_id.'">Edit</button></span>  <span><button class="btn btn-sm btn-danger delete_load" type="button" data-id="'.$load_id.'" data-scode="'.$subject.'">Delete</button></span></td>';
							
						}else{
							$finaltbl .= '<td class="s9 text-center" dir="ltr">No Data</td>';
						}
				}

					?>
					<?php
						$finaltbl .= '</tr>';
						echo $finaltbl;
						}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div><br>
		</div>
		<div class="container-fluid">
							<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Tuesday/Thursday</b>
						<span class="float:right">
				<form method="POST" class="form-inline" id="printratth" action="roomassign_generatetth.php">
						<button type="button" class="btn btn-success btn-sm btn-flat" id="printtth"><span class="glyphicon glyphicon-print"></span> Print</button>
						</form></span>
						
                        </div>
					<div class="card-body">
						<table class="table table-bordered waffle no-grid" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Time</th>
									<?php 
									$room = "";
								$rooms = $conn->query("SELECT DISTINCT room_name FROM roomlist order by id;");
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
                                    
                                echo '<th class="text-center">'. $room.'</th>';

								}
                                ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$time_id="";
								$timeslots = $conn->query("SELECT * FROM timeslot Where schedule ='MWTTh' order by time_id asc");
								foreach ($timeslots as $timerow) {
									$time = $timerow['timeslot'];
									$time_id =$timerow['id'];
									
									$finaltbl ='<tr>
									<td class="s8 text-center">'.$time.'</td>';
		
								?>
				<?php
						
						$i = 1;
						$timeslot_id="";
						$loads = $conn->query("SELECT * from loading Where days ='TTh'  order by rooms asc");
						foreach ($loads as $load) {
						$timeslot_id = $load['timeslot_id'];
						$timeslot = $load['timeslot'];
						$room_name = $load['rooms'];
						$faculty = $load['faculty'];
						$course = $load['course'];
						$subject = $load['subjects'];
						$scheds = $subject." ".$course;
						$load_id = $load['id'];

						$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty);
						foreach ($faculty as $frow) {
							$instname = $frow['name'];
							$newSched= $scheds." ".$instname;
						}
						
							if($timeslot_id == $time_id ){
								$room = "";
								$num ="";
								$roomidarr =[];
								$rooms = $conn->query("SELECT * FROM roomlist order by id asc");
								$num = $rooms->num_rows;
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
									$room_id = $roomrow['id'];
									$roomidarr =array($room_id);
									if($room_name == $room_id){
										$finaltbl .= '<td class="s9 text-center content" data-id = "'.$load_id.'" data-scode="'.$subject.'" dir="ltr">'.$newSched.'<br><span><button class="btn btn-sm btn-primary edit_load" type="button" data-id="'.$load_id.'">Edit</button></span>  <span><button class="btn btn-sm btn-danger delete_load" type="button" data-id="'.$load_id.'" data-scode="'.$subject.'">Delete</button></span></td>';
									}
									else {
										//$finaltbl .= '<td class="s9 text-center" dir="ltr">No Data</td>';
									}
								}

										
								
							}
				}
					
					?>
					<?php
						$finaltbl .= '</tr>';
						echo $finaltbl;
						}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div><br>
			<!-- Table Panel -->
		</div>
		<div class="container-fluid">
							<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Friday</b>
						<span class="float:right">
				<form method="POST" class="form-inline" id="printrafri" action="roomassign_generatefri.php">
						<button type="button" class="btn btn-success btn-sm btn-flat" id="printfri"><span class="glyphicon glyphicon-print"></span> Print</button>
						</form></span>
						
                        </div>
					<div class="card-body">
						<table class="table table-bordered waffle no-grid" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Time</th>
									<?php 
									$room = "";
								$rooms = $conn->query("SELECT DISTINCT room_name FROM roomlist order by id;");
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
                                    
                                echo '<th class="text-center">'. $room.'</th>';

								}
                                ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$time_id="";
								$timeslots = $conn->query("SELECT * FROM timeslot Where schedule ='F' order by time_id asc");
								foreach ($timeslots as $timerow) {
									$time = $timerow['timeslot'];
									$time_id =$timerow['id'];
									
									$finaltbl ='<tr>
									<td class="s8 text-center">'.$time.'</td>';
		
								?>
				<?php
						
						$i = 1;
						$timeslot_id="";
						$loads = $conn->query("SELECT * from loading Where days ='F'  order by rooms asc");
						foreach ($loads as $load) {
						$timeslot_id = $load['timeslot_id'];
						$timeslot = $load['timeslot'];
						$room_name = $load['rooms'];
						$faculty = $load['faculty'];
						$course = $load['course'];
						$subject = $load['subjects'];
						$scheds = $subject." ".$course;
						$load_id = $load['id'];

						$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty);
						foreach ($faculty as $frow) {
							$instname = $frow['name'];
							$newSched= $scheds." ".$instname;
						}
						
							if($timeslot_id == $time_id ){
								$room = "";
								$num ="";
								$roomidarr =[];
								$rooms = $conn->query("SELECT * FROM roomlist order by id asc");
								$num = $rooms->num_rows;
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
									$room_id = $roomrow['id'];
									$roomidarr =array($room_id);
									if($room_name == $room_id){
										$finaltbl .= '<td class="s9 text-center content" data-id = "'.$load_id.'" data-scode="'.$subject.'" dir="ltr">'.$newSched.'<br><span><button class="btn btn-sm btn-primary edit_load" type="button" data-id="'.$load_id.'">Edit</button></span>  <span><button class="btn btn-sm btn-danger delete_load" type="button" data-id="'.$load_id.'" data-scode="'.$subject.'">Delete</button></span></td>';
									}
									else {
										//$finaltbl .= '<td class="s9 text-center" dir="ltr">No Data</td>';
									}
								}

										
								
							}
				}
					
					?>
					<?php
						$finaltbl .= '</tr>';
						echo $finaltbl;
						}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div><br>
			<!-- Table Panel -->
		</div>
		<div class="container-fluid">
							<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Saturday</b>
						<span class="float:right">
				<form method="POST" class="form-inline" id="printrasat" action="roomassign_generatesat.php">
						<button type="button" class="btn btn-success btn-sm btn-flat" id="printsat"><span class="glyphicon glyphicon-print"></span> Print</button>
						</form></span>
						
                        </div>
					<div class="card-body">
						<table class="table table-bordered waffle no-grid" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Time</th>
									<?php 
									$room = "";
								$rooms = $conn->query("SELECT DISTINCT room_name FROM roomlist order by id;");
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
                                    
                                echo '<th class="text-center">'. $room.'</th>';

								}
                                ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$time_id="";
								$timeslots = $conn->query("SELECT * FROM timeslot Where schedule ='Sat' order by time_id asc");
								foreach ($timeslots as $timerow) {
									$time = $timerow['timeslot'];
									$time_id =$timerow['id'];
									
									$finaltbl ='<tr>
									<td class="s8 text-center">'.$time.'</td>';
		
								?>
				<?php
						
						$i = 1;
						$timeslot_id="";
						$loads = $conn->query("SELECT * from loading Where days ='Sat'  order by rooms asc");
						foreach ($loads as $load) {
						$timeslot_id = $load['timeslot_id'];
						$timeslot = $load['timeslot'];
						$room_name = $load['rooms'];
						$faculty = $load['faculty'];
						$course = $load['course'];
						$subject = $load['subjects'];
						$scheds = $subject." ".$course;
						$load_id = $load['id'];

						$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty);
						foreach ($faculty as $frow) {
							$instname = $frow['name'];
							$newSched= $scheds." ".$instname;
						}
						
							if($timeslot_id == $time_id ){
								$room = "";
								$num ="";
								$roomidarr =[];
								$rooms = $conn->query("SELECT * FROM roomlist order by id asc");
								$num = $rooms->num_rows;
								while($roomrow=$rooms->fetch_assoc()){
									$room = $roomrow['room_name'];
									$room_id = $roomrow['id'];
									$roomidarr =array($room_id);
									if($room_name == $room_id){
										$finaltbl .= '<td class="s9 text-center content" data-id = "'.$load_id.'" data-scode="'.$subject.'" dir="ltr">'.$newSched.'<br><span><button class="btn btn-sm btn-primary edit_load" type="button" data-id="'.$load_id.'">Edit</button></span>  <span><button class="btn btn-sm btn-danger delete_load" type="button" data-id="'.$load_id.'" data-scode="'.$subject.'">Delete</button></span></td>';
									}
									else {
										//$finaltbl .= '<td class="s9 text-center" dir="ltr">No Data</td>';
									}
								}

										
								
							}
				}
					
					?>
					<?php
						$finaltbl .= '</tr>';
						echo $finaltbl;
						}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div><br>
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
	
	td{
		vertical-align: middle !important;
	}
</style>
<script>
 $(document).ready(function () {
    $(document).on('mouseenter', 'div', function () {
        $(this).find(".edit_load").show();
		$(this).find(".delete_load").show();
    }).on('mouseleave', 'div', function () {
        $(this).find(".edit_load").hide();
		$(this).find(".delete_load").hide();
    });
	$('.dropdown-toggle').dropdown();
});
$('.delete_load').click(function(){
		_conf("Are you sure you want to delete this room?","delete_load",[$(this).attr('data-id')])
	})

	$('.delete_MW').click(function(){
		_conf("Are you sure you want to delete this room?","delete_MW",[$(this).attr('data-day')])
	})

	$('.delete_TTh').click(function(){
		_conf("Are you sure you want to delete this room?","delete_TTh",[$(this).attr('data-day')])
	})

	$('.delete_Fri').click(function(){
		_conf("Are you sure you want to delete this room?","delete_Fri",[$(this).attr('data-day')])
	})

	$('.delete_Sat').click(function(){
		_conf("Are you sure you want to delete this room?","delete_Sat",[$(this).attr('data-day')])
	})

	function delete_load($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_load',
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

	function delete_MW($days){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_MW',
			method:'POST',
			data:{days:$days},
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
	function delete_TTh($days){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_TTh',
			method:'POST',
			data:{days:$days},
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
	function delete_Fri($days){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_Fri',
			method:'POST',
			data:{days:$days},
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
	function delete_Sat($days){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_Sat',
			method:'POST',
			data:{days:$days},
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
	/*$("td").hover(function(){
$(this).css( "background-color", "red" );
}, function(){
$(this).css( "background-color", "white" );
});*/
$('.edit_load').click(function(){
	uni_modal('Edit Load','manage_room.php?id='+$(this).attr('data-id'))
})
$('.edit_loadtth').click(function(){
	uni_modal('Edit Load','manage_roomtth.php?id='+$(this).attr('data-id'))
})

$('#new_schedule_mw').click(function(){
		uni_modal('New Schedule','manage_room.php','mid-large')
	})

	$('#new_schedule_tth').click(function(){
		uni_modal('New Schedule','manage_room.php','mid-large')
	})

	$('#new_schedule_f').click(function(){
		uni_modal('New Schedule','manage_room.php','mid-large')
	})

	$('#print').click(function(e){
    e.preventDefault();
    $('#printra').attr('action', 'roomassign_generate.php');
    $('#printra').submit();
  });
  $('#printtth').click(function(e){
    e.preventDefault();
    $('#printratth').attr('action', 'roomassign_generatetth.php');
    $('#printratth').submit();
  });
  $('#printfri').click(function(e){
    e.preventDefault();
    $('#printrafri').attr('action', 'roomassign_generatefri.php');
    $('#printrafri').submit();
  });
  $('#printsat').click(function(e){
    e.preventDefault();
    $('#printrasat').attr('action', 'roomassign_generatesat.php');
    $('#printrasat').submit();
  });

  /*$(function() {
    var tableRows = $("#insloadtable tbody tr"); //find all the rows
    var rowValues = []; //to keep track of which values appear more than once
    tableRows.each(function() { 
        var rowValue = $(this).find(".content").html();
        if (!rowValues[rowValue]) {
            var rowComposite = new Object();
            rowComposite.count = 1;
            rowComposite.row = this;
            rowValues[rowValue] = rowComposite;
        } else {
            var rowComposite = rowValues[rowValue];
            if (rowComposite.count == 1) {
                $(rowComposite.row).css('backgroundColor', 'red');
            }
            $(this).css('backgroundColor', 'red');
            rowComposite.count++;
        }
    });
});
	/*$('table').dataTable()
	var table = $('#insloadtable').DataTable();
    $('#insloadtable tbody').on('click', 'td', function () {
        //console.log(table.row(this).data());
        $(".modal-body div span").text("");
        $(".scheddays span").text(table.row(this).data()[1]);
        $(".time span").text(table.row(this).data()[2]);
        $(".course span").text(table.row(this).data()[3]);
        $(".description span").text(table.row(this).data()[4]);
        $(".units span").text(table.row(this).data()[5]);
        $(".room span").text(table.row(this).data()[6]);
		$(".instructor span").text(table.row(this).data()[7]);
        $("#myModal").modal("show");
    });*/
</script>