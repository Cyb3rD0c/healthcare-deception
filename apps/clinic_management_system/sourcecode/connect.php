<?php
/* Docker Database */

$servername = "cms_clinic_db";
$username = "root";
$password = "rootpassword";
$dbname = "clinic_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
