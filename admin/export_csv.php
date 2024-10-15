
<?php 
 
// Load the database configuration file 

if(isset($_GET['course']) and isset($_GET['year']) and isset($_GET['secid']) and isset($_GET['semester'])){
// Fetch records from database 
$course =$_GET['course'];
$year =$_GET['year'];
$secid =$_GET['secid'];
$semester =$_GET['semester'];
$query = $conn->query("SELECT * FROM fees "); 
$query1 = $conn->query("SELECT * FROM loading INNER JOIN roomlist r ON loading.rooms = r.room_id INNER JOIN faculty f ON loading.faculty=f.id where course = '$secid' and semester='$semester' order by timeslot_sid asc"); 
 
if($query1->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "class_schedule|$secid-$semester|semester.csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('Subject Code', 'Subject Description', 'Lec Unit' , 'Lab Unit', 'Days', 'Time Schedule', 'Room', 'Instructor'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($rows = $query1->fetch_assoc()){ 
          $fname = $rows['firstname'];
          $lname = $rows['lastname'];
          $mname = $rows['middlename'];
          $name = "$lname, $fname $mname";
          
        
        $lineData = array($rows['subjects'], $rows['sub_description'], $rows['lec_units'], $rows['lab_units'], $rows['days'], $rows['timeslot'], $rows['room_name'],$name,); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
else{
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
exit; 
}
 
?>
