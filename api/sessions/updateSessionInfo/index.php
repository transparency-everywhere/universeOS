<?
include("../../../inc/config.php");
include("../../../inc/functions.php");

include("../../../inc/classes/class_sessions.php");


$sessions = new sessions();
$sessions->updateSessionInformation($_POST['fingerprint'], $_POST['key'], $_POST['value']);