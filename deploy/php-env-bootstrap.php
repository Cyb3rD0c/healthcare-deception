<?php
// php-env-bootstrap.php

// only define if they haven't already hard-coded something
if (!defined('DB_SERVER'))   define('DB_SERVER',   getenv('DB_HOST')     ?: 'localhost');
if (!defined('DB_USERNAME')) define('DB_USERNAME', getenv('DB_USER')     ?: 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASS')     ?: '');
if (!defined('DB_NAME'))     define('DB_NAME',     getenv('DB_NAME')     ?: '');

?>
