<?php

// DATABASE CONNECTION //

@session_start();

$servername = getenv('DB_HOST') ?: 'remoteclinic_db';
$username = getenv('DB_USER') ?: 'remoteclinic_user';
$password = getenv('DB_PASSWORD') ?: 'remoteclinic_password';
$dbname = getenv('DB_NAME') ?: 'remoteclinic_db';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
