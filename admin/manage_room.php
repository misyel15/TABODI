<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$user = $conn->query("SELECT * FROM loading where id =".$_GET['id']);
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
	}
?>
<style>
	
	
</style>
<div class="container-fluid">
	<form action="" id="manage-roomschedule">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="col-lg-16">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<div class="col-sm-12">
						<label for="" class="control-label">Faculty</label>
						<select name="faculty" id="" class="custom-select select2">
							<option value="0">All</option>
						<?php 
							$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty order by concat(lastname,', ',firstname,' ',middlename) asc");
							while($row= $faculty->fetch_array()):
						?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($meta['faculty']) && $meta['faculty'] == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
						</select>
						</div>
					</div>
						<div class="form-group">
						<div class="col-sm-12">
							<label for="" class="control-label">Semester</label>
							<select name="semester" id="semester" class="form-control">
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
					<!--<div class="form-group">
						<label for="yrsection" class="col-sm-6 control-label">Year Level</label>

						<div class="col-sm-12">
						<select class="form-control" name="year" id="year" required>
							<option value="0" disabled selected>Select Year</option>
							<?php 
									$sql = "SELECT DISTINCT(year) FROM section ";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
										$year = $row['year'];
									?>
							<option value="<?php echo $year ?>" <?php echo isset($_GET['year']) && $_GET['year'] == $row['year'] ? 'selected' : '' ?>><?php echo ucwords($year) ?></option>
						<?php endwhile; ?>
						</select>
						</div>
					</div>-->
					<div class="form-group">
    <label for="yrsection" class="col-sm-6 control-label">Section</label>

    <div class="col-sm-12">
        <select class="form-control" name="yrsection" id="yrsection" required onchange="populateSubjects()">
            <option value="0" disabled selected>Select Yr. & Sec.</option>
            <?php 
                // Modify the SQL query to include an ORDER BY clause to sort by year
                $sql = "SELECT * FROM section ORDER BY year ASC, section ASC";
                $query = $conn->query($sql);
                while ($row = $query->fetch_array()):
                    $course = $row['course'];
            ?>
            <option value="<?php echo $row['year']."".$row['section'] ?>" <?php echo isset($meta['course']) && $meta['course'] == $row['year']."".$row['section'] ? 'selected' : '' ?>>
                <?php echo ucwords($row['year']." ".$row['section']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>
</div>

					<div class="form-group">
                                <label for="subject" class="col-sm-3 control-label">Subject</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="subject" id="subject" required>
                                    <option value="" disabled selected>Select Subject</option>
									<?php 
									$sql = "SELECT * FROM subjects";
									$query = $conn->query($sql);
									while($prow= $query->fetch_array()):
									?>
							<option value="<?php echo $prow['subject'] ?>" <?php echo isset($meta['subjects']) && $meta['subjects'] == $prow['subject'] ? 'selected' : '' ?>><?php echo ucwords($prow['subject']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                        </div>
						<input type="hidden" name="description" id="description" value="<?php echo isset($meta['sub_description']) ? $meta['sub_description'] : '' ?>">
						<input type="hidden" name="total_units" id="total_units" value="<?php echo isset($meta['total_units']) ? $meta['total_units'] : '' ?>">
						<input type="hidden" name="lec_units" id="lec_units" value="<?php echo isset($meta['lec_units']) ? $meta['lec_units'] : '' ?>">
						<input type="hidden" name="lab_units" id="lab_units" value="<?php echo isset($meta['lab_units']) ? $meta['lab_units'] : '' ?>">
						<input type="hidden" name="room_name" id="room_name" value="<?php echo isset($meta['room_name']) ? $meta['room_name'] : '' ?>">
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
							<option value="<?php echo $row['room_id'] ?>" <?php echo isset($meta['rooms']) && $meta['rooms'] == $row['room_id'] ? 'selected' : '' ?>><?php echo ucwords($row['room_name']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                            </div>
							<div class="form-group">
						<div class="col-sm-12">
								<label for="specialization" class="control-label">Days of Week</label>

								<select class="form-control" name="days" id="days">
									<option value="" disabled selected>Select Days of Week</option>
									<?php 
									$sql = "SELECT * FROM days";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
									?>
							<option value="<?php echo $row['days'] ?>" <?php echo isset($meta['days']) && $meta['days'] == $row['days'] ? 'selected' : '' ?>><?php echo ucwords($row['days']) ?></option>
						<?php endwhile; ?>
								</select>
								</div>
							</div>
							<input type="hidden" name="timeslot" id="timeslot" value="<?php echo isset($meta['timeslot']) ? $meta['timeslot'] : '' ?>">
							<div class="form-group">
                                <label for="room" class="col-sm-3 control-label">Timeslot</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="timeslot_id" id="timeslot_id" required>
                                    <option value="" disabled selected>Select Timeslot</option>
									<?php 
									$sql = "SELECT * FROM timeslot";
									$query = $conn->query($sql);
									while($row= $query->fetch_array()):
									?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($meta['timeslot_id']) && $meta['timeslot_id'] == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['timeslot']." ".$row['schedule']) ?></option>
						<?php endwhile; ?>
                                </select>
                                </div>
                            </div>
							<div class="form-group">
							<div class="col-sm-12">
								<input type="hidden" class="form-control" name="hours" id="hours" value="<?php echo isset($meta['hours']) ? $meta['hours'] : ''?>">
								<input type="hidden" name="timeslot_sid" id="timeslot_sid" value="<?php echo isset($meta['timeslot_sid']) ? $meta['timeslot_sid'] : '' ?>">
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	$('#manage-roomschedule').submit(function(e){
    e.preventDefault();
   
	if (!validateForm()) {
            Swal.fire({
                icon: 'warning',
                title: 'warning!',
                text: 'Please fill in all required fields.',
                showConfirmButton: true
            });
            return;
        }
    $('#msg').html('');
    $.ajax({
        url: 'ajax.php?action=save_roomschedule',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp){
            if (resp == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data successfully saved',
                   
                    showConfirmButton: true
                }).then(() => {
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                });
            } else if (resp == 2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Room and Timeslot already exist',
                   
                    showConfirmButton: true
                }).then(() => {
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred'
                });
            }
        }
    });
});

function validateForm() {
        let isValid = true;
        const requiredFields = ['faculty', 'semester', 'course', 'yrsection', 'subject', 'room', 'days', 'timeslot_id'];
        
        requiredFields.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (!element || element.value === '' || element.value === '0') {
                isValid = false;
                element.classList.add('is-invalid');
            } else {
                element.classList.remove('is-invalid');
            }
        });

        return isValid;
    }


	document.getElementById('timeslot_id').addEventListener('change', function() {
		var timeslot_id = document.getElementById("timeslot_id").value;
		populatetime(timeslot_id);
		populateHours(timeslot_id);
	});

	document.getElementById('days').addEventListener('change', function() {
		var subject = document.getElementById("subject").value;
		var days = document.getElementById("days").value;
		populatedesc(subject);
		populateTimeslot(subject,days);
	});
	document.getElementById('room').addEventListener('change', function() {
		var room_id = document.getElementById("room").value;
		// var course = e.options[e.selectedIndex].getAttribute('data-course');
		populateRoomname(room_id)
	});

	/*document.getElementById('room').addEventListener('change', function() {
		getSched();
	});*/	
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
		function populateRoomname(room_id){

			var room_name = document.getElementById('room_name');


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
					var room = response[i].room_name;
					room_name.value = room;
					
					}
				}
			}
			};
			var data = {request:'getRoomName',id: room_id};
			xhttp.send(JSON.stringify(data));
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

	function populateHours(timeslot_id){

		var hours = document.getElementById('hours');
		var timeid = document.getElementById('timeslot_sid');


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

						var hoursdata = response[i].hours;
						var timeiddata = response[i].timeslot_sid;

						hours.value = hoursdata;
						timeid.value = timeiddata;
						}
					}
				}
			};
			var data = {request:'getHours',id: timeslot_id};
			xhttp.send(JSON.stringify(data));
		}

		function populatedesc(subject){

var desc = document.getElementById('description');
var total_units = document.getElementById('total_units');
var lec_units = document.getElementById('lec_units');
var lab_units = document.getElementById('lab_units');


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
				var unitsdata = response[i].total_units;
				var lecunitsdata = response[i].lec_units;
				var labunitsdata = response[i].lab_units;

				desc.value = descriptiondata;
				total_units.value = unitsdata;
				lec_units.value = lecunitsdata;
				lab_units.value = labunitsdata;
				
				}
			}
		}
	};
	var data = {request:'getDesc',id: subject};
	xhttp.send(JSON.stringify(data));
}

			function populateSubjects(){
				var e = document.getElementById('yrsection');
				var sem = document.getElementById('semester');
				var semester = sem.options[sem.selectedIndex].value;
				var course = e.options[e.selectedIndex].getAttribute('data-course');
				var year = e.options[e.selectedIndex].getAttribute('data-year');
				//var attrs = option.attributes;
				var courseyear = course +" "+year;
				console.log(course);
				console.log(year);
				console.log(semester);


				var subjectel = document.getElementById('subject');

				subjectel.innerHTML = "";
				
				var subjectopt = document.createElement('option');
				subjectopt.value = 0;
				subjectopt.innerHTML = 'Select Subject';
				subjectel.appendChild(subjectopt);


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
									var specialization = response[i].specialization;

									// Add option to state dropdown
									var opt = document.createElement('option');
									opt.value = id;
									opt.innerHTML = name;
									
									const special = document.createAttribute("data-special");
									// Set the value of the class attribute:
									special.value = specialization;
									opt.setAttributeNode(special);
									subjectel.appendChild(opt);

								}
							}
						}
					};
					var data = {request:'getSubjects',course: course, year: year,semester: semester};
					xhttp.send(JSON.stringify(data));
	}

	function populateTimeslot(subject,days){
				var e = document.getElementById('subject');
				var specialization = e.options[e.selectedIndex].getAttribute('data-special');
				console.log(subject)
				console.log(days)
				console.log(specialization)


				var timeslotel = document.getElementById('timeslot_id');

				timeslotel.innerHTML = "";
				
				var timeslotopt = document.createElement('option');
				timeslotopt.value = 0;
				timeslotopt.innerHTML = 'Select Timeslot';
				timeslotel.appendChild(timeslotopt);


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

									timeslotel.appendChild(opt);

								}
							}
						}
					};
					var data = {request:'getTimeslot',subject: subject,days: days,specialization: specialization};
					xhttp.send(JSON.stringify(data));
	}

	function populateYear(course){

var yrsectionel = document.getElementById('yrsection');

yrsectionel.innerHTML = "";

var yearopt = document.createElement('option');
yearopt.value = 0;
yearopt.innerHTML = 'Select Yr. & Sec.';
yrsectionel.appendChild(yearopt);


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
					var course = response[i].course;
					var year = response[i].year;

					// Add option to state dropdown
					var opt = document.createElement('option');
					opt.value = id;
					opt.innerHTML = name;

					const attcourse = document.createAttribute("data-course");
					const attyear = document.createAttribute("data-year");


					// Set the value of the class attribute:
					attcourse.value = course;
					attyear.value = year;

					opt.setAttributeNode(attcourse);
					opt.setAttributeNode(attyear);
					yrsectionel.appendChild(opt);

				}
			}
		}
	};
	var data = {request:'getYear',course: course};
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