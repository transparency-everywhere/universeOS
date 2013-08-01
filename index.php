<?php
session_start();


// //if user uses internetexplorer => include error template
// $browser = get_browser(null, true);
// echo$browser['browser'];
// if($browser[browser] == "Internet Explorer"){
// 
    // include("inc/crossbrowsing/ie/ie.php");
//     
    // die();
// 	
// }
// unset($browser);

include_once("inc/functions.php");
include_once("inc/config.php");
init();
$timestamp = time();
$usersql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
$userdata = mysql_fetch_array($usersql);


include("inc/header.php");
?>

    <body onclick="clearMenu()" onload="clock()<?=$startLink;?>">
<?
if(!isset($_SESSION["userid"])) {

    include("guest.php");
    
    die();
       
} ?>
        <div id="reload"></div>
        <div id="bodywrap">
        	
        	
            <div id="alerter" class="container"></div><div id="loader"></div><div id="popper"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
            <div id="suggest">
            </div>


            <div id="invisiblefeed">
                <? include("modules/feed/index.php") ?>
            </div>
            
            <? include("modules/reader/index.php") ?>
            
            <div id="invisiblebuddylist">
                <? include("buddylist.php"); ?>
            </div>
            
            <div id="invisiblefilesystem">
                <? include("modules/filesystem/filesystem.php") ?>
            </div>
            
            <? include("modules/chat/chat.php") ?>
            
            
            <div id="personalFeed" style="display: none; z-index: 99999;">
                <?
                include("personalFeed.php");
                ?>
            </div>
            

            <div id="playListPlayer">
                <?
                include("playListplayer.php");
                ?>
            </div>
        </div>
            <?
            include("dock.php");
            ?>
    <? 
    $zeit=gettimeofday();
    $endzeit=$zeit["usec"];
    $gesamtzeit=round(($endzeit-$startzeit)/1000,0);
    ?>
    
    
    <?
    include("openFileFromLink.php");
    ?>
    </body>
    

