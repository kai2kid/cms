<?php
$_online = 0;
session_start();
if ($_online) {
  // db settings
  define('_DB_HOST', '192.168.9.10');
  define('_DB_USER', 'absensi');
  define('_DB_PASS', 'absensiSTTS2012');
  define('_DB_NAME', 'db_absensi');
  
//  define('_PATH_ROOT','w:/');
} else {
  // db settings
  define('_DB_HOST', 'localhost');
  define('_DB_USER', 'root');
  define('_DB_PASS', '');
  define('_DB_NAME', 'beautifyme');
  
//  define('_PATH_ROOT','D:/xampp/htdocs/');  
}

//define('DEBUG', true); // show debugging messages?
date_default_timezone_set("asia/jakarta"); // set timezone

// Connect to server and select database.
$link = mysqli_connect(_DB_HOST, _DB_USER, _DB_PASS) or die("Connection Error!");
global $link;
mysqli_select_db($link, _DB_NAME)or die("Cannot select DB");

?>