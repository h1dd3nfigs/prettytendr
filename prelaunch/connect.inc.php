<?php

$conn_error = 'Could not connect.';
$mysql_host= 'localhost';
$mysql_user= 'pytadmin';
$mysql_pass = 'effEWR2k14';
$mysql_db = 'vid_splash_pg';

$link  = mysqli_connect($mysql_host, $mysql_user, $mysql_pass);
$mysqli_select_db = mysqli_select_db($link, $mysql_db) ;

if(!$link ||!$mysqli_select_db){
//	die($conn_error);

} 
?>