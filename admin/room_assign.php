<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM schedules where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
if(!empty($repeating_data)){
$rdata= json_decode($repeating_data);
	foreach($rdata as $k => $v){
		 $$k = $v;
	}
	$dow_arr = isset($dow) ? explode(',',$dow) : '';
	// var_dump($start);
}
}
?>
<style>
	
	
</style>
<div class="container-fluid">
	<form action="" id="manage-schedule">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-16">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Faculty</label>
						<select name="faculty_id" id="" class="custom-select select2">
							<option value="0">All</option>
						<?php 
							$faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty order by concat(lastname,', ',firstname,' ',middlename) asc");
							while($row= $faculty->fetch_array()):
						?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
                                <label for="subject" class="col-sm-3 control-label">Subject</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="subject" id="subject" required onchange="populatedesc(this.value)">
                                    <option value="" disabled selected>Select Subject</option>
                                    <?php
                                    $sql = "SELECT * FROM subjects";
                                    $query = $conn->query($sql);
                                    while($prow = $query->fetch_assoc()){
                                        echo "
                                        <option value='".$prow['subject']."'>".$prow['subject']."</option>
                                        ";
									
                                    }
                                    ?>
                                </select>
                                </div>
                        </div>
					<div class="form-group">
						<label for="description" class="control-label">Description</label>
						<textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Units</label>
						<input type="text" name="units" id="units" class="form-control">
					</div>
					<div class="form-group">
                                <label for="room" class="col-sm-3 control-label">Room</label>

                                <div class="col-sm-12">
                                <select class="form-control" name="room" id="room" required onchange="populatetime(this.value)">
                                    <option value="" disabled selected>Select Room</option>
                                    <?php
                                    $sql = "SELECT * FROM rooms where status = 0";
                                    $query = $conn->query($sql);
                                    while($prow = $query->fetch_assoc()){
                                        echo "
                                        <option value='".$prow['id']."'>".$prow['room_name']."</option>
                                        ";
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
					<div class="form-group">
						<label for="course" class="col-sm-3 control-label">Course</label>

						<div class="col-sm-12">
						<select class="form-control" name="course" id="course" required onchange="populateYear(this.value)">
							<option value="" disabled selected>Select Course</option>
							<?php
							$sql = "SELECT * FROM courses";
							$query = $conn->query($sql);
							while($prow = $query->fetch_assoc()){
								echo "
								<option value='".$prow['course']."'>".$prow['course']."</option>
								";
								$course = $row['course'];
							}
							?>
						</select>
						</div>
					</div>
					<div class="form-group">
								<label for="year" class="col-sm-3 control-label">Year</label>

								<div class="col-sm-12">
								<select class="form-control" name="year" id="year" required onchange="populateSection(this.value)">
									<option value="0" selected>Select Year</option>
								</select>
								</div>
							</div>
							<div class="form-group">
								<label for="section" class="col-sm-3 control-label">Section</label>

								<div class="col-sm-12">
								<select class="form-control" name="section" id="section" required>
									<option value="0" selected disabled>Select Section</option>
								</select>
								</div>
							</div>
					<div class="form-group">
						<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="1" id="is_repeating" name="is_repeating" <?php echo isset($is_repeating) && $is_repeating != 1 ? '' : 'checked' ?>>
						  <label class="form-check-label" for="type">
						   	Weekly Schedule
						  </label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group for-repeating">
						<label for="dow" class="control-label">Days of Week</label>
						<select name="dow[]" id="dow" class="custom-select select2" multiple="multiple">
							<?php 
							$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
							for($i = 0; $i < 7;$i++):
							?>
							<option value="<?php echo $i ?>"  <?php echo isset($dow_arr) && in_array($i,$dow_arr) ? 'selected' : ''  ?>><?php echo $dow[$i] ?></option>
						<?php endfor; ?>
						</select>
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month From</label>
						<input type="month" name="month_from" id="month_from" class="form-control" value="<?php echo isset($start) ? date("Y-m",strtotime($start)) : '' ?>">
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month To</label>
						<input type="month" name="month_to" id="month_to" class="form-control" value="<?php echo isset($end) ? date("Y-m",strtotime($end)) : '' ?>">
					</div>
					<div class="form-group for-nonrepeating" style="display: none">
						<label for="" class="control-label">Schedule Date</label>
						<input type="date" name="schedule_date" id="schedule_date" class="form-control" value="<?php echo isset($schedule_date) ? $schedule_date : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time From</label>
						<input type="time" name="time_from" id="time_from" class="form-control">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time To</label>
						<input type="time" name="time_to" id="time_to" class="form-control">
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
	if('<?php echo isset($id) ? 1 : 0 ?>' == 1){
		if($('#is_repeating').prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	}
	$('#is_repeating').change(function(){
		if($(this).prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	})
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	$('#manage-schedule').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_schedule',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else if(resp==2){
					alert_toast("Error on saving room",'success')

				}else if(resp==0){
					alert_toast("Error on saving data",'error')
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


	function getSched(time){ 
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
		alert(starthours.length)
		alert(startmins.length)
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
		alert(timefrom+" - "+timeto)
        document.getElementById("time_from").value = timefrom;
        document.getElementById("time_to").value = timeto;
    }

	function populatetime(room_name){

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
				var time = response[i].time;
				getSched(time);
				
				}
			}
		}
	};
	var data = {request:'getTime',id: room_name};
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