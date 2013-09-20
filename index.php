<?php
error_reporting(0);
@ini_set('display_errors', 0);
session_start();


// //if user uses internetexplorer => include error template
// $browser = get_browser(null, true);
// echo$browser['browser'];
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
if (count(preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches))>1){

    include("inc/crossbrowsing/ie/ie.php");
    
    die();
	
}
// unset($browser);

include_once("inc/functions.php");
include_once("inc/config.php");
init();
$timestamp = time();
$usersql = mysql_query("SELECT * FROM user WHERE userid='".getUser()."'");
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
<?
if($_SESSION[userid] == "1"){
 include("modules/desktop/dashboard.php");
}
?>

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
			
    include("openFileFromLink.php");
    ?>
<script>
	
      $("#reader:hidden").fadeIn(3000);
</script>
    </body>
    

