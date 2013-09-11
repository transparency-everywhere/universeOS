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
        <div id="buddyListFrame">
        <? } ?>
            <table width="100%" cellspacing="0">
            <?
            
            $buddies = buddyListArray();
			foreach($buddies AS $buddy){
				
				$username =  useridToUsername($buddy);
			
    ?>
                <tr class="strippedRow height60">
	                 <td style="padding-left: 3px; width: 35px;"><?=showUserPicture($buddy, "30");?></td>
	                 <td><a href="#" onclick="openChatDialoge('<?=$username;?>');"><?=$username;?></a></td>
	                 <td>
	                 	
	                 	<div class="btn-toolbar">
						  <div class="btn-group">
						    <a class="btn btn-mini" href="#" onclick="showProfile('<?=$buddy;?>')"><i class="icon-user"></i></a>
						    <a class="btn btn-mini" href="#" onclick="popper('doit.php?action=writeMessage&buddy=<?=$buddy;?>')"><i class="icon-envelope"></i></a>
						  </div>
						</div>
					 </td>
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
}?>
        </div>
   </div>
   <?
$mayKnow = friendsYouMayKnow();
if(!empty($mayKnow)){
	
	echo"<div id=\"buddySuggestions\">";
	echo"<header>";
	echo"&nbsp;you may know";
	echo"<a id=\"closeSuggestions\" onclick=\"$('#buddySuggestions').hide();\">x</a>";
	echo"</header>";
	echo"&nbsp<a href=\"#\" onclick=\"showProfile('$mayKnow')\">";
	echo"&nbsp;";
	echo"<span>";
	echo showUserPicture($mayKnow, 11);
	echo"</span>";
	echo useridToUsername($mayKnow);
	echo"</a>";
	echo"</div>";
	
}
?>
</div>
<? } ?>