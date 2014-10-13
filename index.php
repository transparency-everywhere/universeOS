<?php
error_reporting(0);
@ini_set('display_errors', 0);
session_start();

include_once("inc/functions.php");
include_once("inc/config.php");
universe::init();
$timestamp = time();
$usersql = mysql_query("SELECT * FROM user WHERE userid='".getUser()."'");
$userdata = mysql_fetch_array($usersql);


include("inc/header.php");
?>
    <body onclick="clearMenu()" onload="clock()<?=$startLink;?>">
<?
if(!isset($_SESSION["userid"])) {
    $_SESSION['loggedOut'] = true;
    include("guest.php");
    
    die();
       
} ?>
	<script>$('#reader').show();</script>
        <div id="reload"></div>
        <div id="alerter" class="container"></div>
            <?
             include("modules/desktop/dashboard.php");
            ?>
        <div id="bodywrap">
        	
        	<ul id="systemAlerts">
        		
        	</ul>
            <div id="loader"></div><div id="popper"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
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
    gui::showDock();
			
    include("openFileFromLink.php");
?>
</body>
    

