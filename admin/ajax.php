<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login_faculty'){
	$login_faculty = $crud->login_faculty();
	if($login_faculty)
		echo $login_faculty;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_course"){
	$save = $crud->save_course();
	if($save)
		echo $save;
}

if($action == "delete_course"){
	$delete = $crud->delete_course();
	if($delete)
		echo $delete;
}
if($action == "delete_load"){
	$delete = $crud->delete_load();
	if($delete)
		echo $delete;
}
if($action == "delete_MW"){
	$delete = $crud->delete_MW();
	if($delete)
		echo $delete;
}
if($action == "delete_TTh"){
	$delete = $crud->delete_TTh();
	if($delete)
		echo $delete;
}
if($action == "delete_Fri"){
	$delete = $crud->delete_Fri();
	if($delete)
		echo $delete;
}
if($action == "delete_Sat"){
	$delete = $crud->delete_Sat();
	if($delete)
		echo $delete;
}
if($action == "save_subject"){
	$save = $crud->save_subject();
	if($save)
		echo $save;
}
if($action == "save_fees"){
	$save = $crud->save_fees();
	if($save)
		echo $save;
}

if($action == "delete_subject"){
	$delete = $crud->delete_subject();
	if($delete)
		echo $delete;
}
if($action == "delete_fees"){
	$delete = $crud->delete_fees();
	if($delete)
		echo $delete;
}
if($action == "delete_schedule"){
	$delete = $crud->delete_schedule();
	if($delete)
		echo $delete;
}
if($action == "save_room"){
	$save = $crud->save_room();
	if($save)
		echo $save;
}if($action == "save_timeslot"){
	$save = $crud->save_timeslot();
	if($save)
		echo $save;
}
if($action == "delete_room"){
	$delete = $crud->delete_room();
	if($delete)
		echo $delete;
}
if($action == "delete_timeslot"){
	$delete = $crud->delete_timeslot();
	if($delete)
		echo $delete;
}
if($action == "save_section"){
	$save = $crud->save_section();
	if($save)
		echo $save;
}
if($action == "delete_section"){
	$delete = $crud->delete_section();
	if($delete)
		echo $delete;
}
if($action == "save_faculty"){
	$save = $crud->save_faculty();
	if($save)
		echo $save;
}
if($action == "delete_faculty"){
	$save = $crud->delete_faculty();
	if($save)
		echo $save;
}

if($action == "save_schedule"){
	$save = $crud->save_schedule();
	if($save)
		echo $save;
}
if($action == "save_roomschedule"){
	$save = $crud->save_roomschedule();
	if($save)
		echo $save;
}
if($action == "save_roomscheduletth"){
	$save = $crud->save_roomscheduletth();
	if($save)
		echo $save;
}
if($action == "delete_schedule"){
	$save = $crud->delete_schedule();
	if($save)
		echo $save;
}
if($action == "get_schedule"){
	$get = $crud->get_schecdule();
	if($get)
		echo $get;
}
if($action == "get_year"){
	$get = $crud->get_year();
	if($get)
		echo $get;
}
if($action == "delete_forum"){
	$save = $crud->delete_forum();
	if($save)
		echo $save;
}

if($action == "save_comment"){
	$save = $crud->save_comment();
	if($save)
		echo $save;
}
if($action == "delete_comment"){
	$save = $crud->delete_comment();
	if($save)
		echo $save;

}

if($action == "save_event"){
	$save = $crud->save_event();
	if($save)
		echo $save;
}
if($action == "delete_event"){
	$save = $crud->delete_event();
	if($save)
		echo $save;
}	
if($action == "participate"){
	$save = $crud->participate();
	if($save)
		echo $save;
}
if($action == "get_venue_report"){
	$get = $crud->get_venue_report();
	if($get)
		echo $get;
}
if($action == "save_art_fs"){
	$save = $crud->save_art_fs();
	if($save)
		echo $save;
}
if($action == "delete_art_fs"){
	$save = $crud->delete_art_fs();
	if($save)
		echo $save;
}
if($action == "get_pdetails"){
	$get = $crud->get_pdetails();
	if($get)
		echo $get;
}
ob_end_flush();
?>
