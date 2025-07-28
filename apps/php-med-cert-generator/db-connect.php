<?php 

$host = 'mcg_db';
$user = 'user';
$pw = 'password';
$dbname = 'mcg_db';

$conn = new mysqli($host, $user, $pw, $dbname);

if($conn->connect_error){
    die("Database connection failed. Error: ".$conn->connect_error);
}
?>
