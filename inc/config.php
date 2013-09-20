<?php

//disable error reporting
error_reporting(0);
@ini_set('display_errors', 0);

//include dbConfig
include("config/dbConfig.php");

$timestamp = time();

//mysql connect	or die
	mysql_connect("$server","$user","$password");
	mysql_select_db("$db");
	
	if(!mysql_connect("$server","$user","$password") OR !mysql_select_db("$db")) {
	die("Something went wrong with the Database... WTF?!");
	}


//get userdata
if(isset($_SESSION['userid'])){
	$userid = $_SESSION['userid'];
	$global_userData = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE userid='$userid'"));
	$global_userGroupData = mysql_fetch_array(mysql_query("SELECT * FROM userGroups WHERE id='$global_userData[usergroup]'"));
}
?>