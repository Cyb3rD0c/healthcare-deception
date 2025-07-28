<?php 
$host = 'mcg_db';
$user = 'user';
$pw = 'password';
$dbname = 'mcg_db';

$conn = new mysqli($host, $user, $pw, $dbname);
if(!$conn){
    die("Database connection failed. Error: ".$conn->error);
}
