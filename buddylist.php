<?
if(empty($_SESSION[userid])){
    session_start();
}
require_once("inc/config.php");
require_once("inc/functions.php");
if(empty($_GET[reload])){
?>
<div class="fenster" id="buddylist" style="display: none;">
    <header class="titel">
        <p>Buddylist&nbsp;</p><p class="windowMenu"><a href="javascript: toggleApplication('buddylist');" style="color: #FFF;"><img src="./gfx/icons/close.png" width="16"></a></p>
    </header>
    <div class="inhalt">
<? if(1 == 2){ ?>
        <div class="blackgradient windowHeader" id="buddyListheader">
            <hgroup>
                <h3 style="margin-top:25px;"><center><div id="userdock" class="module" style="height: 36px; margin-bottom: 30px;"><a href="javascript: openPersonalFeed()"><?=showUserPicture($userdata['userid'], 30);?></a></div></h3>
            </hgroup>
        </div>
<? } ?>
        <div id="buddyListFrame">
        <? } ?>
            <table width="100%" cellspacing="0">
            <?
$buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' && request='0'");
while($buddylistData = mysql_fetch_array($buddylistSql)) {
    $blUserSql = mysql_query("SELECT * FROM user WHERE userid='$buddylistData[buddy]'");
    $blUserData = mysql_fetch_array($blUserSql);
    
    //sets a hasttag for this 
    $userRow = md5("$blUserData[lastactivity],$userRow");
    if(!empty($buddylistData[alias])){
        $username = $buddylistData[alias];
    } else{
    $username = htmlspecialchars($blUserData[username]);
        }
    ?>
                <tr class="strippedRow height60">
                 <td style="padding-left: 3px;"><?=showUserPicture($blUserData['userid'], "30");?></td>
                 <td><a href="#" onclick="openChatDialoge('<?=$username;?>');"><?=$username;?></a></td>
                 <td><a href="#" onclick="createNewTab('reader_tabView','<?=$blUserData[username];?>','','./profile.php?user=<?=$blUserData[userid];?>',true);return false"><img src="./gfx/user.gif"></a></td>
                 <td>&nbsp;</td>        
                 <td><a href="#" onclick="popper('doit.php?action=writeMessage&buddy=<?=$blUserData[userid];?>')"><img src="./gfx/mail.gif"></a></td>
                </tr>
<?
$i++;
}
$_SESSION[reloadBuddylist] = "$userRow";
?>
            </table>
        <?
if(empty($_GET[reload])){
if(empty($i)){
    ?>
                <div style="font-size: 12pt;">
                    search for the user- or realname of your friends, to add them to your buddylist.
                </div>
    <?
}
$mayKnow = friendsYouMayKnow();
if(!empty($mayKnow)){
	
	echo"<div>";
	echo"you may know<br>";
	echo"<a href=\"#\" onclick=\"showProfile('$mayKnow')\">";
	echo"&nbsp;";
	echo showUserPicture($mayKnow, 25);
	echo useridToUsername($mayKnow);
	echo"</a>";
	echo"</div>";
	
}
?>
        </div>
   </div>
</div>
<? } ?>