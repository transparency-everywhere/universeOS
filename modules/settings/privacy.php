<?php
      session_start();
  include("../../inc/config.php");
  include("../../inc/functions.php");
    if($_POST[submit]){
        mysql_query("UPDATE user SET priv_showProfile='$_POST[priv_showProfile]', priv_profileInformation='$_POST[priv_profileInformation]', priv_profilePicture='$_POST[priv_profilePicture]', priv_profileFav='$_POST[priv_profileFav]', priv_buddyRequest='$_POST[priv_buddyRequest]', priv_foreignerMessages='$_POST[priv_foreignerMessages]', priv_foreignerFeeds='$_POST[priv_foreignerFeeds]' WHERE userid='$_SESSION[userid]'");
        jsAlert("saved :)");
    }
    $privEditSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
    $privEditData = mysql_fetch_array($privEditSql);
	
    if($privEditData[priv_activateProfile] == "1"){
        $checked[priv_activateProfile] = 'checked="checked"';
    }
    if($privEditData[priv_showProfile] == "1"){
        $checked[priv_showProfile] = 'checked="checked"';
    }
    if($privEditData[priv_profileInformation] == "1"){
        $checked[priv_profileInformation] = 'checked="checked"';
    }
    if($privEditData[priv_profilePicture] == "1"){
        $checked[priv_profilePicture] = 'checked="checked"';
    }
    if($privEditData[priv_profileFav] == "1"){
        $checked[priv_profileFav] = 'checked="checked"';
    }
    if($privEditData[priv_profileLog] == "1"){
        $checked[priv_profileLog] = 'checked="checked"';
    }
    if($privEditData[priv_activateFeed] == "1"){
        $checked[priv_activateFeed] = 'checked="checked"';
    }
    if($privEditData[priv_buddyRequest] == "1"){
        $checked[priv_buddyRequest] = 'checked="checked"';
    }
    if($privEditData[priv_foreignerFeeds] == "1"){
        $checked[priv_foreignerFeeds] = 'checked="checked"';
    }
    if($privEditData[priv_foreignerMessages] == "1"){
        $checked[priv_foreignerMessages] = 'checked="checked"';
    }
    ?>
        
        <center>
            <h1>Privacy settings</h1>
        </center>
        <form action="modules/settings/privacy.php" target="submitter" method="post">
        <table style="margin: 15px;">
            <tr>
                <td colspan="2"><h2>Profile</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_showProfile" <?=$checked[priv_showProfile];?> value="1"></td>
                <td>Show profile to foreigners</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_profileInformation" <?=$checked[priv_profileInformation];?> value="1"></td>
                <td>Show information to foreigners</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_profileFav" <?=$checked[priv_profileFav];?> value="1"></td>
                <td>Show favourite files to foreigners</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><h2>Buddylist</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_buddyRequest" <?=$checked[priv_buddyRequest];?> value="1"></td>
                <td>Foreigners need allowance to add you on their buddylist</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><h2>Messages</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_foreignerMessages" <?=$checked[priv_foreignerMessages];?> value="1"></td>
                <td>Allow foreigners to write you messages</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><h2>Feed</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_foreignerFeeds" <?=$checked[priv_foreignerFeeds];?> value="1"></td>
                <td>Show Feed to foreigners</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr><!-- 
            <tr>
                <td colspan="2"><h2>General</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_profilePicture" value="1"></td>
                <td>Show profilepicture to foreigners</td>
            </tr> -->
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="right"><a  class="btn" onclick="$('#invisibleSettings').remove();">cancel</a>&nbsp;&nbsp;<input type="submit" class="btn btn-info" value="save" name="submit"></td>
            </tr>
        </table>
        </form>