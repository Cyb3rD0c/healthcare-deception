<?php
$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
$base_url = getenv('BASE_URL') ?: 'http://localhost/hprms/';
if(!defined('base_url')) define('base_url', $base_url);
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
if(!defined('dev_data')) define('dev_data',$dev_data);
if(!defined('DB_SERVER')) define('DB_SERVER',"hprms-db");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"hprmsuser");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"yourpassword");
if(!defined('DB_NAME')) define('DB_NAME',"hprms_db");
?>
