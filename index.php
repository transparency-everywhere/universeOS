<?php
include_once("inc/functions.php");
include_once("inc/config.php");

$universe = new universe();
$universe->init();
$timestamp = time();
if(getUser()){
    $userClass = new user(getUser());
    $userData = $userClass->getData();
    $login = true;
}else{
    
    $login = false;
    $userData['startLink'] = ''; //otherwise notice 'undefined index'
}


include("inc/header.php");
echo '<body onclick="clearMenu()" onload="clock()'.$userData['startLink'].'">';

if(!$login) {
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
<?php
$gui = new gui();
$gui->showDock();
			
include('actions/openFileFromLink.php');
?>
</body>
    

