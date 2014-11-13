<?
session_start();
include("inc/config.php");
include("inc/functions.php");
$group = (int)$_GET['id'];
$groupClass = new groups();
$groupData = $groupClass->getGroupData($group);
$link = "group.php?id=$group";

//check if user is admin
$admins = explode(";", $groupData['admin']);
if (in_array(getUser(), $admins)) {
   $admin = "1";
}

//check if user is member
$memberSql = mysql_query("SELECT * FROM groupAttachments WHERE `group`='$group' AND item='user' AND itemId='$userid'");
$memberData = mysql_fetch_array($memberSql);
if (!empty($memberData['itemId'])) {
   $member = "1";
}
?>
    <div id="profileWrap" class="group_<?=$group;?>">
    <div>&nbsp;</div>
    <div class="signatureGradient" style="height: 80px; padding: 15px; font-size: 17pt; margin-top: -18px;">
        <img src="./gfx/icons/group.png" style="float: right;">
        <p style="float: left; margin-top: 5px; margin-left: 20px; margin-right: 10px; font-size:18px; text-align: right;"><?=nl2br($groupData[description]);?></p>
        <p style="float: right; margin-top: 5px; margin-left: 20px; margin-right: 10px; font-size:18px; text-align: right;"><b><?=$groupData[title];?></b><br>
                <span id="joinButton">
                    <?
                    if(proofLogin()){
                        if(!empty($admin)){?>
                        <a href="#" onclick="javascript: popper('doit.php?action=groupAdmin&id=<?=$group;?>')" class="btn btn-info" style="margin-top: 10px;">Admin</a>
                        <?
                        }
                        if(empty($member)){ 
                        if($groupData["public"] == "1"){?>
                        <a href="doit.php?action=joinGroup&group=<?=$group;?>&val=1" target="submitter" class="btn btn-info" style="margin-top: 10px;">Join</a>
                        <?
                        }else{?>
                        <a href="doit.php?action=joinGroup&group=<?=$group;?>&val=1" target="submitter" class="btn btn-info" style="margin-top: 10px;">Send Request</a>
                        <?}
                        }else{
                            if($groupData['membersInvite'] == "1" || $admin == "1"){
                        ?>
                        <a href="#" onclick="javascript: popper('doit.php?action=groupInviteUsers&id=<?=$group;?>')" class="btn btn-info" style="margin-top: 10px;">Invite Friends</a>
                        <?
                          }
                        ?>
                        <a href="doit.php?action=groupLeave&id=<?=$group;?>" class="btn btn-danger" target="submitter" style="margin-top: 10px;" target="submitter">Leave</a>
                        <? }
                    }?>
                </span>
        </p>
    </div>
    <table width="100%" height="40" cellspacing="0">
        <tr style="border-top: 1px solid #424242;">
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupActivity');"><img src="./gfx/icons/rss.png">&nbsp;Activity</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupUsers');"><img src="./gfx/icons/group.png">&nbsp;Users</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupPlaylists');"><img src="./gfx/icons/playlist.png">&nbsp;Playlists</td>
        </tr>
    </table>
    <div id="groupActivity" class="groupSlider">
        
        
        <div>
                <?php
                $classFeed = new feed();
                $classFeed->show("group", "$group");
                ?>   
        </div>
        
        
        
    </div>
    <div id="groupUsers" class="groupSlider" style="display: none;">
          <table cellspacing="0" style="font-size: 9px;" width="100%">
          <?
          $groupUseSql = mysql_query("SELECT * FROM groupAttachments WHERE `group`='$group' AND validated='1'");
          while($groupUseData = mysql_fetch_array($groupUseSql)){
              if($i%2 == 0){
                  $color="FFFFFF";
              }else {
                  $color="e5f2ff";
              }
              $i++;
              $groupUserSql = mysql_query("SELECT username FROM user WHERE userid='$groupUseData[itemId]'");
              $groupUserData = mysql_fetch_array($groupUserSql);
              ?>
              <tr bgcolor="#<?=$color;?>" height="35">
                  <td width="35">&nbsp;<?=showUserPicture($groupUseData, 15);?></td>
                  <td><?=$groupUserData[username];?></td>
              </tr>
          <? } ?>
          </table>
    </div>
    <div id="groupFiles" class="groupSlider" style="display:none">
        <table cellspacing="0" style="font-size: 9px;" width="100%">
          <?php
        	$query = "WHERE INSTR(`privacy`, '{$group}') > 0 ORDER BY timestamp DESC LIMIT 5";
          
        
                        //showFileBrowser($folder);
                        ?>
                        <table cellspacing="0" width="100%">
                        <?
                        
                        //showFileList('', $query);
                        echo'</table>';
                        ?>
          </table>
    </div>
    <div id="groupPlaylists" class="groupSlider" style="display:none">
	    <table cellspacing="0" width="100%">
	        <?
	        $needle = "$group;";
	        $playListSql = mysql_query("SELECT * FROM `playlist` WHERE `privacy` LIKE %$group%");
	        while($playListData = mysql_fetch_array($playListSql)){
	        if(authorize($playListData['privacy'], 'show', $playListData['user'])){
	        $i++    
	            ?>
	        <tr border="0" bgcolor="#<?=$color;?>" width="100%" height="35">
	            <td width="35">&nbsp;<img src="./gfx/icons/playlist.png"></td>
	            <td><a href="javascript: popper('doit.php?action=showPlaylist&id=<?=$playListData['id'];?>')"><?=$playListData['title']?></a></td>
	        </tr>
	        <? }} ?>
	    </table>
    </div>
    <?
    $classComments = new comments();
    $classComments->showComments(group, $group, $GroupData[title], $link);
    ?>