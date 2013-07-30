<?
$timestamp = time();

//mysql connect
	$server = "85.214.203.132";
	$user = "universe";
	$password = "wer345tzu567fgh098";
	
	$db = "universe";
	
	mysql_connect("$server","$user","$password");
	mysql_select_db("$db");
	
	if(!mysql_connect("$server","$user","$password") OR !mysql_select_db("$db")) {
	echo "Something went wrong with the Database... WTF?!";
	die();
	}


//get gloabals for user
if(!empty($_SESSION[userid])){
	
	$global_userData = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'"));
	
	$global_userGroupData = mysql_fetch_array(mysql_query("SELECT * FROM userGroups WHERE id='$global_userData[usergroup]'"));
	
	
}
?>