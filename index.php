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

include("views/header/header.html");
echo '<body onclick="clearMenu()" onload="clock()'.$userData['startLink'].'">';

        //define bg-image
        if(!empty($userdata['backgroundImg'])){ 
            ?>
            <style type="text/css">
                body{
                    background-image: url(<?=$userdata['backgroundImg'];?>);
                    background-attachment: no-repeat;
                }
            </style>
            <? }
if(!$login) {
    $_SESSION['loggedOut'] = true;
    
    echo '<link rel="stylesheet" type="text/css" href="inc/css/guest.css" media="all" />';
    echo '<div id="bodywrap">';
        include('views/guestpage/guest_area.html');

        echo '<div id="alerter"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>';
        echo '<div id="suggest">';
        echo '</div>';
    echo '</div>';
        
    include('views/guestpage/login_menu.html');
    include('views/guestpage/dock_menu.html');
    include('views/guestpage/dock.html');
    
    echo analytic_script;
    
    include('actions/openFileFromLink.php');
    include('views/guestpage/search_menu.html');

    include("views/header/scripts.html");
    echo '<script type="text/javascript" src="inc/js/guest.js"></script>';
    echo '</body>';
       
}
else{
    //set userid
    include("views/header/scripts.html");
    echo '<script>User.userid = '.getUser().';</script>';
    
    echo '<div id="alerter"></div>';
    
    //include dashboard
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
    
//    echo        '<div id="playListPlayer">';
//    
//    echo        '</div>';
    
    $gui = new gui();
    $gui->showDock();

    echo    '</div>';
    
    include('views/guestpage/search_menu.html');
    
    include('actions/openFileFromLink.php');
    
    echo '</body>';
}
}

generate();
?>


    

