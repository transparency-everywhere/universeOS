<?php
session_start();
if($_GET[reload] == "1"){
    $includepath = "../../inc";
}else{
$includepath = "inc";
}
$includepath = "$path$includepath";
include("$includepath/config.php");
include_once("$includepath/functions.php");

$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);

if($_GET[action] == "add"){
    $type = $_GET[type];
    $check = mysql_query("SELECT type,item FROM fav WHERE type='$_GET[type]' && item='$_GET[item]'");
    $checkData = mysql_fetch_array($check);
    if(isset($checkData[type])){
        echo"allready your favourite";
    } else {
        $time = time();
        mysql_query("INSERT INTO fav (`type` ,`item` ,`user` ,`timestamp`) VALUES('$_GET[type]', '$_GET[item]', '$_SESSION[userid]', '$time');"); 
        echo"worked :)";
    }
} else{
    $url = "http://feeds.bbci.co.uk/news/rss.xml";
    ?>
    
      <?
      if(!isset($_GET[action])){ 
      	
      	
      	?>
        <div>
            <header class="signatureGradient" style="height: 60px; padding: 15px; font-size: 17pt;">
                <span style="float: left;">Heydiho <a href="#" onclick="showProfile(<?=$_SESSION[userid];?>)"><?=$userData[username];?></a>,<p style="font-size: 13pt;">good to see you!</p></span>
                <span style="float: right"><?=showUserPicture($_SESSION[userid], "50");?></span>
            </header>
            <table width="100%" height="40" cellspacing="0" style="cursor: pointer;">
                <tr style="border-top: 1px solid #c9c9c9;" id="favTabBar">
                	<!-- this cols toggle the tabs the javascript is located at bottom -->
                    <td width="25%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive active" id="showfavGroup"><span class=""><img src="./gfx/icons/group.png">&nbsp;Groups</span></td>
                    <td width="25%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" id="showfavPlayList"><span class=""><img src="./gfx/icons/playlist.png">&nbsp;Playlists</span></td>
                    <td width="25%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" id="showfavNews"><span class=""><img src="./gfx/icons/rss.png">&nbsp;News</span></td>
                    <td width="25%" align="center" style="border-right: 1px solid #CFCFCF;" class="grayBar interactive" id="showfavFiles"><span class=""><img src="./gfx/icons/folder.png">&nbsp;Files</span></td>
                </tr>
            </table>
            <div style="position: absolute;  top: 151px; right: 0px; bottom: 0px; left: 0px; background: #FFFFFF; overflow: auto; padding: 15px;" class="favTab" id="favTab_Group">
                   
                    <h3 class="readerStartItem">
                        <img src="./gfx/icons/group.png" height="14">&nbsp;Your Groups <a href="#" class="btn btn-info btn-small bsPopOver" data-toggle="popover" title="" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" id="popoooo"><i class="icon-info-sign"></i></a> <span style="float:right"><a href="javascript: popper('doit.php?action=addGroup');" class="btn"><img src="./gfx/icons/group.png" height="14">&nbsp;Add Group</a>  </span>
                    </h3>
                    <table class="border-top-radius border-box readerStartItem" cellspacing="0"  style="border: 1px solid #c9c9c9; margin-top: -15px;">
                       <tr class="grayBar" height="35">
                           <td width="27">&nbsp;</td>
                           <td width="300">&nbsp;name</td>
                           <td align="right">members&nbsp;&nbsp;</td>
                       </tr>
                       <?
                       $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='$userid' AND validated='1'");
                       while($attData = mysql_fetch_array($attSql)){
                           $i++;
                           $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                           $groupData = mysql_fetch_array($groupSql);
                       $result = mysql_query("SELECT * FROM groupAttachments WHERE group='2'");
                       $num_rows = mysql_num_rows($result);
                           $title = $groupData[title];
                           $title10 = substr("$title", 0, 10);
                           $title15 = substr("$title", 0, 25);
                           ?>
                               <tr height="30" class="strippedRow">
                                   <td width="27">&nbsp;<img src="./gfx/icons/group.png" height="15"></td>
                                   <td width="300">&nbsp;<a href="#" onclick="createNewTab('reader_tabView','<?=$title10;?>','','group.php?id=<?=$groupData[id];?>',true);return false"><?=$title15;?></a></td>
                                   <td align="right"><?=countGroupMembers($groupData[id]);?>&nbsp;&nbsp;</td>
                               </tr>
                       <?}
                       if($i < 1){
                           echo"<tr><td>Your are in no group</td></tr>";
                       }
                       ?>
                     </table>
            </div>
            <div style="position: absolute;  top: 151px; right: 0px; bottom: 0px; left: 0px; background: #FFFFFF; overflow: auto; padding: 15px; display: none;" class="favTab" id="favTab_playList">
                
                    <h3 class="readerStartItem">
                        <img src="./gfx/icons/playlist.png" height="14">&nbsp;Your Playlists<span style="float: right;"><a href="javascript: popper('doit.php?action=addPlaylist');" class="btn"><img src="./gfx/icons/playlist.png" height="14">&nbsp;Add Playlist</a></span>
                    </h3>
                      <table class="border-top-radius border-box readerStartItem" cellspacing="0" style="border: 1px solid #c9c9c9; margin-top: -15px;">
                          <tr class="grayBar" height="35">
                              <td width="27">&nbsp;</td>
                              <td width="200">Title</td>
                              <td align="right">Played&nbsp;&nbsp;</td>
                          </tr>
                            <?PHP
                            unset($i);
                            
                            
                            
                                $userGroupsSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='$_SESSION[userid]'");
                                while($userGroupsData = mysql_fetch_array($userGroupsSql)){
                                    $userGroups[] = "$userGroupsData[group]";
                                }
                                foreach($userGroups AS &$userGroup){
                                    $query = "$query OR INSTR(`privacy`, '{$userGroup}') > 0";
                                }
                            
                            
                            $playListSql = mysql_query("SELECT id, user, title, played FROM playlist WHERE user='$_SESSION[userid]' $query");
                            while($playListData = mysql_fetch_array($playListSql)){
                            $i++;
                            ?>
                            <tr border="0" class="strippedRow" width="100%" height="30">
                                <td width="27">&nbsp;<img src="./gfx/icons/playlist.png"></td>
                                <td width="150"><a href="javascript: showPlaylist('<?=$playListData[id]?>');"><?=$playListData[title]?></a></td>
                                <td align="right"><?=$playListData[played];?>&nbsp;&nbsp;</td>
                            </tr>
                            <?PHP
                            } 
                            if(empty($i)){
                                echo"<tr><td colspan=\"3\">Add playlist to automaticly play mp3´s and Youtube Songs in a row.</td></tr>";
                            }
?>
                        </table><br><br>
            </div>
            <div style="position: absoute; top: 140px; bottom: 0px; background: #FFFFFF; overflow: auto; display: none;" class="favTab" id="favTab_News">
                <div class="leftNav" style="top: 151px;">
                    <ul style="padding: 10px;" id="rssFavList">
                        <?
                        $favSql = mysql_query("SELECT item FROM fav WHERE user='$_SESSION[userid]' AND type='link'");
                        while($favData = mysql_fetch_array($favSql)){
                            $favLinkSql = mysql_query("SELECT id, title, link FROM links WHERE id='$favData[item]'");
                            $favLinkData = mysql_fetch_array($favLinkSql);
                                            $title = substr($favLinkData[title], '0', '15');
                                            echo"<li><img src=\"./gfx/icons/rss.png\" height=\"10\">&nbsp;<a href=\"#\" onclick=\"loader('newsContentFrame','doit.php?action=showSingleRssFeed&id=$favData[item]');\">$title</a></li>";
                        }
                        ?>
                        <li><img src="./gfx/icons/rss.png" height="10">&nbsp;<a href="#" onclick="loader('newsContentFrame','doit.php?action=showSingleRssFeed&id=12');">BBC News - World</a></li>
                    </ul>
                    <ul>
                        <li><i style="font-size: 8pt;">Add RSS Feeds to your favourites, to see them here</i></li>
                    </ul>
                </div>
                    <div style="top: 151px;" class="frameRight" id="newsContentFrame">
            <?php
            $url = "http://feeds.bbci.co.uk/news/world/rss.xml";
            echo"<div style=\"padding: 20px;\">";
            showRssFeed($url);
            echo"</div>";
            ?>
                    </div>
            </div>
            <div style="position: absoute; top: 140px; bottom: 0px; background: #FFFFFF; overflow: auto; display: none;" class="favTab" id="favTab_Files">

                    <div class="leftNav" style="top: 151px;">
                    <ul style="float: left; background: #e5f2ff; padding: 10px;">
                        <li onclick="loader('personalFileFrame','doit.php?action=loadPersonalFileFrame&query=allFiles');"><img src="./gfx/icon7.png" width="16">&nbsp;All Files</li>
                        <li onclick="loader('personalFileFrame','doit.php?action=loadPersonalFileFrame&query=myFiles');"><img src="./gfx/icons/filesystem/element.png" width="17" style="margin-bottom: -2px;">&nbsp;myFiles</li>
                        <li><i style="font-size: 8pt;">some Info about this list.</i></li>
                    </ul>
                    </div>
                    <div class="frameRight" style="top: 151px;" id="personalFileFrame">
                        <?
		                  $user = $_SESSION[userid];
		                 
		                  //show folders and elements
		                  $folderQuery = "WHERE creator='$user' ORDER BY timestamp DESC";
		                  $elementQuery = "WHERE author='$user' ORDER BY timestamp DESC";
		                  showFileBrowser($folder, "$folderQuery", "$elementQuery");
		                  
		                  //show files
		                  $fileQuery = "owner='$user' ORDER BY timestamp DESC";
		                  echo'<table width="100%">';
		                  showFileList('', $fileQuery);
		                  echo"</table>";
                        ?>
                        
                        
                        
                    </div>
                
            </div>
        </div>
        <script>
            $("#newsBBC").click(function() {
                $("#dashNews").load("doit.php?action=showNewsFeedTitles&id=1");
            });
            
            $("#showfavGroup").click(function() {
            	$('#favTabBar .interactive').removeClass('active');
                $(".favTab").slideUp();
                $("#favTab_Group").slideDown();
                $('#showfavGroup').parent("td").addClass('active');
            });            
            $("#showfavPlayList").click(function() {
            	$('#favTabBar .interactive').removeClass('active');
                $(".favTab").slideUp();
                $("#favTab_playList").slideDown();
                $('#showfavPlayList').parent("td").addClass('active');
            });            
            $("#showfavNews").click(function() {
            	$('#favTabBar .interactive').removeClass('active');
                $(".favTab").slideUp();
                $("#favTab_News").slideDown();
                $('#showfavNews').parent("td").addClass('active');
            });            
            $("#showfavFiles").click(function() {
            	$('#favTabBar .interactive').removeClass('active');
                $(".favTab").slideUp();
                $("#favTab_Files").slideDown();
                $('#showfavFiles').parent("td").addClass('active');
            });
        </script>
<?
}
?>
        <? } ?>