<?php include('db_connect.php');?>
<?php 
 
// Load the database configuration file 

if(isset($_GET['secid']) and isset($_GET['semester']) and isset($_GET['year']) and isset($_GET['course'])){
// Fetch records from database 
$secid =$_GET['secid'];
$semester =$_GET['semester'];
$year =$_GET['year'];
$course =$_GET['course'];
$query = $conn->query("SELECT * FROM fees WHERE semester='$semester' AND course='$course' AND year='$year' GROUP BY id DESC LIMIT 1  "); 
$query1 = $conn->query("SELECT * FROM loading INNER JOIN roomlist r ON loading.rooms = r.id INNER JOIN faculty f ON loading.faculty=f.id where course = '$secid' and semester='$semester' order by timeslot_sid asc"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "fees|$year|$semester.csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('library','computer','school_id','athletic','admission','development','guidance','handbook','entrance','registration','medical','cultural'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($rows = $query->fetch_assoc()){ 
          
        $lineData = array($rows['library'], $rows['computer'], $rows['school_id'], $rows['athletic'], $rows['admission'], $rows['development'], $rows['guidance'], $rows['handbook'], $rows['entrance'], $rows['registration'], $rows['medical'], $rows['cultural']); 
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