<?php
session_start();
include("inc/config.php");
include("inc/functions.php");
$time = time();


//echo $time;



                    $class = new files();
                    var_dump($class->getMyFiles(1));












//
//else if($_GET['action'] == "tester"){
//    echo 'test';
//    error_reporting(E_ALL);
//    include('inc/classes/handlers/youtube/class.php');
//    $yt =  new youtube_handler();
//    echo var_dump($yt->query('test', 0, 100));
//        
//}
//

?>