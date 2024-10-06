<?php include('db_connect.php');?>

<div class="container-fluid">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<center><h3>Monday/Wednesday</h3></center>
						<br>
						<span class="float:right">
							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-left"  id="new_schedule_mw">
					<i class="fa fa-plus"></i> New Entry
				</button>
				<div class="dropdown">
				<button class="btn btn-danger btn-sm col-sm-2 float-right dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Delete Table
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item delete_MW" data-day="MW">Monday/Wednesday</a>
					<a class="dropdown-item delete_TTh" data-day="TTH">Tuesday/Thursday</a>
					<a class="dropdown-item delete_Fri" data-day="F">Friday</a>
					<a class="dropdown-item delete_Sat" data-day="Sat">Saturday</a>
				</div>
				</div>
				<form method="POST" class="form-inline" id="printra" action="roomassign_generate.php" style="margin-left: 520px;">
						<button type="button" class="btn btn-success float-center btn-sm btn-flat" id="print"><span class="glyphicon glyphicon-print"></span> Print</button>
						</form></span>
						
                        </div>
					<div class="card-body">
<?php

// $rooms = $conn->query("SELECT DISTINCT room_name FROM roomlist order by id;");
$rooms = array();
$roomsdata = $conn->query("SELECT * FROM roomlist order by id;");
while($r=$roomsdata->fetch_assoc()){
	$room = $r['room_name'];
	$rooms[] = $r['room_name'];
}
$times = array();
$timesdata = $conn->query("SELECT * FROM timeslot Where schedule='MW' order by id;");
while($t=$timesdata->fetch_assoc()){
	$timeslot = $t['timeslot'];
	$times[] = $t['timeslot'];
    // print_r($times);
}

// // Define time and room variables
// $times = array("9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM");
// $rooms = array("Room 1", "Room 2", "Room 3", "Room 4");

// Create table header
echo '<table class="table table-bordered waffle no-grid" id="insloadtable">';
echo '<thead><tr><th class="text-center">Time</th>';
foreach ($rooms as $room) {
    echo '<th class="text-center">$room</th>';
}
echo "</tr></thead>";

// Create table body
echo "<tbody>";
foreach ($times as $time) {
    echo "<tr><td>$time</td>";
    foreach ($rooms as $room) {
        // Query database for events in this time and room
        $query = "SELECT * FROM loading WHERE timeslot='$time' AND rooms='$room'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Display event information
            $row = mysqli_fetch_assoc($result);
			$course = $row['course'];
			$subject = $row['subjects'];
            $faculty = $row['faculty'];
			$scheds = $subject." ".$course;
            $faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty);
                foreach ($faculty as $frow) {
                    $instname = $frow['name'];
                    $newSched= $scheds." ".$instname;
                }
            echo '<td class="s9 text-center content">'.$newSched.'</td>';
        } else {
            // Display empty cell
            echo "<td></td>";
        }
    }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

// Close MySQL connection
mysqli_close($conn);
?>
