<?
if(empty($_GET[user])){
    die("error: no user selected");
}
session_start();
include("inc/config.php");
include("inc/functions.php");

$user = save("$_GET[user]");
$profilesql = mysql_query("SELECT * FROM user WHERE userid='".mysql_real_escape_string($_GET[user])."'");
$profiledata = mysql_fetch_array($profilesql);
if($_GET[showFeed] == "1"){
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="inc/style.css">  
    </head>
    <body>
<table width="100%" cellspacing="0">
    <?
        $userFeedSql = mysql_query("SELECT * FROM userfeeds WHERE owner='$user' ORDER BY timestamp DESC LIMIT 15");
        while($userFeedData = mysql_fetch_array($userFeedSql)) {
                if($userFeedData[protocoll_type] == "fileUpload"){
                $folderAddSql = mysql_query("SELECT * FROM elements WHERE id='$userFeedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"createNewTab('fileBrowser_tabView','".$folderAddData[title]."','','modules/filesystem/showelement.php?element=".$folderAddData[id]."',true);return false\"> ".$folderAddData[title]."</a>";
                }if($userFeedData[protocoll_type]=="elementAdd"){
                $folderAddSql = mysql_query("SELECT * FROM folders WHERE id='$userFeedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData[id]."&reload=1');return false\"> ".$folderAddData[name]."/</a>";   
                }if($userFeedData[protocoll_type]=="folderAdd"){
                $folderAddSql = mysql_query("SELECT * FROM folders WHERE id='$userFeedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData[id]."&reload=1');return false\"> ".$folderAddData[name]."/</a>";   
                }
                if($i%2 == 0){
                    $color="FFFFFF";
                }else {
                    $color="e5f2ff";
                }
                $i++
                ;?>
            <tr bgcolor="#<?=$color;?>" height="35">
                <td><?=$profiledata[username];?>&nbsp;<?=nl2br(universeText(htmlspecialchars($userFeedData[feed])));?><?=$text;?></td>
            </tr>
<?        }

    echo "</table>";
}else{
$link = "profile.php?user=$user";

if(!empty($profiledata[realname])){
$name = "$profiledata[realname]";
}
if($profiledata[birthdate]){
$birth_day = date("dS", $profiledata[birthdate]);
$birth_month = date("F", $profiledata[birthdate]);
if(isset($birth_day) AND isset($bith_month)){
$birthtext .= "Born on the $birth_day of $birth_month";    
}
$birth_year = date("Y", $profiledata[birthdate]);
if(!empty($birth_year) && !empty($birthtext)){
$birthtext .= " $birth_year";
}
if(isset($birthtext)){
    $birthtext .= ".";
}
}
if(isset($profiledata[home])){
$profileText = "From $profiledata[home]";
}
if(isset($profiledata[place])){
    if(isset($profileText)){
    $profileText .= ", lives in $profiledata[place]";
    }else{
    $profileText .= "Lives in $profiledata[place]";
    }
}
if(isset($profiledata[school1])){
    if(isset($profileText)){
    $profileText .= ", went to $profiledata[school1]";
    }else{
    $profileText .= "Went to $profiledata[school1]";
    }
}
if(isset($profileText)){
    $profileText .= ".";
}


$checkSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' AND buddy='$user'");
$checkData = mysql_fetch_array($checkSql);
if(!empty($checkData)){
    if($checkData[request] == "1"){
        $friendButton = "<a href=\"#\" class=\"btn disabled friendButton_$user\">request sent</a>";
    }else{
        $friendButton = "";
    }
}else{
    if($user !== $_SESSION[userid]){
    $friendButton = "<a href=\"./doit.php?action=addbuddy&buddy=$user\" target=\"submitter\" class=\"btn friendButton_$user\">Add to Buddylist</a>";
    }
    
}
if(empty($friendButton)){
    $checkSql1 = mysql_query("SELECT * FROM buddylist WHERE buddy='$user' AND owner='$_SESSION[userid]'");
    $checkData1 = mysql_fetch_array($checkSql1);
    if(!empty($checkData1)){
        if($checkData1[request] == "1"){
            $friendButton = "<a href=\"#\" class=\"btn friendButton_$user\">Accept Request</a>";
        }else{
            $friendButton = "";

        }
    }else{
        if($user !== $_SESSION[userid]){
        $friendButton = "<a href=\"./doit.php?action=addbuddy&buddy=$user\" target=\"submitter\" class=\"btn friendButton_<?=$user;?>\">Add to Buddylist</a>";
    
        }
    }
}
if(empty($friendButton)){
    
}


if(isset($_GET[scoreaction])){
    if($_GET[scoreaction] == voteplus){
        plusOne($_SESSION[userid], comments, $_GET[typeid]);
    }
}
?>   
    <div class="signatureGradient" style="height: 80px; padding: 15px; font-size: 17pt;">
        <span style="float: left;"><a href="#" onclick="showProfile(<?=$profiledata[userid];?>)"><?=$profiledata[username];?></a><span style="float: right"><?=$name;?></span><br><p style="font-size: 13pt;"><?=$birthtext;?> <?=$profileText;?></p>
            <div style="margin-top: 15px;">
                <?
                if(!empty($_SESSION[userid])){
                ?>
                <?=$friendButton;?>
                <a href="#" onclick="popper('doit.php?action=writeMessage&buddy=<?=$profiledata[userid];?>')" class="btn">Message</a>
                <a href="#" class="btn">Kick</a>
                <? } ?>
             </div></span>
        <span style="float: right"><a href="#" onclick="openElement('<?=$profiledata[profilepictureelement];?>', 'Userpictures'); return false;"><?=showUserPicture($profiledata[userid], "50");?></a><br>
        </span>
    </div>
    <div id="profileWrap">
    <table width="100%" height="40" cellspacing="0">
        <tr style="border-top: 1px solid #424242;" class="grayBar">
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleProfileTabs('profileAktivity');"><img src="./gfx/icons/rss.png">&nbsp;Activity</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleProfileTabs('profileFav');"><img src="./gfx/icons/fav.png">&nbsp;Fav</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleProfileTabs('profileFiles');"><img src="./gfx/icons/folder.png">&nbsp;Files</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleProfileTabs('profileFriends');"><img src="./gfx/icons/group.png">&nbsp;Friends</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleProfileTabs('profilePlaylists');"><img src="./gfx/icons/playlist.png">&nbsp;Playlists</td>
        </tr>
    </table>
    <div style="height: 210px;">
    <div id="profileAktivity" class="profileSlider">
        
        <div>
                <?showFeedNew("singleUser", "$user");?>   
        </div>
        
    </div>
    <div id="profilePlaylists" class="profileSlider" style="display: none;">
     
                        <table class="border-top-radius border-box" cellspacing="0" width="100%">
                            <?
                            $playListSql = mysql_query("SELECT id, user, title FROM playlist WHERE user='$user'");
                            while($playListData = mysql_fetch_array($playListSql)){
                            if($i%2 == 0){
                                $color="FFFFFF";
                            }else {
                                $color="e5f2ff";
                            }
                            $i++    
                                ?>
                            <tr border="0" bgcolor="#<?=$color;?>" width="100%" height="35">
                                <td width="35">&nbsp;<img src="./gfx/icons/playlist.png"></td>
                                <td><a href="javascript: popper('doit.php?action=showPlaylist&id=<?=$playListData[id];?>')"><?=$playListData[title]?></a></td>
                            </tr>
                            <? } ?>
                        </table>
    </div>
    <div id="profileFiles" class="profileSlider" style="display: none;">
        <?
        
                        $folderQuery = "WHERE creator='$user' ORDER BY timestamp DESC";
                        $elementQuery = "WHERE author='$user' ORDER BY timestamp DESC";
                        showFileBrowser($folder, "$folderQuery", "$elementQuery");
                        ?>
                        <table cellspacing="0" width="100%">
                        <?
                        
                        $fileQuery = "owner='$user' ORDER BY timestamp DESC";
                        showFileList('', $fileQuery);
                        echo"</table>";
        
        ?>
    </div>
    <div id="profileFriends" class="profileSlider" style="display: none;">
        <table width="100%" cellspacing="0">
        <?
        $friendSql = mysql_query("SELECT * FROM buddylist WHERE owner='$user'");
        while($friendData = mysql_fetch_array($friendSql)){
            if($i%2 == 0){
                $color="FFFFFF";
            }else {
                $color="e5f2ff";
            }
            $usernameSql = mysql_query("SELECT username FROM user WHERE userid='$friendData[buddy]'");
            $usernameData = mysql_fetch_array($usernameSql);
            ?>
            <tr bgcolor="#<?=$color;?>" height="35">
                <td width="40">&nbsp;<?=showUserPicture($friendData[buddy], "20");?></td>
                <td><a href="#" onclick="javascript: showProfile('<?=$friendData[buddy];?>');"><?=$usernameData[username];?></a></td>
            </tr>
            <?
        $i++;}?>
        </table>
    </div>
    <div id="profileFav" class="profileSlider" style="display: none;">
    	<table width="100%">
	      <?
	      showFav($user);
	      ?>
    	</table>
    </div>
    </div>
    
    

    
    
            <div class="border-radius">
            </div>
            <center>
                    <div style="width: 90%; border:1px solid #c9c9c9;">
                    <?
                    showComments(profile, $user, $profiledata[username], $link);
                    ?>
                    </div>
            </center>
    </div>
<?} ?>