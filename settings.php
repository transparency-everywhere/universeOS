<?
if(empty($_SESSION[userid])){
    session_start();
    
}
include("inc/functions.php");
include("inc/config.php");
?>

    <script type="text/javascript">

function sendform(f,c)
{
  f.action.match(/(\bkeepThis=(true|false)&TB_iframe=true.+$)/);
  tb_show(c, 'about:blank?'+RegExp.$1);
  f.target=$('#TB_iframeContent').attr('name')
  return true;
}
      </script>
 <body style="background: #7d7e7d;">

      
<?
    if($_GET[action] == "start") {
        ?>
    
<div>
    <div class="leftNav">
    <ul>
        <li><a href="modules/settings/index.php?action=general" target="frameRight">General</a></li>
        <li><a href="javascript:showSettings('profile')">Profile</a></li>
        <li><a href="modules/settings/index.php?action=privacy" target="frameRight">Privacy</a></li>
        <li><a href="modules/settings/index.php?action=friends" target="frameRight">Buddylist</a></li>
        <li><a href="javascript:showSettings('profile')">Media</a></li>
    </ul>
    </div>
    <iframe class="frameRight" name="frameRight" src="modules/settings/index.php?action=general"></iframe>
</div>

<?
    } 
    else if($_GET[action] == general) {
        
        if(isset($_POST[AccSetSubmit])) {
            $birthdate = gmmktime("0", "0", "0", "$_POST[birth_month]", "$_POST[birth_day]", "$_POST[birth_year]");
            mysql_query("UPDATE user SET username='$_POST[AccSetUsername]', realname='$_POST[AccSetRealname]', email='$_POST[AccSetMail]', place='$_POST[place]', home='$_POST[home]', birthdate='$birthdate', school1='$_POST[school1]', university1='$_POST[university1]', employer='$_POST[work]' WHERE userid='$_SESSION[userid]'");
            jsAlert("Succes!");
            }
        $AccSetSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
        $AccSetData = mysql_fetch_array($AccSetSql);
        if($AccSetData[birthdate]){
        $birth_day = date("d", $AccSetData[birthdate]);
        $birth_month = date("m", $AccSetData[birthdate]);
        $birth_year = date("Y", $AccSetData[birthdate]);
        }
     ?>
      <div id="invisiblefeed">
    <? if(empty($_GET[subaction])){ ?>
    <div id="content">
        <p style="position: absolute; top: 15px; right: 10%;"><img src="../../upload/userFiles/<?=$_SESSION['userid'];?>/userPictures/thumb/300/<?=$AccSetData['userPicture'];?>" class="border-radius inlet" width="100"><br><br><a href="index.php?action=general&subaction=image" class="button" target="frameRight">edit</a></p>
        <table style="position: absolute; top: 15px; left: 15px;">
            <tr><form action="index.php?action=general" method="post" target="frameRight">
                <td>Username</td>
                <td><input type="text" name="AccSetUsername" value="<?=$AccSetData[username];?>"></td>                    
            </tr>
            <tr>
                <td>Real Name</td>
                <td><input type="text" name="AccSetRealname" value="<?=$AccSetData[realname];?>"></td>                    
            </tr>
            <tr>
                <td>Email</td>
                
                <td><input type="text" name="AccSetMail" value="<?=$AccSetData[email];?>"></td>                    
            </tr>
            <tr>
                    <td>City</td>
                    <td><input type="text" name="place" value="<?=$AccSetData[place];?>"></td>
            </tr>
                <tr>
                    <td>Hometown</td>
                    <td><input type="text" name="home" value="<?=$AccSetData[home];?>"></td>
                </tr>
                <tr>
                    <td>Birthdate</td>
                    <td><input type="text" size="2" placeholder="dd" name="birth_day" value="<?=$birth_day;?>">.<input type="text" size="2" placeholder="mm" name="birth_month" value="<?=$birth_month;?>">.<input type="text" size="4" placeholder="yyyy" name="birth_year" value="<?=$birth_year;?>"></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td></td>
                </tr>
                <tr>
                    <td>School</td>
                    <td><input type="text" name="school1" value="<?=$AccSetData[school1];?>"></td>
                </tr> 
                <tr>
                    <td>University</td>
                    <td><input type="text" name="university1" value="<?=$AccSetData[university1];?>"></td>
                </tr> 
                <tr>
                    <td>Work</td>
                    <td><input type="text" name="work" value="<?=$AccSetData[employer];?>"></td>
                </tr>
            <tr>
                <td><input type="submit"  name="AccSetSubmit" value="save" class="button"></td>
            </tr></form>
        </table>
    </div>
    <?
    
    }
     if($_GET[subaction] == image) {
         if(isset($_POST[submit])) {
             
             $imgSql = mysql_query("SELECT userid, homePictureFolder, profilepictureelement FROM user WHERE userid='$_SESSION[userid]'");
             $imgData = mysql_fetch_array($imgSql);
             
            $target_path = "../../upload/userFiles/$_SESSION[userid]/userPictures/";
            $path = "$target_path";
            $thumbPath25 = "$target_path/thumb/25";
            $thumbPath40 = "$target_path/thumb/40";
            $thumbPath300 = "$target_path/thumb/300";
            
            $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
            
            
            $imgName = basename($_FILES['uploadedfile']['name']);

            if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
            mkthumb("$imgName",25,25,$path,$thumbPath25); 
            mkthumb("$imgName",40,40,$path,$thumbPath40); 
            mkthumb("$imgName",300,300,$path,$thumbPath300);
            
            mysql_query("INSERT INTO `files` ( `folder`, `title`) VALUES ( '$imgData[profilepictureelement]', '$imgName');");
            mysql_query("UPDATE user SET userPicture='$imgName' WHERE userid='$_SESSION[userid]'");
            jsAlert("The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded");
            
            } else{
                     echo "There was an error uploading the file, please try again!";
                }
            $time = time();
            
            


             
         }
        ?>
    <div>
        <header>
            <hgroup>
                <h2>upload new profilepicture</h2>
                <h3><a href="javascript:history.back();">&lt;&lt;back&lt;&lt;</a></h3>
            </hgroup>
        </header>
        <div>
            <form action="index.php?action=general&subaction=image" method="post" enctype="multipart/form-data" target="frameRight">
                <input type="file" name="uploadedfile"><br><input type="submit" name="submit" value="upload">
            </form>            
        </div>
        <footer>
            <p>Please keep always in mind that everyone can see this infos till you switched it off in your <mark>privacy settings</mark>.<br>It also don't overwrites your old one and is shown beside all of your posts.</p>
        </footer>
    </div>
        
    <?
    } ?>
</div>
<? }
 if($_GET[action] == "friends") {
    if(isset($_POST[submit])){
        $newAlias = htmlspecialchars($_POST[alias]);
        mysql_query("UPDATE buddylist SET alias='$newAlias' WHERE owner='$_SESSION[userid]' && buddy='$_POST[buddy]'");
        jsAlert("worked:)");
    }
?>
     <div>
         <p>Your Buddies:</p>
         <ul style="list-style: none">
         <?
         
            if(isset($_GET[delete])){
                mysql_query("DELETE FROM buddylist WHERE owner='$_SESSION[userid]' && buddy='$_GET[buddy]'");
                jsAlert("worked :(");
            }
         $buddyEditSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]'");
         while($buddyEditData = mysql_fetch_array($buddyEditSql)){
            if(isset($_GET[reload])){
            $userpicture = showUserPicture($buddyEditData[buddy], 30);
            }else{
            $userpicture = showUserPicture($buddyEditData[buddy], 30, 1);
            $path = "../../";
            }
             if(empty($buddyEditData[alias])){  
            $blUserSql = mysql_query("SELECT userid, username FROM user WHERE userid='$buddyEditData[buddy]'");
            $blUserData = mysql_fetch_array($blUserSql);
            $alias = "$blUserData[username]"; 
            } else{
            $alias = "$buddyEditData[alias]";
            }
         ?>
             <li><a href="<?=$path;?>settings.php?action=friends&reload=1&delete=1&buddy=<?=$buddyEditData[buddy];?>" target="frameRight"><img src="<?=$path;?>gfx/delete_2.png" width="16"></a><form action="<?=$path;?>settings.php?action=friends&reload=1" target="frameRight" method="post"><?=$userpicture;?>&nbsp;<input type="hidden" name="buddy" value="<?=$buddyEditData[buddy];?>"><input type="text" name="alias" value="<?=$alias;?>">&nbsp;<input type="submit" name="submit" value="save"></form></li>
             <hr>
         <? } ?>    
         </ul>
     </div>
     <?
   
}
else if($_GET[action] == privacy) { 
    if(!isset($_GET[reload])){
        $path = '../../';
    }
    if($_POST[submit]){
        mysql_query("UPDATE user SET priv_activateProfile='$_POST[priv_activateProfile]', priv_showProfile='$_POST[priv_showProfile]', priv_profileInformation='$_POST[priv_profileInformation]', priv_profilePicture='$_POST[priv_profilePicture]', priv_profileFav='$_POST[priv_profileFav]', priv_profileLog='$_POST[priv_profileLog]', priv_activateFeed='$_POST[priv_activateFeed]', priv_buddyRequest='$_POST[priv_buddyRequest]'");
    }
    $privEditSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
    $privEditData = mysql_fetch_array($privEditSql);
    if($privEditData[priv_activateProfile] == "1"){
        $activateProfile = 'checked="checked"';
    }
    if($privEditData[priv_showProfile] == "1"){
        $activateProfile = 'checked="checked"';
    }
    if($privEditData[priv_profileInformation] == "1"){
        $profileInformation = 'checked="checked"';
    }
    if($privEditData[priv_profilePicture] == "1"){
        $profilePicture = 'checked="checked"';
    }
    if($privEditData[priv_profileFav] == "1"){
        $profileFav = 'checked="checked"';
    }
    if($privEditData[priv_profileLog] == "1"){
        $profileLog = 'checked="checked"';
    }
    if($privEditData[priv_activateFeed] == "1"){
        $activateFeed = 'checked="checked"';
    }
    if($privEditData[priv_buddyRequest] == "1"){
        $buddyRequest = 'checked="checked"';
    }
    ?>
    <div class="jqMailPopUp border-radius box-shadow" id="peppersteak">
        <hgroup>
            <h3>Privacy settings</h2>
            <h2>Profile</h2>
        </hgroup>
        <form action="<?=$path;?>settings.php?action=privacy&reload=1" target="frameRight" method="post">
        <ol style="list-style: none">
            <li><input type="checkbox" name="priv_showProfile" <?=$activateProfile;?> value="1">Activate profile</li>
            <li><input type="checkbox" name="priv_profileInformation" <?=$profilePicture;?> value="1">Show profilepicture to foreigners</li>
            <li><input type="checkbox" name="priv_profilePicture" <?=$profileInformation;?> value="1">Show information to foreigners</li>
            <li><input type="checkbox" name="priv_profileFav" <?=$profileFav;?> value="1">Show favourite files to foreigners</li>
            <li><input type="checkbox" name="priv_profileLog" <?=$profileLog;?> value="1">Show Feed just Friends</li>
        </ol>
        <hr>
        <hgroup>
            <h2>Buddylist</h2>
        </hgroup>
        <ol style="list-style: none">
            <li><input type="checkbox" name="priv_buddyRequest" value="1" <?=$buddyRequest;?>>Users need your allowance to add you on their buddylist</li></li>
        </ol>
        <hr>
        <hgroup>
            <h2>Feed</h2>
        </hgroup>
        <ol style="list-style: none">
            <li><input type="checkbox" name="priv_activateFeed" value="1" <?=$activateFeed;?>>Activate feed</li>
            <li><input type="submit" value="save" name="submit">
        </ol>
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
        </ol> 
    </div>
    </div>
    </div>
           <?
        }
    }
}
?>