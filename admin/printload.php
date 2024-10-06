<?php 
    include 'db_connect.php';
?>

        <div id="ranking">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Days</th>
                    <th class="text-center">Time</th>
                    <th class="text-center">Course code</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Units</th>
                    <th class="text-center">Room</th>
                    <th class="text-center">Instructor</th>
                    <th class="text-center">Action</th>
                </tr>
                <?php 
								$i = 1;
								$schedules = $conn->query("SELECT * FROM schedules order by id asc");
								while($row=$schedules->fetch_assoc()):
									$id = $row['faculty_id'];
									$days = "";
									$day1 = "";
									$day2 = "";
									$day3 = "";
									$faculty = $conn->query("SELECT * FROM faculty WHERE id = '$id'");
								while($facultyrow=$faculty->fetch_assoc()):
									$result = $row['repeating_data'];
									if($result != null){
										$data = json_decode($result);
										$days = $data->dow;
										$daysarr = array($days);
										if(strlen($daysarr[0]) >= 1){
										$dayone = $daysarr[0][0];
										}else{
										$dayone = 0;
										}
										if(strlen($daysarr[0]) >= 3){
										$daytwo = $daysarr[0][2];
										}else{
										$daytwo = 0;
										}
										if(strlen($daysarr[0]) >= 5){
										$daythree = $daysarr[0][4];
										}else{
										$daythree = 0;
										}
										switch($dayone){
											case 0:
												$day1 = "";
											break;
											case 1:
												$day1 = "M";
											break;
											case 2:
												$day1 = "T";
											break;
											case 3:
												$day1 = "W";
											break;
											case 4:
												$day1 = "TH";
											break;
											case 5:
												$day1 = "F";
											break;
											case 6:
												$day1 = "S";
											break;
										}
										switch($daytwo){
											case 0:
												$day2 = "";
											break;
											case 1:
												$day2 = "M";
											break;
											case 2:
												$day2 = "T";
											break;
											case 3:
												$day2 = "W";
											break;
											case 4:
												$day2 = "TH";
											break;
											case 5:
												$day2 = "F";
											break;
											case 6:
												$day2 = "S";
											break;
										}
										switch($daythree){
											case 0:
												$day3 = "";
											break;
											case 1:
												$day3 = "M";
											break;
											case 2:
												$day3 = "T";
											break;
											case 3:
												$day3 = "W";
											break;
											case 4:
												$day3 = "TH";
											break;
											case 5:
												$day3 = "F";
											break;
											case 6:
												$day3 = "S";
											break;
										}

										$schedDays = $day1.$day2.$day3;
										//printf($day1.",".$day2.",".$day3);
                                        echo '<tr>
                                        <td scope="row">' . $i++ . '</td>
                                        <td>' . isset($schedDays) ? $schedDays : '' . '</td>
                                        <td>' . $row['time_from']." - ".$row['time_to'] . '</td>
                                        <td>' . $row['course_code'] . '</td>
                                        <td>' . $row['subject_description'] . '</td>
                                        <td>' . $row['units'] . '</td>
                                        <td>' . $row['room'] . '</td>
                                        <td>' . $facultyrow['firstname']." ".$facultyrow['middlename']." ". $facultyrow['lastname'] . '</td>
                                        </tr>';
									}
                                    else{
                                    echo '<tr>
                                        <td colspan="4">There are no users!</td>
                                    </tr>';
                                    }
								?>
                </tbody>
            </table>
        </div>