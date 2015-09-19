<?php

//disable error reporting
error_reporting(0);
//@ini_set('display_errors', 0);


//include dbConfig
include("config/dbConfig.php");

//serverstuf
$universeURL = 'http://localhost/universe'; //url of current installation


$timestamp = time();


//start session
if(!isset($_SESSION)){ 
    session_start(); 
}

////mysql connect	or die
//mysql_connect("$server","$user","$password");
//mysql_select_db("$db");
//
//if(!mysql_connect("$server","$user","$password") OR !mysql_select_db("$db")) {
//    die("Something went wrong with the Database... WTF?!");
//}

$mysqli = new mysqli("$server", "$user", "$password", "$db");
if ($mysqli->connect_errno) {
    echo "Something went wrong with the Database... WTF?! - Error Notification: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


define('analytic_script',  "<!-- Piwik only on page for unregistered users -->"
    . "<script type=\"text/javascript\"> \n"
    . "  var _paq = _paq || [];\n"
    . "  _paq.push(['trackPageView']);\n"
    . "  _paq.push(['enableLinkTracking']);\n"
    . "  (function() {\n"
    . "    var u=((\"https:\" == document.location.protocol) ? \"https\" : \"http\") + \"://analytics.transparency-everywhere.com//\";\n"
    . "    _paq.push(['setTrackerUrl', u+'piwik.php']);\n"
    . "    _paq.push(['setSiteId', 1]);\n"
    . "    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';\n"
    . "    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);\n"
    . "  })();\n"
    . "</script>\n"
    . "<noscript><p><img src=\"http://analytics.transparency-everywhere.com/piwik.php?idsite=1\" style=\"border:0\" alt=\"\" /></p></noscript>\n"
    . "<!-- End Piwik Code -->");

?>