<?php

//disable error reporting
error_reporting(0);
//@ini_set('display_errors', 0);


//include dbConfig
$config_class_file_path = dirname(__FIlE__).'/classes/uni_config.php';
if(file_exists($config_class_file_path)){
    include($config_class_file_path);
}else{
    die('there is no config file. Please run the installer or check if there is anything wrong with your server');
}

//serverstuf
$universeURL = 'http://localhost/universe'; //url of current installation


$timestamp = time();


//start session
if(!isset($_SESSION)){ 
    session_start(); 
}

define('uni_config_database_host', $server);
define('uni_config_database_user', $user);
define('uni_config_database_password', $password);
define('uni_config_database_name', $db);

//$mysqli = new mysqli("$server", "$user", "$password", "$db");
//if ($mysqli->connect_errno) {
//    echo "Something went wrong with the Database... WTF?! - Error Notification: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; //NOTIFICATION SHOULD BE REMOVED
//}


define('analytic_script',  "<!-- Piwik --> <script type=\"text/javascript\"> var _paq = _paq || []; _paq.push(['trackPageView']); _paq.push(['enableLinkTracking']); (function() { var u=\"//analytics.transparency-everywhere.com/piwik/\"; _paq.push(['setTrackerUrl', u+'piwik.php']); _paq.push(['setSiteId', 2]); var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s); })(); </script> <noscript><p><img src=\"//analytics.transparency-everywhere.com/piwik/piwik.php?idsite=2\" style=\"border:0;\" alt=\"\" /></p></noscript> <!-- End Piwik Code -->");
?>