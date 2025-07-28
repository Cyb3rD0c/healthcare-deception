<?php
$host = 'hcpms_db'; // The service name defined in docker-compose.yml
$db = 'hms';
$user = 'root';
$pass = 'rootpassword'; // Ensure this matches the root password set in your docker-compose.yml

$database = new mysqli($host, $user, $pass, $db);

if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}
?>
