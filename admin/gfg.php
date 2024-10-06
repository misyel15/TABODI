<?php
include('db_connect.php');

// Read POST data
$postData = json_decode(file_get_contents("php://input"));
$request = isset($postData->request) ? $postData->request : "";

// Initialize result arrays
$result = array();
$data = array();

// Handle requests
switch ($request) {
   case 'getYear':
      if (isset($postData->course)) {
         $course = $postData->course;

         $sql = "SELECT * FROM section WHERE course=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $course);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $id = $row['year'] . "" . $row['section'];
            $name = $row['year'] . "" . $row['section'];
            $course = $row['course'];
            $year = $row['year'];

            $data[] = array(
               "id" => $id,
               "name" => $name,
               "course" => $course,
               "year" => $year
            );
         }
      }
      break;

   case 'getSubjects':
      if (isset($postData->course) && isset($postData->year) && isset($postData->semester)) {
         $course = $postData->course;
         $year = $postData->year;
         $semester = $postData->semester;

         $sql = "SELECT * FROM subjects WHERE course=? AND year=? AND semester=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("sss", $course, $year, $semester);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $id = $row['subject'];
            $name = $row['subject'];
            $specialization = $row['specialization'];

            $data[] = array(
               "id" => $id,
               "name" => $name,
               "specialization" => $specialization
            );
         }
      }
      break;

   case 'getTimeslot':
      if (isset($postData->subject) && isset($postData->days) && isset($postData->specialization)) {
         $subject = $postData->subject;
         $days = $postData->days;
         $specialization = $postData->specialization;

         $sql = "SELECT * FROM timeslot WHERE schedule=? AND specialization=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("ss", $days, $specialization);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $time_data = $row['timeslot'] . " " . $row['schedule'];
            $id = $row['id'];

            $data[] = array(
               "id" => $id,
               "name" => $time_data
            );
         }
      }
      break;

   case 'getSection':
      if (isset($postData->section_id)) {
         $section_id = $postData->section_id;

         $sql = "SELECT * FROM section WHERE year=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $section_id);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $id = $row['section'];
            $name = $row['section'];

            $data[] = array(
               "id" => $id,
               "name" => $name
            );
         }
      }
      break;

   case 'getDesc':
      if (isset($postData->id)) {
         $id = $postData->id;

         $sql = "SELECT * FROM subjects WHERE subject=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $id);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $description = $row['description'];
            $total_units = $row['total_units'];
            $lec_units = $row['Lec_Units'];
            $lab_units = $row['Lab_Units'];

            $data[] = array(
               "description" => $description,
               "total_units" => $total_units,
               "lec_units" => $lec_units,
               "lab_units" => $lab_units
            );
         }
      }
      break;

   case 'getHours':
      if (isset($postData->id)) {
         $id = $postData->id;

         $sql = "SELECT * FROM timeslot WHERE id=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("i", $id);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $hours = $row['hours'];
            $timeslot_sid = $row['time_id'];

            $data[] = array(
               "hours" => $hours,
               "timeslot_sid" => $timeslot_sid
            );
         }
      }
      break;

   case 'getTime':
      if (isset($postData->id)) {
         $id = $postData->id;

         $sql = "SELECT * FROM timeslot WHERE id=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("i", $id);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $time = $row['timeslot'];

            $data[] = array(
               "id" => $id,
               "timeslot" => $time
            );
         }
      }
      break;

   case 'getRoomName':
      if (isset($postData->id)) {
         $id = $postData->id;

         $sql = "SELECT * FROM roomlist WHERE room_id=?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("i", $id);
         $stmt->execute();
         $result = $stmt->get_result();

         while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $room_name = $row['room_name'];

            $data[] = array(
               "id" => $id,
               "room_name" => $room_name
            );
         }
      }
      break;

   default:
      // Handle unrecognized requests
      break;
}

// Output JSON response
echo json_encode($data);

// Close database connection
$stmt->close();
$conn->close();
die();
?>
