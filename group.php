<?
session_start();
include("inc/config.php");
include("inc/functions.php");
$group = $_GET[id];
$needle = "$_GET[id];";
$group = save($group);
$groupSql = mysql_query("SELECT * FROM groups WHERE id='$group'");
$groupData = mysql_fetch_array($groupSql);
$link = "group.php?id=$group";

//check if user is admin
$admins = explode(";", $groupData[admin]);
if (in_array("$_SESSION[userid]", $admins)) {
   $admin = "1";
}

//check if user is member
$memberSql = mysql_query("SELECT * FROM groupAttachments WHERE `group`='$group' AND item='user' AND itemId='$userid'");
$memberData = mysql_fetch_array($memberSql);
if (!empty($memberData[itemId])) {
   $member = "1";
}
?>
    <div id="profileWrap">
    <div>&nbsp;</div>
    <div class="signatureGradient" style="height: 80px; padding: 15px; font-size: 17pt; margin-top: -18px;">
        <img src="./gfx/icons/group.png" style="float: right;">
        <p style="float: left; margin-top: 5px; margin-left: 20px; margin-right: 10px; font-size:18px; text-align: right;"><?=nl2br($groupData[description]);?></p>
        <p style="float: right; margin-top: 5px; margin-left: 20px; margin-right: 10px; font-size:18px; text-align: right;"><b><?=$groupData[title];?></b><br>
                <span style="position: absolute;  width: 300px; height: 40px; margin-top: 20px; right: 20px;">
                    <?
                    if(isset($_SESSION[userid])){
                        if(!empty($admin)){?>
                        <a href="#" onclick="javascript: popper('doit.php?action=groupAdmin&id=<?=$group;?>')" class="btn btn-info" style="margin-top: 10px;">Admin</a>
                        <?
                        }
                        if(empty($member)){ 
                        if($groupData["public"] == "1"){?>
                        <a href="doit.php?action=joinGroup&group=<?=$group;?>val=1" class="btn btn-info" style="margin-top: 10px;">Join</a>
                        <?
                        }else{?>
                        <a href="doit.php?action=joinGroup&group=<?=$group;?>val=1" class="btn btn-info" style="margin-top: 10px;">Send Request</a>
                        <?}
                        }else{
                            if($groupData[membersInvite] == "1" || $admin == "1"){
                        ?>
                        <a href="#" onclick="javascript: popper('doit.php?action=groupInviteUsers&id=<?=$group;?>')" class="btn btn-info" style="margin-top: 10px;">Invite Friends</a>
                        <?
                          }
                        ?>
                        <a href="doit.php?action=groupLeave&id=<?=$group;?>" class="btn btn-danger" style="margin-top: 10px;" target="submitter">Leave</a>
                        <? }
                    }?>
                </span>
        </p>
    </div>
    <table width="100%" height="40" cellspacing="0">
        <tr style="border-top: 1px solid #424242;">
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupActivity');"><img src="./gfx/icons/rss.png">&nbsp;Activity</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupUsers');"><img src="./gfx/icons/group.png">&nbsp;Users</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('groupFiles');"><img src="./gfx/icons/folder.png">&nbsp;Files</td>
            <td width="20%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" onclick="toggleGroupTabs('profilePlaylists');"><img src="./gfx/icons/playlist.png">&nbsp;Playlists</td>
        </tr>
    </table>
    <div id="groupActivity" class="groupSlider">
        
        
        <div>
                <?showFeedNew("group", "$group");?>   
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
          $query = "WHERE privacy LIKE '%$group;%'";
          
        
                        showFileBrowser($folder, "$query", "$query");
                        ?>
                        <table cellspacing="0" width="100%">
                        <?
                        
                        showFileList('', $query);
                        echo"</table>";
                        ?>
          </table>
    </div>
    <div id="groupPlaylists" class="groupSlider" style="display:none">
        <table cellspacing="0" style="font-size: 9px;" width="100%">
        <?
        $query = "WHERE INSTR(`privacy`, '{$needle}') > 0 ORDER BY timestamp DESC LIMIT 5";
        ?>
        </table>
    </div>
    <?
    showComments(group, $group, $GroupData[title], $link);
    ?>