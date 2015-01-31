<?php
include_once("inc/functions.php");
include_once("inc/config.php");

$universe = new universe();
$universe->init();
function generate(){
    
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
    
    echo '<script type="text/javascript" src="inc/js/guest.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="inc/css/guest.css" media="all" />';
    echo '<div id="bodywrap">';
        include('views/guestpage/guest_area.html');

        echo '<div id="alerter" class="container"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>';
        echo '<div id="suggest">';
        echo '</div>';
    echo '</div>';
        
    include('views/guestpage/login_menu.html');
    include('views/guestpage/dock_menu.html');
    include('views/guestpage/dock.html');
    
    echo analytic_script;
    
    include('actions/openFileFromLink.php');

    echo '</body>';
    echo '</html>';
       
}else{
    echo '<script>User.userid = '.getUser().';</script>';
    echo '<div id="alerter" class="container"></div>';
    
    include("modules/desktop/dashboard.php");
    echo    '<div id="bodywrap">';
    echo        '<ul id="systemAlerts">';
    echo        '</ul>';
    echo        '<div id="loader"></div>';
    echo        '<div id="popper"></div>';
    echo        '<iframe name="submitter" style="display:none;" id="submitter"></iframe>';
    echo        '<div id="suggest"></div>';
    
    echo        '<div id="notifications">';
        echo        '<ul></ul>';
    echo        '</div>';
    
    echo        '<div id="playListPlayer">';
    
   include("playListplayer.php");
    
    echo        '</div>';
    
    echo    '</div>';
    
    $gui = new gui();
    $gui->showDock();

    include('actions/openFileFromLink.php');
    
    echo '</body>';
}
}

generate();
?>


    

