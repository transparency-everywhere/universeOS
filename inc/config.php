<?php

//disable error reporting
error_reporting(E_ALL);
//@ini_set('display_errors', 0);


//include dbConfig
include("config/dbConfig.php");

//serverstuf
$universeURL = 'http://localhost/universe'; //url of current installation


$timestamp = time();


//start session
session_start();

//mysql connect	or die
	mysql_connect("$server","$user","$password");
	mysql_select_db("$db");
	
	if(!mysql_connect("$server","$user","$password") OR !mysql_select_db("$db")) {
            die("Something went wrong with the Database... WTF?!");
	}


?>