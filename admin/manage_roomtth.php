<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$user = $conn->query("SELECT * FROM tthloading where id =".$_GET['id']);
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
	}
?>
<style>
	
	
</style>
<div class="container-fluid">
	<form action="" id="manage-roomscheduletth">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="col-lg-16">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Faculty</label>
						<select name="faculty" id="" class="custom-select select2">
							<option value="0">All</option>
						<?php 
							$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty order by concat(lastname,', ',firstname,' ',middlename) asc");
							while($row= $faculty->fetch_array()):
						?>
							<option value="<?php echo $row['name'] ?>" <?php echo isset($meta['faculty']) && $meta['faculty'] == $row['name'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
                                <label for="subject" class="col-sm-3 control-label">Subject</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="subject" id="subject" required>
                                    <option value="" disabled selected>Select Subject</option>
									<?php 
									$sql = "SELECT * FROM subjects WHERE status >= 1";
									$query = $conn->query($sql);
									while($prow= $query->fetch_array()):
									?>
							<option value="<?php echo $prow['subject'] ?>" <?php echo isset($meta['subjects']) && $meta['subjects'] == $prow['subject'] ? 'selected' : '' ?>><?php echo ucwords($prow['subject']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                        </div>
					<div class="form-group">
                                <label for="room" class="col-sm-3 control-label">Room</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="room" id="room" required>
                                    <option value="" disabled selected>Select Room</option>
									<?php 
									$sql = "SELECT * FROM roomlist";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
									?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($meta['rooms']) && $meta['rooms'] == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['room_name']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                            </div>
							<input type="hidden" name="timeslot" id="timeslot" value="<?php echo isset($timeslot) ? $timeslot : '' ?>">
							<div class="form-group">
                                <label for="room" class="col-sm-3 control-label">Timeslot</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="timeslot_id" id="timeslot_id" required onchange="populatetime(this.value)">
                                    <option value="" disabled selected>Select Timeslot</option>
									<?php 
									$sql = "SELECT * FROM timeslot";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
									?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($meta['timeslot_id']) && $meta['timeslot_id'] == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['timeslot']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                            </div>
							<div class="form-group">
								<label class="control-label">Days of Week</label>
								<input type="text" class="form-control" name="days" id="days" value="<?php echo isset($meta['days']) ? $meta['days'] : ''?>">
							</div>
					<div class="form-group">
						<label for="course" class="col-sm-3 control-label">Course</label>

						<div class="col-sm-12">
						<select class="form-control" name="course" id="course" required>
							<option value="0" disabled selected>Select Course</option>
							<?php 
									$sql = "SELECT * FROM section";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
										$course = $row['course'];
									?>
							<option value="<?php echo $row['year']."".$row['section'] ?>" <?php echo isset($meta['course']) && $meta['course'] == $row['year']."".$row['section'] ? 'selected' : '' ?>><?php echo ucwords($row['year']."".$row['section']) ?></option>
						<?php endwhile; ?>
						</select>
						</div>
					</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
	</div>
	<script>
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	$('#manage-roomscheduletth').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=roomscheduletth',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				alert_toast(resp);
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else if(resp==2){
					alert_toast("Room and Timeslot already exist",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				
			}
		})
	})


	/*document.getElementById('subject').addEventListener('change', function() {
			getDesc();
	});*/
	document.getElementById('room').addEventListener('change', function() {
		getSched();
	});
	/*function getDesc(){ 
		var e = document.getElementById("subject")
		var value = e.options[e.selectedIndex].value;
        var myval=value.split('-');
        document.getElementById("description").value=myval[0];
        document.getElementById("units").value=myval[1];
    }*/


	function getSched(time,id){ 
		var date = new Date();
		//var e = document.getElementById("room")
		//var value = e.options[e.selectedIndex].value;
        var myval=time.split('-');
		var dateNow =(date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear();
		var starttime = new Date(dateNow+" "+myval[0])
		var endtime = new Date(dateNow+" "+myval[1])
		var starthours = starttime.getHours().toString();
		var startmins = starttime.getMinutes().toString();
		var endhours = endtime.getHours().toString();
		var endmins = endtime.getMinutes().toString();
		if(starthours.length <= 1 ){
			starthours = "0"+starthours;
		}
		else{
			starthours = starthours;
		}
		if(startmins.length <= 1 ){
			startmins = "0"+startmins;
		}
		else{
			startmins = startmins;
		}
		if(endhours.length <= 1 ){
			endhours = "0"+endhours;
		}else{
			endhours = endhours;
		}
		if(endmins.length <= 1 ){
			endmins= "0"+endmins;
		}
		else{
			endmins = endmins
		}

		var timefrom = starthours+":"+startmins;
		var timeto = endhours+":"+endmins;
        document.getElementById("time_from").value = timefrom;
        document.getElementById("time_to").value = timeto;
		document.getElementById("timeslot").value = id;
    }

	function populatetime(timeslot_id){

	var desc = document.getElementById('description');


	// AJAX request
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "gfg.php", true); 
	xhttp.setRequestHeader("Content-Type", "application/json");
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// Response
			var response = JSON.parse(this.responseText);
			
			var len = 0;
			if(response != null){
			len = response.length;
			}

			if(len > 0){

				for(var i=0; i<len; i++){

				var id = response[i].id;
				var time = response[i].timeslot;
				document.getElementById("timeslot").value = time;
				
				}
			}
		}
	};
	var data = {request:'getTime',id: timeslot_id};
	xhttp.send(JSON.stringify(data));
}

	function populatedesc(subject){

		var desc = document.getElementById('description');
		var unit = document.getElementById('units');


			// AJAX request
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "gfg.php", true); 
			xhttp.setRequestHeader("Content-Type", "application/json");
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					// Response
					var response = JSON.parse(this.responseText);
					
					var len = 0;
					if(response != null){
					len = response.length;
					}

					if(len > 0){

						for(var i=0; i<len; i++){

						var descriptiondata = response[i].description;
						var unitsdata = response[i].units;

						desc.value = descriptiondata;
						unit.value = unitsdata;
						
						}
					}
				}
			};
			var data = {request:'getDesc',id: subject};
			xhttp.send(JSON.stringify(data));
		}

			function populateYear(course_id){

				var yearel = document.getElementById('year');

				yearel.innerHTML = "";
				
				var yearopt = document.createElement('option');
				yearopt.value = 0;
				yearopt.innerHTML = 'Select Year';
				yearel.appendChild(yearopt);


					// AJAX request
					var xhttp = new XMLHttpRequest();
					xhttp.open("POST", "gfg.php", true); 
					xhttp.setRequestHeader("Content-Type", "application/json");
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							// Response
							var response = JSON.parse(this.responseText);
							
							var len = 0;
							if(response != null){
							len = response.length;
							}
						
							if(len > 0){
								// Read data and create <option >
								for(var i=0; i<len; i++){

									var id = response[i].id;
									var name = response[i].name;

									// Add option to state dropdown
									var opt = document.createElement('option');
									opt.value = id;
									opt.innerHTML = name;
									yearel.appendChild(opt);

								}
							}
						}
					};
					var data = {request:'getYear',course_id: course_id};
					xhttp.send(JSON.stringify(data));
	}

	function populateSection(section_id){
		var sectionel = document.getElementById('section');

		sectionel.innerHTML = "";

			var sectionopt = document.createElement('option');
			sectionopt.value = 0;
			sectionopt.innerHTML = 'Select Section';
			sectionel.appendChild(sectionopt);

		    // AJAX request
		    var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "gfg.php", true); 
			xhttp.setRequestHeader("Content-Type", "application/json");
			xhttp.onreadystatechange = function() {
			   	if (this.readyState == 4 && this.status == 200) {
			     	// Response
			     	var response = JSON.parse(this.responseText);
			     	
			     	var len = 0;
		            if(response != null){
		               len = response.length;
		            }
		           
		            if(len > 0){
		               	// Read data and create <option >
		               	for(var i=0; i<len; i++){

		                  	var id = response[i].id;
		                  	var name = response[i].name;

		                  	// Add option to state dropdown
		                  	var opt = document.createElement('option');
						    opt.value = id;
						    opt.innerHTML = name;
						    sectionel.appendChild(opt);

		               	}
		            }
			   	}
			};
			var data = {request:'getSection',section_id: section_id};
			xhttp.send(JSON.stringify(data));
	}

	
</script>