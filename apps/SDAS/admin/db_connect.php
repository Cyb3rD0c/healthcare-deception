<?php
$servername = "doctors_appointment_db";
$username = "user";
$password = "user_password";
$dbname = "doctors_appointment_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
