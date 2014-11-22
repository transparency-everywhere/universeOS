<?
if(empty($_SESSION['userid'])){
    session_start();
}
require_once("inc/config.php");
require_once("inc/functions.php");
if(empty($_GET['reload'])){
?>
        <div id="buddyListFrame">
        <? } ?>
            <table width="100%" cellspacing="0">
            <?
            $buddyListClass = new buddylist();
            $buddies = $buddyListClass->buddyListArray();
			foreach($buddies AS $buddy){
				
				$username =  useridToUsername($buddy);
			
    ?>
                <tr class="height60 greyHover">
	                 <td style="padding:0 10px; width: 43px;"><?=showUserPicture($buddy, "40");?></td>
	                 <td><a href="#" onclick="openChatDialoge('<?=$username;?>');"><?=$username;?></a><br><a href="#" onclick="openChatDialoge('<?=$username;?>');" class="realname"><?=useridToRealname($buddy);?>&nbsp;</a></td>
	                 <td align="right" style="padding: 0 10px;">
						    <a href="#" onclick="showProfile('<?=$buddy;?>'); return false" title="open Profile"><i class="glyphicon glyphicon-user" style="font-size:12px"></i></a>
						    <a href="#" onclick="openChatDialoge('<?=$username;?>'); return false" title="write Message"><i class="glyphicon glyphicon-envelope" style="font-size:12px"></i></a>
						    <a href="#" onclick="openChatDialoge('<?=$username;?>'); return false" title="write Message"><i class="glyphicon glyphicon-cog" style="font-size:12px"></i></a>
                                                   
					 </td>
                </tr>
<?
$i++;
}
$_SESSION['reloadBuddylist'] = "$userRow";
?>
            </table>
        <?
if(empty($_GET['reload'])){
if(empty($i)){
    ?>
                <div style="font-size: 12pt;">
                    search for the user- or realname of your friends, to add them to your buddylist.
                </div>
<?
}?>
        </div>
   	<?
        
	$buddyListClass->showBuddySuggestions();
	?>
<? } ?>