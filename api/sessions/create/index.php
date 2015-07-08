<?
include("../../../inc/config.php");
include("../../../inc/functions.php");

include("../../../inc/classes/class_sessions.php");


$sessions = new sessions();
    
echo $sessions->createSession($_POST['fingerprint'], $_POST['type'], $_POST['title']);