<?
error_reporting(0);
@ini_set('display_errors', 0);
$timestamp = time();

//mysql connect
	$server = "localhost";
	$user = "root";
	$password = "";
	
	$db = "universeDevelopment";
	
	mysql_connect("$server","$user","$password");
	mysql_select_db("$db");
	
	if(!mysql_connect("$server","$user","$password") OR !mysql_select_db("$db")) {
	echo "Something went wrong with the Database... WTF?!";
	die();
	}


//get userdata
if(isset($_SESSION['userid'])){
	$userid = $_SESSION['userid'];
	$global_userData = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE userid='$userid'"));
	$global_userGroupData = mysql_fetch_array(mysql_query("SELECT * FROM userGroups WHERE id='$global_userData[usergroup]'"));
	
	
}
?>