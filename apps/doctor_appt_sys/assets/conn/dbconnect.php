<?php
$servername = "doc_appt_system_db";
$username = "root";
$password = "root";
$dbname = "sourcecodester_dadb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
