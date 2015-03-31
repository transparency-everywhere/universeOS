<?php
      session_start();
  include("../../inc/config.php");
  include("../../inc/functions.php");
    if($_POST['submit']){
        mysql_query("UPDATE user SET priv_showProfile='$_POST[priv_showProfile]', priv_profileInformation='$_POST[priv_profileInformation]', priv_profilePicture='$_POST[priv_profilePicture]', priv_profileFav='$_POST[priv_profileFav]', priv_buddyRequest='$_POST[priv_buddyRequest]', priv_foreignerMessages='$_POST[priv_foreignerMessages]', priv_foreignerFeeds='$_POST[priv_foreignerFeeds]' WHERE userid='$_SESSION[userid]'");
        jsAlert("saved :)");
    }
    ?>
        
        <center>
            <h1>Secority settings</h1>
        </center>
        <form action="modules/settings/privacy.php" target="submitter" method="post">
        	<h2>Password</h2>
        	<a href="#" class="btn btn-primary">Change Password</a>
        </form>