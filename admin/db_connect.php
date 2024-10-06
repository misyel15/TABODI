<?php
// Define the database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'scheduling_db';

// Create a new MySQLi object
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}