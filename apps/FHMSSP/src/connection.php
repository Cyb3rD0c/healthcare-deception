<?php
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'hms';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';


$database = new mysqli($host, $user, $pass, $db);
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}
?>
