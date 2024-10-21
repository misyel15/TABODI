<?php
session_start();
ini_set('display_errors', 1);

class Action {
    private $db;

    public function __construct() {
        ob_start();
        include 'db_connect.php'; // Ensure db_connect.php contains your database connection logic
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
    }
	
	

	function login_faculty() {
		// Start the session if not already started
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	
		// Extract POST variables and sanitize input
		$id_no = htmlspecialchars(trim($_POST['id_no']), ENT_QUOTES, 'UTF-8');
	
		// Prepare the SQL statement to prevent SQL injection
		$stmt = $this->db->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id_no = ?");
		$stmt->bind_param("s", $id_no);  // "s" indicates that the parameter is a string
		$stmt->execute();
	
		// Get the result
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			// Fetch the user data
			$user_data = $result->fetch_assoc();
	
			// Store relevant user data in the session, escaping the output
			foreach ($user_data as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				}
			}
			return 1;  // Successful login
		} else {
			return 3;  // Invalid ID number
		}
	}
	
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../home.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", email = '$email' ";
		$data .= ", course = '$course' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		//$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}

			return 1;
				}
	}
	function save_course() {
		extract($_POST);
	
		// Ensure that $dept_id, $course, and $description are properly set
		$data = "dept_id = '$dept_id', "; // Start with dept_id
		$data .= "course = '$course', "; // Append course
		$data .= "description = '$description' "; // Append description
	
		// Check for duplicate course
		$check_duplicate = $this->db->query("SELECT * FROM courses WHERE course = '$course' AND id != '$id'");
		if ($check_duplicate->num_rows > 0) {
			// Duplicate course found, return 0
			return 0;
		}
	
		// Check if the ID is empty to determine whether to insert or update
		if (empty($id)) {
			// Insert new course
			$save = $this->db->query("INSERT INTO courses SET $data");
		} else {
			// Update existing course
			$save = $this->db->query("UPDATE courses SET $data WHERE id = $id");
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = empty($id) ? 'New course added: ' . $course : 'Course updated: ' . $course;
		$status = $save ? 'unread' : 'read'; // Mark notification as unread
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
		}
	
		// Return success status
		return $save ? 1 : 2; // Return 2 in case of failure
	}
	function delete_course() {
		extract($_POST);
	
		// Attempt to delete the course
		$delete = $this->db->query("DELETE FROM courses WHERE id = ".$id);
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'Course deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	

	function save_subject() {
		extract($_POST);
		// Assuming the dept_id is stored in the session
		$dept_id = $_SESSION['dept_id'];
	
		// Build the data string with dept_id included
		$data = "subject = '$subject', ";
		$data .= "description = '$description', ";
		$data .= "Lec_units = '$lec_units', ";
		$data .= "Lab_units = '$lab_units', ";
		$data .= "hours = '$hours', ";
		$data .= "total_units = '$units', ";
		$data .= "course = '$course', ";
		$data .= "year = '$cyear', ";
		$data .= "semester = '$semester', ";
		$data .= "specialization = '$specialization', ";
		$data .= "dept_id = '$dept_id' "; // Add dept_id to the data string
	
		// Check for duplicate subject
		$check_duplicate = $this->db->query("SELECT * FROM subjects WHERE subject = '$subject' AND id != '$id'");
		if ($check_duplicate->num_rows > 0) {
			// Duplicate subject found, return error
			return 0; // or handle the error appropriately
		}
	
		if (empty($id)) {
			// Insert new subject
			$save = $this->db->query("INSERT INTO subjects SET $data");
		} else {
			// Update existing subject
			$save = $this->db->query("UPDATE subjects SET $data WHERE id = $id");
		}
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = empty($id) ? 'New subject added: ' . $subject : 'Subject updated: ' . $subject;
		$status = $save ? 'unread' : 'read'; // Mark notification as unread
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
		}
	
		// Return success status
		return $save ? 1 : 2; // Return 2 in case of failure
	}
	
	function save_room() {
		extract($_POST);
		$data = " room_name = '$room' ";
		$data .= ", room_id = '$room_id' ";
	
		// Ensure dept_id is extracted from the session
		$dept_id = $_SESSION['dept_id']; // Retrieve dept_id from session
		$data .= ", dept_id = '$dept_id' "; // Add dept_id to the data string
	
		// Check for duplicate room name or ID within the same department
		$check = $this->db->query("SELECT * FROM roomlist WHERE (room_name = '$room' OR room_id = '$room_id') AND dept_id = '$dept_id'");
		if ($check->num_rows > 0) {
			return 3; // Return a specific code for duplicate entry
		}
	
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO roomlist SET $data");
		} else {
			$save = $this->db->query("UPDATE roomlist SET $data WHERE id = $id");
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = empty($id) ? 'New room added: ' . $room : 'Room updated: ' . $room;
		$status = $save ? 'unread' : 'read'; // Mark notification as unread
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
		}
	
		// Return success status
		return $save ? 1 : 2; // Return 2 in case of failure
	}
	
	function save_timeslot() {
		extract($_POST);
		
		// Ensure dept_id is extracted from the session
		$dept_id = $_SESSION['dept_id']; // Retrieve dept_id from session
	
		$data = " time_id = '$time_id' ";
		$data .= ", timeslot = '$timeslot' ";
		//$data .= ", hours = '$hours' "; // Commented out as per original code
		$data .= ", schedule = '$schedule' ";
		$data .= ", specialization = '$specialization' ";
		$data .= ", dept_id = '$dept_id' "; // Add dept_id to the data string
	
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO timeslot SET $data");
		} else {
			$save = $this->db->query("UPDATE timeslot SET $data WHERE id = $id");
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = empty($id) ? 'New timeslot added: ' . $timeslot : 'Timeslot updated: ' . $timeslot;
		$status = $save ? 'unread' : 'read'; // Mark notification as unread
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
		}
	
		// Return success status
		return $save ? 1 : 2; // Return 2 in case of failure
	}
	
	function save_section() {
		extract($_POST);
		// Assuming the dept_id is stored in the session
		$dept_id = $_SESSION['dept_id'];
	
		// Build the data string with dept_id included
		$data = "course = '$course', ";
		$data .= "year = '$cyear', ";
		$data .= "section = '$section', ";
		$data .= "dept_id = '$dept_id' "; // Add dept_id to the data string
	
		// Check for duplicate section
		if (empty($id)) {
			$check = $this->db->query("SELECT * FROM section WHERE course = '$course' AND year = '$cyear' AND section = '$section'");
		} else {
			$check = $this->db->query("SELECT * FROM section WHERE course = '$course' AND year = '$cyear' AND section = '$section' AND id != '$id'");
		}
	
		if ($check->num_rows > 0) {
			return 3; // Return a specific code for duplicate entry
		}
	
		$save = false; // Initialize $save
		$message = ''; // Initialize message for notification
		$status = 'unread'; // Default status for the notification
	
		if (empty($id)) {
			// Insert new section
			$save = $this->db->query("INSERT INTO section SET $data");
			$message = 'New section added: ' . $section; // Set message for new section
		} else {
			// Update existing section
			$save = $this->db->query("UPDATE section SET $data WHERE id = $id");
			$message = 'Section updated: ' . $section; // Set message for updated section
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
			return empty($id) ? 1 : 2; // Return 1 for insert and 2 for update
		}
	
		return 0; // Return 0 if the save operation fails
	}
	
	
	
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subjects where id = ".$id);
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'Subject deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	function delete_fees(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM fees where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function delete_room(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM roomlist where id = ".$id);
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'Room deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	function delete_timeslot(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM timeslot where id = ".$id);
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'Timeslot deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	function delete_load(){
		extract($_POST);
		$scode = "";
		$lquery = $this->db->query("SELECT * FROM loading WHERE id = ".$id);
		foreach ($lquery as $key) {
			$scode = $key['subjects'];
		}
		$query = $this->db->query("SELECT * FROM subjects WHERE subject='$scode'");
		foreach ($query as $key) {
		$status = $key['status'];
		$newstats = $status + 1;
		$subjectStats = "status =".$newstats;
		$update = $this->db->query("UPDATE subjects set ".$subjectStats." where subject='$scode'");
		}
		$delete = $this->db->query("DELETE FROM loading where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function delete_MW(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loading where days ='$days'");
		if($delete){
			return 1;
		}
	}
	function delete_TTh(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loading where days ='$days'");
		if($delete){
			return 1;
		}
	}
	function delete_Fri(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loading where days ='$days'");
		if($delete){
			return 1;
		}
	}
	function delete_Sat(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loading where days ='$days'");
		if($delete){
			return 1;
		}
	}
	function delete_section(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM section where id = ".$id);
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'Section deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	function save_faculty() {
		extract($_POST);
		$data = '';
	
		// Ensure dept_id is extracted from the session
		$dept_id = $_SESSION['dept_id']; // Retrieve dept_id from session
	
		// Build data string from POST data
		foreach ($_POST as $k => $v) {
			if (!empty($v)) {
				if ($k != 'id') {
					if (empty($data)) {
						$data .= " $k='{$v}' ";
					} else {
						$data .= ", $k='{$v}' ";
					}
				}
			}
		}
	
		// Include dept_id in the data string
		$data .= ", dept_id='{$dept_id}' "; // Add dept_id to the data string
	
		// Generate a unique id_no if it's empty
		if (empty($id_no)) {
			$i = 1;
			while ($i == 1) {
				$rand = mt_rand(1, 99999999);
				$rand = sprintf("%'08d", $rand);
				$chk = $this->db->query("SELECT * FROM faculty WHERE id_no = '$rand'")->num_rows;
				if ($chk <= 0) {
					$data .= ", id_no='$rand' ";
					$i = 0;
				}
			}
		}
	
		// Check for duplicate id_no before saving
		if (empty($id)) {
			if (!empty($id_no)) {
				$chk = $this->db->query("SELECT * FROM faculty WHERE id_no = '$id_no'")->num_rows;
				if ($chk > 0) {
					return 2; // Return code for duplicate id_no
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO faculty SET $data");
			$message = 'New faculty added: ' . $firstname; // Change $name to the appropriate variable for the faculty name
		} else {
			if (!empty($id_no)) {
				$chk = $this->db->query("SELECT * FROM faculty WHERE id_no = '$id_no' AND id != $id")->num_rows;
				if ($chk > 0) {
					return 2; // Return code for duplicate id_no
					exit;
				}
			}
			$save = $this->db->query("UPDATE faculty SET $data WHERE id = " . $id);
			$message = 'Faculty updated: ' . $firstname; // Change $name to the appropriate variable for the faculty name
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$status = 'unread'; // Default status for the notification
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
			return 1; // Return 1 for successful save
		}
	
		return 0; // Return 0 if save operation fails
	}
	
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty where id = ".$id);
		
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = $delete ? 'faculty deleted: ' . $id : 'Course deletion failed: ' . $id;
		$status = $delete ? 'unread' : 'read'; // Mark notification as unread if deletion was successful
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record
		$this->db->query("INSERT INTO notifications (user_id, message, status, created_at) 
						  VALUES ('$user_id', '$message', '$status', '$timestamp')");
	
		// Query to count the total number of unread notifications
		$notification_count_query = $this->db->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE status = 'unread'");
		$notification_count = $notification_count_query->fetch_assoc()['unread_count'];
	
		// Return success or failure status
		return $delete ? 1 : 2; // Return 1 for success, 2 for failure
	}
	
	function save_roomschedule() {
		extract($_POST);
		
		// Ensure dept_id is extracted from the session
		$dept_id = $_SESSION['dept_id']; // Retrieve dept_id from session
	
		$data = " timeslot_id = '$timeslot_id' ";
		$data .= ", timeslot = '$timeslot' ";
		$data .= ", rooms = '$room' ";
		$data .= ", faculty = '$faculty' ";
		$data .= ", course = '$yrsection' ";
		$data .= ", subjects = '$subject' ";
		$data .= ", semester = '$semester' ";
		$data .= ", days = '$days' ";
		$data .= ", sub_description = '$description' ";
		$data .= ", total_units = '$total_units' ";
		$data .= ", lec_units = '$lec_units' ";
		$data .= ", lab_units = '$lab_units' ";
		$data .= ", coursedesc = '$course' ";
		$data .= ", hours = '$hours' ";
		$data .= ", timeslot_sid = '$timeslot_sid' ";
		$data .= ", room_name = '$room_name' ";
		$data .= ", dept_id = '$dept_id' "; // Add dept_id to the data string
	
		if (empty($id)) {
			// Decrease the subject status if this is a new entry
			$query = $this->db->query("SELECT * FROM subjects WHERE subject='$subject'");
			foreach ($query as $key) {
				$status = $key['status'];
				$newstats = $status - 1;
				$subjectStats = "status =" . $newstats;
				$this->db->query("UPDATE subjects SET " . $subjectStats . " WHERE subject='$subject'");
			}
	
			// Check for existing loading entry
			$sql = "SELECT * FROM loading WHERE timeslot_id ='$timeslot_id' AND rooms='$room' AND days='$days' AND dept_id='$dept_id'";
			$query = $this->db->query($sql);
	
			if ($query->num_rows == 0) {
				$save = $this->db->query("INSERT INTO loading SET " . $data);
			} else {
				return 2; // Return 2 for duplicate entry
			}
		} else {
			// Decrease the subject status when updating
			$query = $this->db->query("SELECT * FROM subjects WHERE subject='$subject'");
			foreach ($query as $key) {
				$status = $key['status'];
				$newstats = $status - 1;
				$subjectStats = "status =" . $newstats;
				$this->db->query("UPDATE subjects SET " . $subjectStats . " WHERE subject='$subject'");
			}
			$save = $this->db->query("UPDATE loading SET " . $data . " WHERE id=" . $id);
		}
	
		// Prepare notification variables
		$user_id = $_SESSION['user_id']; // Assuming you're storing user_id in session
		$message = empty($id) ? 'New room schedule added: ' . $timeslot : 'Room schedule updated: ' . $timeslot;
		$status = 'unread'; // Mark notification as unread
		$timestamp = date('Y-m-d H:i:s'); // Current timestamp
	
		// Insert notification record if save was successful
		if ($save) {
			$this->db->query("INSERT INTO notifications (user_id, message, status, created_at)
							  VALUES ('$user_id', '$message', '$status', '$timestamp')");
			return 1; // Return 1 for successful save
		}
	
		return 0; // Return 0 if save operation fails
	}
	
	function save_roomscheduletth(){
		extract($_POST);
		$data = " timeslot_id = '$timeslot_id' ";
		$data .= ", timeslot = '$timeslot' ";
		$data .= ", rooms = '$room' ";
		$data .= ", faculty = '$faculty' ";
		$data .= ", semester = '$semester' ";
		$data .= ", course = '$course' ";
		$data .= ", subjects = '$subject' ";
		//$rdata = implode($dow);
		$data .= ", days = '$days' ";
		if(empty($id)){
			$query = $this->db->query("SELECT * FROM subjects WHERE subject='$subject'");
			foreach ($query as $key) {
			$status = $key['status'];
			$newstats = $status - 1;
			$subjectStats = "status =".$newstats;
			$update = $this->db->query("UPDATE subjects set ".$subjectStats." where subject='$subject'");
			}
			$save = $this->db->query("INSERT INTO tthloading set ".$data);
		}else{
			$query = $this->db->query("SELECT * FROM subjects WHERE subject='$subject'");
			foreach ($query as $key) {
			$status = $key['status'];
			$newstats = $status - 1;
			$subjectStats = "status =".$newstats;
			$update = $this->db->query("UPDATE subjects set ".$subjectStats." where subject='$subject'");
			}
			$save = $this->db->query("UPDATE tthloading set ".$data." where id=".$id);
		}
		if($save){
			return 1;
		}
	}
	function save_schedule(){
		extract($_POST);
		$data = " faculty_id = '$faculty_id' ";
		$data .= ", course_code = '$subject' ";
		$data .= ", subject_description = '$description' ";
		$data .= ", units = '$units' ";
		$data .= ", room = '$room' ";
		$data .= ", course = '$course' ";
		$data .= ", year = '$year' ";
		$data .= ", section = '$section' ";
		$status = " status = 1 ";
		if(isset($is_repeating)){
			$data .= ", is_repeating = '$is_repeating' ";
			$rdata = array('dow'=>implode(',', $dow),'start'=>$month_from.'-01','end'=>(date('Y-m-d',strtotime($month_to .'-01 +1 month - 1 day '))));
			$data .= ", repeating_data = '".json_encode($rdata)."' ";
		}else{
			$data .= ", is_repeating = 0 ";
			$data .= ", schedule_date = '$schedule_date' ";
		}
		$data .= ", timeslot = '$timeslot' ";
		$data .= ", time_from = '$time_from' ";
		$data .= ", time_to = '$time_to' ";

		if(empty($id)){
			$saveroom = $this->db->query("UPDATE rooms set ".$status." where id=".$room);
				if($saveroom){

					$save = $this->db->query("INSERT INTO schedules set ".$data);
						
					}
					else{
						return 2;
					}
		}else{
			$save = $this->db->query("UPDATE schedules set ".$data." where id=".$id);
		}
		if($save){
			return 1;
		}else{
			return 0;
		}
			
	}
	function delete_schedule(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM schedules where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_schecdule(){
		extract($_POST);
		$data = array();
		$qry = $this->db->query("SELECT * FROM schedules where faculty_id = 0 or faculty_id = $faculty_id");
		while($row=$qry->fetch_assoc()){
			if($row['is_repeating'] == 1){
				$rdata = json_decode($row['repeating_data']);
				foreach($rdata as $k =>$v){
					$row[$k] = $v;
				}
			}
			$data[] = $row;
		}
			return json_encode($data);
	}
	function get_year(){
		extract($_POST);
		$data = array();
		$qry = $this->db->query("SELECT * FROM section where id = 0 or id = $course_id");
		while($row=$qry->fetch_assoc()){
			if($row['is_repeating'] == 1){
				$rdata = json_decode($row['repeating_data']);
				foreach($rdata as $k =>$v){
					$row[$k] = $v;
				}
			}
			$data[] = $row;
		}
			return json_encode($data);
	}
	function delete_forum(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

		if(empty($id)){
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_comments set ".$data);
		}else{
			$save = $this->db->query("UPDATE forum_comments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_comments where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_event(){
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '".htmlentities(str_replace("'","&#x2019;",$content))."' ";
		if($_FILES['banner']['tmp_name'] != ''){
						$_FILES['banner']['name'] = str_replace(array("(",")"," "), '', $_FILES['banner']['name']);
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['banner']['name'];
						$move = move_uploaded_file($_FILES['banner']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", banner = '$fname' ";

		}
		if(empty($id)){

			$save = $this->db->query("INSERT INTO events set ".$data);
		}else{
			$save = $this->db->query("UPDATE events set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_event(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = ".$id);
		if($delete){
			return 1;
		}
	}
	
	function participate(){
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if($commit)
			return 1;

	}
}
