<?php include('db_connect.php');?>
<?php
function generateRow($conn){
		$content = '';
        $content2 = '';
        $content3 = '';
        $content4 = '';

        $content ='<h1>Saturday</h1><table  border="0.5" cellspacing="0" cellpadding="3" class="table table-bordered waffle no-grid" id="insloadtable">
							<thead>
								<tr>
									<th class="text-center">Time</th>';
                                    $rooms = array();
                                    $roomsdata = $conn->query("SELECT * FROM roomlist order by room_id;");
                                    while($r=$roomsdata->fetch_assoc()){
                                    $room = $r['room_name'];
                                    $rooms[] = $r['room_name'];
                                    }
                                    $times = array();
                                    $timesdata = $conn->query("SELECT * FROM timeslot Where schedule='Sat' order by time_id;");
                                    while($t=$timesdata->fetch_assoc()){
                                    $timeslot = $t['timeslot'];
                                    $times[] = $t['timeslot'];
                                    // print_r($times);
                                    }
                
                                    // // Define time and room variables
                                    // $times = array("9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM");
                                    // $rooms = array("Room 1", "Room 2", "Room 3", "Room 4");
                
                                    // Create table header
                                    // echo '<table class="table table-bordered waffle no-grid" id="insloadtable">';
                                    // echo '<thead><tr><th class="text-center">Time</th>';
                                    foreach ($rooms as $room) {
                                        $content .= '<th class="text-center">'.$room.'</th>';
                                    }
                                    $content .= "</tr></thead>";
                
                                    // Create table body
                                    $content .= "<tbody>";
                                    foreach ($times as $time) {
                                        $content .= "<tr><td>$time</td>";
                                    foreach ($rooms as $room) {
                                    // Query database for events in this time and room
                                    $query = "SELECT * FROM loading WHERE timeslot='$time' AND room_name='$room' AND days ='Sat'";
                                    $result = mysqli_query($conn, $query);
                                    if (mysqli_num_rows($result) > 0) {
                                    // Display event information
                                    $row = mysqli_fetch_assoc($result);
                                    $course = $row['course'];
                                    $subject = $row['subjects'];
                                    $faculty = $row['faculty'];
                                    $load_id = $row['id'];
                                    $scheds = $subject." ".$course;
                                    $faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM faculty Where id=".$faculty);
                                    foreach ($faculty as $frow) {
                                    $instname = $frow['name'];
                                    $newSched= $scheds." ".$instname;
                                    }
                                    $content .= '<td class="s9 text-center content" data-id = "'.$load_id.'" data-scode="'.$subject.'" dir="ltr">'.$newSched.'</td>';
                                    } else {
                                    // Display empty cell
                                    $content .= "<td></td>";
                                    }
                                    }
                                    $content .= "</tr>";
                                    }
                                    $content .= "</tbody>";
return $content;
}



require_once('../tcpdf/tcpdf.php');
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Room Assignment');
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage();
    $content = '';
    $content .= generateRow($conn);
    $content .= '</table>';
    $pdf->writeHTML($content);
    $pdf->Output('roomassign.pdf', 'I');
?>