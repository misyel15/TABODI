<?php
// Database connection parameters
$host = '127.0.0.1:3306';
$username = 'u510162695_scheduling_db';
$password = '1Scheduling_db'; // Ensure to set a proper password here
$dbname = 'u510162695_scheduling_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname,);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
