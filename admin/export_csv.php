<?php
session_start();
include('db_connect.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id'] ?? null; // Get the department ID from the session

if(isset($_GET['course']) && isset($_GET['year']) && isset($_GET['secid']) && isset($_GET['semester'])) {
    // Fetch records from database 
    $course = $_GET['course'];
    $year = $_GET['year'];
    $secid = $_GET['secid'];
    $semester = $_GET['semester'];

    $query = $conn->query("SELECT * FROM fees"); 
    if (!$query) {
        die("Database query failed: " . $conn->error); // Debugging output
    }

    $query1 = $conn->query("SELECT * FROM loading 
        INNER JOIN roomlist r ON loading.rooms = r.room_id 
        INNER JOIN faculty f ON loading.faculty = f.id 
        WHERE course = '$secid' AND semester = '$semester' 
        ORDER BY timeslot_sid ASC"); 

    if ($query1 && $query1->num_rows > 0) { 
        $delimiter = ","; 
        $filename = "class_schedule|$secid-$semester|semester.csv"; 

        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 

        // Set column headers 
        $fields = array('Subject Code', 'Subject Description', 'Lec Unit', 'Lab Unit', 'Days', 'Time Schedule', 'Room', 'Instructor'); 
        fputcsv($f, $fields, $delimiter); 

        // Output each row of the data, format line as csv and write to file pointer 
        while($rows = $query1->fetch_assoc()) { 
            $fname = $rows['firstname'];
            $lname = $rows['lastname'];
            $mname = $rows['middlename'];
            $name = "$lname, $fname $mname";

            $lineData = array($rows['subjects'], $rows['sub_description'], $rows['lec_units'], $rows['lab_units'], $rows['days'], $rows['timeslot'], $rows['room_name'], $name); 
            fputcsv($f, $lineData, $delimiter); 
        } 

        // Move back to the beginning of the file 
        fseek($f, 0); 

        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 

        // Output all remaining data on a file pointer 
        fpassthru($f); 
        fclose($f); // Close the file pointer
        exit; // Ensure no further code is executed
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; // Ensure no further code is executed
    }
} else {
    // Redirect if required parameters are not set
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit; // Ensure no further code is executed
}
?>
