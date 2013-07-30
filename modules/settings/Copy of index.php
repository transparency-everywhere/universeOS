<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
    if(empty($_GET[action])) {
        if(empty($_GET[reload])){ 
            $right = "right: -150px;";?>
<script type="text/javascript">
   initDraggable();
    
   function sendform(f,c)
   {
   f.action.match(/(\bkeepThis=(true|false)&TB_iframe=true.+$)/);
   tb_show(c, 'about:blank?'+RegExp.$1);
   f.target=$('#TB_iframeContent').attr('name')
   return true;
   }
</script>
<div id="invisibleSettings">
<div class="fenster" id="settings">
    <header class="titel">
        <p>Settings</p><p class="windowMenu"><a href="#" onclick="$('#invisibleSettings').remove();"><img src="./gfx/icons/close.png" width="16"></a>
    </header>
    <div class="inhalt autoflow">
        
        
        
        
        

    
<div>
    <div class="leftNav" style="top: 0;">
    <ul>
        <li><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?reload=1');">General</a></li>
        <li><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?action=privacy');">Privacy</a></li>
        <li><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?action=friends');">Buddylist</a></li>
        <li><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?action=groups');">Groups</a></li>
    </ul>
    </div>
    <? } ?>
    <div id="settingsFrame">
      
                <?
                    if(isset($_POST[AccSetSubmit])) {
                        $birthdate = gmmktime("0", "0", "0", "$_POST[birth_month]", "$_POST[birth_day]", "$_POST[birth_year]");
                        mysql_query("UPDATE user SET realname='$_POST[AccSetRealname]', email='$_POST[AccSetMail]', place='$_POST[place]', home='$_POST[home]', birthdate='$birthdate', school1='$_POST[school1]', university1='$_POST[university1]', employer='$_POST[work]' WHERE userid='$_SESSION[userid]'");
                        jsAlert("Your changes have been saved");
                        }
                    $AccSetSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
                    $AccSetData = mysql_fetch_array($AccSetSql);
                    if($AccSetData[birthdate]){
                    $birth_day = date("d", $AccSetData[birthdate]);
                    $birth_month = date("m", $AccSetData[birthdate]);
                    $birth_year = date("Y", $AccSetData[birthdate]);
                    }
                 ?>
                <? if(empty($_GET[subaction])){ ?>
        
        <div class="frameRight" style="top:0px;">
            <form action="modules/settings/index.php" method="post" target="submitter">
                <div class="controls" style="max-width: 450px;">
                    <div class="controls controlls-row">
                        <h3 class="span5">Edit your profile</h3>
                    </div>
                    
                    <div class="controls controlls-row">
                        <span class="span2">Picture</span>
                        <div class="span3">
                            <?=showUserPicture($_SESSION[userid], 100);?><br style="clear: both;">
                            <a href="javascript: loader('settingsFrame', 'modules/settings/index.php?subaction=image&reload=1');" class="btn" style="margin-top: 10px; margin-bottom: 15px;">edit</a>
                        </div>
                    </div>
                    
                    <div class="controls controlls-row">
                        <span class="span2">Name</span>
                        <input type="text" name="AccSetRealname" class="span3" value="<?=$AccSetData[realname];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">City</span>
                        <input type="text" name="place" class="span3" value="<?=$AccSetData[place];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Hometown</span>
                        <input type="text" name="home" class="span3" value="<?=$AccSetData[home];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Birthdate</span>
                        <input type="text" name="birth_day" class="span1" value="<?=$birth_day;?>">
                        <input type="text" name="birth_month" class="span1" value="<?=$birth_month;?>">
                        <input type="text" name="birth_year" class="span1" value="<?=$birth_year;?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">School</span>
                        <input type="text" name="school1" class="span3" value="<?=$AccSetData[school1];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">University</span>
                        <input type="text" name="university1" class="span3" value="<?=$AccSetData[university1];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Work</span>
                        <input type="text" name="work" class="span3" value="<?=$AccSetData[employer];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span5">
                            <input type="submit" name="AccSetSubmit" value="Save" class="btn btn-info pull-right" style="margin: 0 30px;">
                            <a  class="btn pull-right" onclick="$('#invisibleSettings').remove();">cancel</a>
                        </span>
                    </div>
                </div>
            </form>
         </div>
                <?

                }
                if($_GET[subaction] == image) {
                    if(isset($_POST[submit])) {
                        $time = time();
                        $imgSql = mysql_query("SELECT userid, profilepictureelement FROM user WHERE userid='$_SESSION[userid]'");
                        $imgData = mysql_fetch_array($imgSql);

                        $target_path = "../../upload/userFiles/$_SESSION[userid]/userPictures/";
                        $path = "$target_path";
                        $thumbPath25 = "$target_path/thumb/25";
                        $thumbPath40 = "$target_path/thumb/40";
                        $thumbPath300 = "$target_path/thumb/300";

                        $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);


                        $imgName = basename($_FILES['uploadedfile']['name']);

                        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                        	
							$type = getMime($imgName);
                        mkthumb("$imgName",25,25,$path,$thumbPath25); 
                        mkthumb("$imgName",40,40,$path,$thumbPath40); 
                        mkthumb("$imgName",300,300,$path,$thumbPath300);

                        mysql_query("INSERT INTO `files` (`folder`, `title`, `type`. `filename`, `owner`, `timestamp`, `privacy`) VALUES ( '$imgData[profilepictureelement]', '$imgName', '$type',  '$imgName', '$_SESSION[userid]', '$time', 'p');");
                        mysql_query("UPDATE user SET userPicture='$imgName' WHERE userid='$_SESSION[userid]'");
                        jsAlert("The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded");

                        } else{
                                echo "There was an error uploading the file, please try again!";
                            }
                        $time = time();





                    }
                    ?>
                <div class="frameRight" id="settingsFrame">
                    <header>
                            <p style="margin-left: 40px;"><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?reload=1');">&lt;&lt;back</a></p>  
                            <p style="font-size: 13pt; margin: 40px;">upload new profilepicture</p>
                             </header>
                    <div style="margin: 40px;">
                        <form action="modules/settings/index.php?subaction=image" method="post" enctype="multipart/form-data" target="submitter">
                            <input type="file" name="uploadedfile"><br><input type="submit" name="submit" value="upload">
                        </form>            
                    </div>
                    <footer style="margin: 40px;">
                        <p>Please keep always in mind that everyone can see this infos till you switched it off in your <mark>privacy settings</mark>.<br>It also don't overwrites your old one and is shown beside all of your posts.</p>
                    </footer>
                </div>       
        
        
        
        
    </div>
</div>

<?
    } }
 if($_GET[action] == "friends") {
    if(isset($_POST[submit])){
        $newAlias = htmlspecialchars($_POST[alias]);
        mysql_query("UPDATE buddylist SET alias='$newAlias' WHERE owner='$_SESSION[userid]' && buddy='$_POST[buddy]'");
        jsAlert("worked:)");
    }
?>
     <div class="frameRight" id="settingsFrame">
         <div id="content">
             <ul>
                 <li>
                     <span><?=showUserPicture($buddyEditData[buddy], 30);?></span>
                     <span>
                         <form action="modules/settings/index.php?action=friends&reload=1" target="submitter" method="post"><?=$userpicture;?>&nbsp;
                            <input type="hidden" name="buddy" value="<?=$buddyEditData[buddy];?>">
                            <input type="text" name="alias" value="<?=$alias;?>">&nbsp;
                            <input type="submit" name="submit" value="save">
                        </form>
                     </span>
                 </li>
             </ul>
             
             
         <center>Your Buddies:
         <table>
             <tr>
                 <td>&nbsp;</td>
             </tr>
         <?
         
            if(isset($_GET[delete])){
                mysql_query("DELETE FROM buddylist WHERE owner='$_SESSION[userid]' && buddy='".mysql_real_escape_string($_GET[buddy])."' LIMIT 1");
                mysql_query("DELETE FROM buddylist WHERE owner='".mysql_real_escape_string($_GET[buddy])."' && buddy='$_SESSION[userid]' LIMIT 1");
                jsAlert("worked :(");
            }
         $buddyEditSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]'");
         while($buddyEditData = mysql_fetch_array($buddyEditSql)){
            $path = "../../";
             if(empty($buddyEditData[alias])){  
            $blUserSql = mysql_query("SELECT userid, username FROM user WHERE userid='$buddyEditData[buddy]'");
            $blUserData = mysql_fetch_array($blUserSql);
            $alias = "$blUserData[username]"; 
            } else{
            $alias = "$buddyEditData[alias]";
            }
         ?>
             <tr>
                 <td>&nbsp;</td>
                <td><a href="#" onclick="confirmation(<?=$buddyEditData[buddy];?>);"><img src="./gfx/delete_2.png" width="16"></a></td>
                <td><?=showUserPicture($buddyEditData[buddy], 30);?></td>
                <td><form action="modules/settings/index.php?action=friends&reload=1" target="submitter" method="post"><?=$userpicture;?>&nbsp;
                    <input type="hidden" name="buddy" value="<?=$buddyEditData[buddy];?>">
                    <input type="text" name="alias" value="<?=$alias;?>">&nbsp;
                    <input type="submit" name="submit" value="save">
                    </form></td>
             </tr>
         <? } ?>  
         </table>
         </center>
         </ul>
         </div>
     </div><script type="text/javascript">
function confirmation(id) {
	var answer = confirm("Are you sure to delete this buddy?")
	if (answer){
            
		$("#submitter").load("modules/settings/index.php?action=friends&reload=1&delete=1&buddy=" + id +"");
	}
	else{
            return false;
	}
}
</script>
     <?
   
}
if($_GET[action] == "groups") {
    if($_GET[subaction] == "add"){
    if(isset($_POST[submit])){
        $description = $_POST[description];
        $title = $_POST[title];
        $privacy = $_POST[privacy];
        $userlist = $_POST[users];
        if((isset($description)) && (isset($title)) && (isset($privacy))){
        mysql_query("INSERT INTO `groups` (`title`, `description`, `public`, `admin`) VALUES ('$title', '$description', '$public', '$userid');");
        $groupId = mysql_insert_id();
        if(isset($userlist)){
        foreach ($userlist as &$value) {
        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$groupId', 'user', '$value', '$time', '$userid');");
        }}
        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$groupId', 'user', '$userid', '$time', '$userid', '0');");
        jsAlert("worked:)");
    }else{
        jsAlert("please fill out everything");
    }}
    ?>
    
     <div class="frameRight" id="settingsFrame">
         <center>
             Add Group
         </center>
         <form action="modules/settings/index.php?action=groups&subaction=add" method="post" target="submitter">
         <table>
             <tr>
                 <td>&nbsp;</td>
             </tr>
             <tr>
                 <td>Groupname</td>
                 <td><input type="text" name="title"></td>
             </tr>
             <tr>
                 <td><input type="radio" name="privacy" value="0">Public</td>
                 <td><input type="radio" name="privacy" value="1">Private</td>
             </tr>
             <tr>
                 <td>Description</td>
                 <td><textarea name="description"></textarea></td>
             </tr>
             <tr>
                 <td>Invite Buddies:</td>
                 <td>
                     <div style="width: 200px; height: 100px; overflow: scroll;">
                         <table cellspacing="0">
        <?
        $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' && request='0'");
        while($buddylistData = mysql_fetch_array($buddylistSql)) {
            $blUserSql = mysql_query("SELECT userid, username FROM user WHERE userid='$buddylistData[buddy]'");
            $blUserData = mysql_fetch_array($blUserSql);
            if(!empty($buddylistData[alias])){
                $username = $buddylistData[alias];
            } else{
            $username = htmlspecialchars($blUserData[username]);
                }
            if($i%2 == 0) {
                $bgcolor="FFFFFF";

            } else {    
                $bgcolor="e5f2ff";
            }
            $i++;
            ?>
                             <tr bgcolor="#<?=$bgcolor;?>">
                                 <td><input type="checkbox" name="users[]" value="<?=$blUserData[userid];?>"></td>
                                 <td><?=$blUserData[username];?></td>
                             </tr>
            <?}?> 
                         </table>
                     </div>
                 </td>
             </tr>
             <tr>
                 <td>
                     <input type="submit" value="add" name="submit">
                 </td>
             </tr>
         </table>
         </form>
     </div> 
     
     <?
    }else if(empty($_GET[subaction])){
?>
     
     <div class="frameRight" id="settingsFrame">
         <center>Groups</center>
         <p><a href="javascript: loader('settingsFrame', 'modules/settings/index.php?action=groups&subaction=add');" class="button">Add Group</a></p>
         <div>
             <p>Joined Groups</p>
             <ul>
                 <li>Transparency Everywhere</li>
             </ul>
         </div>
     </div>
     <?
   
}}
else if($_GET[action] == privacy) { 
    if(!isset($_GET[reload])){
        $path = '../../';
    }
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
    <div class="frameRight" id="settingsFrame">
        
        <center>
            <h1>Privacy settings</h1>
        </center>
        <form action="modules/settings/index.php?action=privacy&reload=1" target="submitter" method="post">
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
            </tr>
            <tr>
                <td colspan="2"><h2>General</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="priv_profilePicture" <?=$checked[priv_profilePicture];?> value="1"></td>
                <td>Show profilepicture to foreigners</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="right"><a  class="btn" onclick="$('#invisibleSettings').remove();">cancel</a>&nbsp;&nbsp;<input type="submit" class="btn btn-info" value="save" name="submit"></td>
            </tr>
        </table>
        </form>
            
        
    </div>
    <?
    if($_GET[subaction] == "profile"){
        if(isset($_POST[submit])){
           echo"safed"; 
        }else{
           ?>
    <div class="jqMailPopUp border-radius box-shadow" id="peppersteak">   
     <link rel="stylesheet" type="text/css" href="modules/profile/profile.css">  

    <div id="profileWrap">
    <div>&nbsp;</div>
    <div class="border-radius shadow profileElement" id="profileInfo">
        <input type="checkbox" name="showProfile"><img src="./upload/userFiles/<?=$profiledata['userid'];?>/userPictures/thumb/300/<?=$profiledata['userPicture'];?>" width="100" class="border-radius shadow">
        <p><a href="#" onclick="createNewTab('reader_tabView','<?=$profiledata[username];?>','','./addbuddy.php?user=<?=$profiledata['userid'];?>',true);return false">Add to Buddylist</a></p>
        <p><?=$profiledata[username];?><br><?=$profiledata[realname];?></p>
        <p>Born on the <input type="checkbox" name="showProfile">1st of July 1968. From <input type="checkbox" name="showProfile">Brighton, lives in <input type="checkbox" name="showProfile">London. Went to <input type="checkbox" name="showProfile">Greenville Highscool.</p>
    </div>
    <div class="border-radius shadow profileElement" id="profileFav">
        <div><input type="checkbox" name="showProfile"></div>
        <ol>
            <li>Fav1</li>
        </ol> 
        <ol>
            <li>Fav2</li>
        </ol> 
        <ol>
            <li>Fav3</li>
        </ol> 
        <ol>
            <li>Fav4</li>
        </ol>                     <?=getRssfeed("$url","Spiegel","auto",10,3);?>
    </div>
    </div>
    </div>
           <?
        }
    }
}
?>
        
        
        
        
        
        
        
    </div>
</div>
</div>
</div>