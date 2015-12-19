<?
include("../../../inc/config.php");
include("../../../inc/functions.php");



$sessions = new sessions();
echo $sessions->getSessionInformation($_POST['fingerprint']);