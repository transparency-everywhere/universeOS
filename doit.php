<?
session_start();
if(empty($_GET[action])){
   setcookie("userid","$_SESSION[userid]",time()+(3600*24));
}

include("inc/config.php");
include("inc/functions.php");
$time = time();
if($_GET[action] == "showScore"){
    echo showScore($_GET[type], $_GET[typeid], 1);
} 
if($_GET[action] == "scorePlus"){
    $type = $_GET[type];
    $typeid = $_GET[typeid];
    plusOne($type, $typeid);
    ?>
    <script>
        parent.$('.score<?=$type;?><?=$typeid;?>').load('doit.php?action=showScore&type=<?=$type;?>&typeid=<?=$typeid;?>');

    </script>
    <?
}   else if($_GET[action] == "scoreMinus"){
        $type = $_GET[type];
        $typeid = $_GET[typeid];
        minusOne($type, $typeid);
        ?>
        <script>
            parent.$('.score<?=$type;?><?=$typeid;?>').load('doit.php?action=showScore&type=<?=$type;?>&typeid=<?=$typeid;?>');
        </script>
        <?
    } 
    else if($_GET[action] == "addFav"){
        $type = $_GET[type];
        $check = mysql_query("SELECT type,item FROM fav WHERE type='$_GET[type]' && item='$_GET[item]' && user='$_SESSION[userid]'");
        $checkData = mysql_fetch_array($check);
        if(isset($checkData[type])){
            jsAlert("allready your favourite :/");
        } else {
            $time = time();
            if(mysql_query("INSERT INTO fav (`type` ,`item` ,`user` ,`timestamp`) VALUES('$_GET[type]', '$_GET[item]', '$_SESSION[userid]', '$time');")){ 
            jsAlert("worked :)");
            $insertId = mysql_insert_id();
            $favSQL = mysql_query("SELECT item FROM fav WHERE id='$insertId'");
            $favData = mysql_fetch_array($favSQL);
                            $favLinkSql = mysql_query("SELECT id, title, link FROM links WHERE id='$favData[item]'");
                            $favLinkData = mysql_fetch_array($favLinkSql);
                                            $title = substr($favLinkData[title], '0', '15');
            ?>
            <script>
                parent.$('#rssFavList').append('<li><img src="./gfx/icons/rss.png" height="10">&nbsp;<a href="#" onclick="loader(\'newsContentFrame\',\'doit.php?action=showSingleRssFeed&id=<?=$favData[item];?>\');"><?=$title;?></a></li>');
            </script>
            <?
            
            }
        }
    }else if($_GET[action] == "removeFav"){
    	removeFav($_POST[type], $_POST[typeId]);
    	jsAlert("Your favorite has been removed.");
  	}
    else if($_GET[action] == "requestpositive"){
        $value = "0";
        mysql_query("UPDATE buddylist SET request='$value' WHERE owner='".mysql_real_escape_string($_GET[buddy])."' && buddy='$_SESSION[userid]'");
 		mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('$_SESSION[userid]', '".mysql_real_escape_string($_GET[buddy])."', '$timestamp', '0');");
        jsAlert("worked :)");
    }
    else if($_GET[action] == "requestnegative"){
        mysql_query("DELETE FROM buddylist WHERE owner='".mysql_real_escape_string($_GET[buddy])."' && buddy='$_SESSION[userid]'");
        jsAlert("worked :)");
    }
    else if($_GET[action] == "addbuddy"){
        $timestamp = time();
        $buddy = save($_GET[buddy]);
        $check = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' && buddy='$buddy' OR buddy='$_SESSION[userid]' && owner='$buddy'");
		$checkData = mysql_fetch_array($check);
        if(!isset($checkData[owner])){
            if($buddy == $_SESSION[userid]){
                $message = "that is you;) $buddy";
            }
            $requestSQL = mysql_query("SELECT * FROM user WHERE userid='$buddy'");
            $requestData = mysql_fetch_array($requestSQL);
            if($requestData[priv_buddyRequest] == "1"){
                $request = "1";
            } else{
                $request = "0";
            }
       if(empty($message)){
        mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('$_SESSION[userid]', '$buddy', '$timestamp', '$request');");

        
        //if privacy settings dont need allowance, the user needs to be added on the buddies buddylist
        if($request == "0"){
        	mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('$buddy', '$_SESSION[userid]', '$timestamp', '$request');");
        }
        
        $message = "worked ;)";
       }

        } else {
            $message = "allready there";
        }
        jsAlert($message);
		?>
		<script>
			parent.$('.friendButton_<?=$buddy;?>').html('request sent');
			parent.$('.friendButton_<?=$buddy;?>').addClass("disabled");
		</script>
		<?
     }
     else if($_GET[action] == "download"){
        $documentSQL = mysql_query("SELECT id, title, type, filename FROM files WHERE id='$_GET[fileId]'");
        $documentData = mysql_fetch_array($documentSQL); 
        $downloadfile = getFilePath($_GET[fileId]);
        $downloadfile = substr($downloadfile, 1);
        $filename = $documentData[filename];
        $downloadfile = "upload/$downloadfile/$filename";
        $filesize = filesize($downloadfile);
        $filetype = end(explode('.', $filename));
        header("Content-type: application/$filetype");
        header("Content-Disposition: attachment; filename=".$filename."");
        readfile("$downloadfile");
        exit;
     }else if($_GET[action] == "showPlaylist"){
        $playListId = save($_GET[id]);
        $playListSql = mysql_query("SELECT * FROM playlist WHERE id='$playListId'");
        $playListData = mysql_fetch_array($playListSql);
        if($playListData[user] == "$_SESSION[userid]"){
            $delete = TRUE;
        }
            ?>
            <div class="jqPopUp border-radius transparency" id="showPlaylist">
                    
                    <header>
                    		<a class="jqClose">X</a>
                            <img src="./gfx/icons/playlist.png">&nbsp;<?=$playListData[title];?>
                    </header>
                    <div class="jqContent">
                        <center>
                            <div style="overflow: auto; width: 450px; height: 200px;">
                                <table cellspacing="0" width="100%">                
                            <?
                            $i = 0;
                            $query = commaToOr("$playListData[folders]", "id");
                            $playListFolderSql = mysql_query("SELECT * FROM folders WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation(folder, $playListFolderData[id])){;
                            ?>
                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/folder.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData[name]?></td>
                                    </tr>

                            <? 
                            $i++;

                            }}
                            $query = commaToOr("$playListData[elements]", "id");
                            $playListFolderSql = mysql_query("SELECT id, title FROM elements WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData[privacy])){   
                            ?>

                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;e_<?=$playListFolderData[title]?></td>
                                    </tr>
                        <? 
                            $i++;

                            }}
                            $query = commaToOr("$playListData[files]", "id");    
                            $playListFolderSql = mysql_query("SELECT * FROM files WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData[privacy])){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=file&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListfileNo<?=$playListFolderData[id];?>">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData[title]?></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++; }}
                            $query = commaToOr("$playListData[links]", "id");
                            $playListFolderSql = mysql_query("SELECT * FROM links WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData[privacy])){    
                                if($playListLinkData[type] == "youTube"){

                                }
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=link&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }

                            ?>

                                    <tr class="strippedRow playListlinkNo<?=$playListFolderData[id];?>">
                                        <td><img src="./gfx/icons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')"><?=$playListFolderData[title]?></a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                        <?
                            $i++; }}
                            $videos = explode(";", $playListData[youTube], -1);
                            foreach($videos as &$vId){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=youTube&itemId=$vId\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListyouTubeNo<?=$vId;?> tooltipper" onmouseover="mousePop('youTube', '<?=$vId;?>', '');" onmouseout="$('.mousePop').hide();">
                                        <td><img src="./gfx/icons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')">Youtube Video</a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++;

                            }?>
                                </table>
                            </div>
                        </center>
                    </div>
                            <footer>
                                <a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '0')" class="btn btn-success"><i class="icon-play icon-white"></i>&nbsp;Play</a>&nbsp;
                                <? if(proofLogin()){ ?>
                                <a href="#" onclick="popper('doit.php?action=copyPlaylist&playlist=<?=$playListData[id];?>')" class="btn btn-info" style="color: #FFFFFF"><i class="icon-share icon-white"></i>&nbsp;Copy</a>&nbsp;
                                <a href="#" onclick="popper('doit.php?action=editItem&type=playlist&itemId=<?=$playListData[id];?>')" class=" btn btn-warning" style="color: #FFFFFF"><i class="icon-edit icon-white"></i>&nbsp;Edit</a>
                                <? } ?>
                            </footer>
            	<script>	
				$('.jqClose').click(function(){
					$('.jqPopUp').hide();
				});
            	</script>
            </div>
    <?
     }
    else if($_GET[action] == "addFileToPlaylist"){
         
         $playlist = $_POST[playlistId];
         $newPlaylist = $_POST[playlistName];
         $file = "$_GET[file]";
         $folder = "$_GET[folder]";
         $element = "$_GET[element]";
         $link = "$_GET[link]";
         
         
                    if((!empty($playlist))OR(!empty($newPlaylist))){
                    $UpdateStringSql = mysql_query("SELECT id, files, folders, links FROM playlist WHERE id='$playlist'");
                    $UpdateData = mysql_fetch_array($UpdateStringSql);
                    if(!empty($newPlaylist)){
                        mysql_query("INSERT INTO playlist (user, title) VALUES('$_SESSION[userid]', '$newPlaylist')");
                        $playlist = mysql_insert_id();
                    }
                        if(!empty($file)){
                        $files= "$file;$UpdateData[files]";
                        mysql_query("UPDATE playlist SET files='$files' WHERE id='$playlist'");
                        }
                        if(!empty($folder)){
                        $folders = "$folder;$UpdateData[folders]";
                        mysql_query("UPDATE playlist SET folders='$folders' WHERE id='$playlist'");
                        }
                        if(!empty($element)){
                        $elements = "$element;$UpdateData[elements]";
                        mysql_query("UPDATE playlist SET elements='$elements' WHERE id='$playlist'");
                        }
                        if(!empty($link)){
                        $links = "$link;$UpdateData[links]";
                        mysql_query("UPDATE playlist SET links='$links' WHERE id='$playlist'");
                        }

                    jsAlert("wouhuhuhuhu $file $_GET[playlistId] $tets");
                }else{
                    if(!empty($file)){
                    $fileSql = mysql_query("SELECT id, title FROM files WHERE id='$file'");
                    $fileData = mysql_fetch_array($fileSql);
                    }
                    if(!empty($folder)){
                    $fileSql = mysql_query("SELECT id, name FROM folders WHERE id='$folder'");
                    $fileData = mysql_fetch_array($fileSql);
                    $fileData[title] = $fileData[name];
                    }
                    if(!empty($element)){
                    $fileSql = mysql_query("SELECT id, title FROM elements WHERE id='$element'");
                    $fileData = mysql_fetch_array($fileSql);
                    }
                    if(!empty($link)){
                    $fileSql = mysql_query("SELECT id, title FROM links WHERE id='$link'");
                    $fileData = mysql_fetch_array($fileSql);
                    }
                    ?>

            <form action="doit.php?action=addFileToPlaylist&file=<?=$file;?>&folder=<?=$folder;?>&element=<?=$element;?>&link=<?=$link;?>" method="post" target="submitter">

                <div class="jqPopUp border-radius transparency" id="addPlaylist">
                    <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closePlaylist">X</a>
                    <header>
                        <?=$fileData[title];?>
                    </header>
                    <div class="jqContent">
                        <p>Please choose a playlist:</p>
                        <select name="playlistId">
                            <option value=""></option>
                            <?
                            $playListsSql = mysql_query("SELECT id, title FROM playlist WHERE user='$_SESSION[userid]'");
                            while($playListsData = mysql_fetch_array($playListsSql)){
                            ?>
                            <option value="<?=$playListsData[id];?>"><?=$playListsData[title];?></option>
                            <? } ?>
                        </select>
                        <p>Add to new Playlist:</p>
                        <input type="text" name="playlistName">
                    </div>
			        <footer>
			         	<span class="pull-left"><a class="btn" onclick="$('.jqClose').hide();">Back</a></span>
			         	<span class="pull-right"><input type="submit" value="add" id="playlistSubmit"></span>
			        </footer>
                </div> 
            </form>
            <script>
                $("#playlistSubmit").click(function () {
                $('#addPlaylist').slideUp();
                });
                $("#closePlaylist").click(function () {
                $('#addPlaylist').slideUp();
                });
            </script>
     <?}
     
     }else if($_GET[action] == "addPlaylist"){
         if(isset($_POST[submit])){
                        
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];
                    
             mysql_query("INSERT INTO playlist (user, title, privacy) VALUES('$user', '$_POST[title]', '$privacy')");   ?>
            <script>
            parent.$('#favTab_playList').load('doit.php?action=showUserPlaylists');
            </script>
            <?
         }
        ?>
        <form action="doit.php?action=addPlaylist" method="post" target="submitter">
        <div class="jqPopUp border-radius transparency" id="addPlaylist">
        	<a class="jqClose" id="closePlaylist">X</a>
            <header>
                Add a new Playlists
            </header>
            <div class="jqContent">
                <table width="100%">
                    <tr>
                        <td width="180">Title:</td>
                        <td><input type="text" name="title" style="width:300px;"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                                <?
                                showPrivacySettings();
                                ?>
                        </td>
                    </tr>
                </table>
            </div>
			<footer>
			 	<span class="pull-left"><a class="btn" onclick="$('.jqClose').hide();">Back</a></span>
			 	<span class="pull-right"><input type="submit" name="submit" value="add" id="playlistSubmit" class="btn btn-success"></span>
			</footer>
        </div>
        <script>
            $("#playlistSubmit").click(function () {
            $('#addPlaylist').slideUp();
            });
            $("#closePlaylist").click(function () {
            $('#addPlaylist').slideUp();
            });
        </script>
        </form>
     <?
     }else if($_GET[action] == "copyPlaylist"){
         $playList = save($_GET[playlist]);
         $playlistData = mysql_query("SELECT * FROM playlist WHERE id='$playList'");
         $playlistData = mysql_fetch_array($playlistData);
         
         if(isset($_POST[submit])){
                        
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];
					
                mysql_query("INSERT INTO playlist (user, title, privacy, links, files, youTube) VALUES('$user', '$_POST[title]', '$privacy', '$playlistData[links]', '$playlistData[files]', '$playlistData[youTube]')");
 				jsAlert("The playlist has been added");
                ?>
            <script>
            parent.$('#favTab_playList').load('doit.php?action=showUserPlaylists');
            </script>
            <?
             }
        ?>
        <form action="doit.php?action=copyPlaylist&playlist=<?=$playList;?>" method="post" target="submitter">
        <div class="jqPopUp border-radius transparency" id="addPlaylist">
            <a class="jqClose" id="closePlaylist">X</a>
            <header>
                Copy Playlist <?=$playlistData[title];?>
            </header>
            <div class="jqContent">
                <table width="100%">
                    <tr>
                        <td width="180">Title:</td>
                        <td><input type="text" name="title" style="width:300px;" value="<?=$playlistData[title];?>"></td>
                    </tr>
                    <tr>
                        <td valign="top">Privacy</td>
                        <td>
							<?
							showPrivacySettings("h//f");
							?>
                        </td>
                    </tr>
                </table>
            </div>
            <footer>
            	<span class="pull-right"><input type="submit" name="submit" value="add" class="btn btn-success" id="playlistSubmit"></span>
            </footer>
        </div>
        <script>
            $("#playlistSubmit").click(function () {
            $('#addPlaylist').slideUp();
            });
            $("#closePlaylist").click(function () {
            $('#addPlaylist').slideUp();
            });
        </script>
        </form>
     <?
     }
     else if($_GET[action] == "showUserPlaylists"){ ?>
                 
                    <h3 class="readerStartItem">
                        <img src="./gfx/icons/playlist.png" height="14">&nbsp;Your Playlists<span style="float: right;"><a href="javascript: popper('doit.php?action=addPlaylist');" class="btn"><img src="./gfx/icons/playlist.png" height="14">&nbsp;Add Playlist</a></span>
                    </h3>
                      <table class="border-top-radius border-box readerStartItem" cellspacing="0" style="border: 1px solid #c9c9c9; margin-top: -15px;">
                          <tr class="grayBar" height="35">
                              <td width="27">&nbsp;</td>
                              <td width="200">Title</td>
                              <td>Played</td>
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
                                <td width="150"><a href="javascript: popper('doit.php?action=showPlaylist&id=<?=$playListData[id];?>')"><?=$playListData[title]?></a></td>
                                <td><?=$playListData[played];?></td>
                            </tr>
                            <?PHP
                            } 
                            if(empty($i)){
                                echo"Add playlist to automaticly play mp3´s and Youtube Songs in a row.";
                            }
?>
                        </table><br><br>
     <?    
     }else if($_GET[action] == "showUserGroups"){
         ?>
                    <h3 class="readerStartItem">
                        <img src="./gfx/icons/group.png" height="14">&nbsp;Your Groups <a href="#" class="btn btn-info btn-small bsPopOver" data-toggle="popover" title="" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" id="popoooo"><i class="icon-info-sign"></i></a> <span style="float:right"><a href="javascript: popper('doit.php?action=addGroup');" class="btn"><img src="./gfx/icons/group.png" height="14">&nbsp;Add Group</a>  </span>
                    </h3>
                    <table class="border-top-radius border-box readerStartItem" cellspacing="0"  style="border: 1px solid #c9c9c9; margin-top: -15px;">
                       <tr class="grayBar" height="35">
                           <td width="27">&nbsp;</td>
                           <td width="300">&nbsp;name</td>
                           <td>members</td>
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
                                   <td><?=countGroupMembers($groupData[id]);?></td>
                               </tr>
                       <?}
                       if($i < 1){
                           echo"Your are in no group";
                       }
                       ?>
                     </table>
         <?
         
     }
     else if($_GET[action] == "requestGroup"){
         $time = time();
         $val = $_GET[val];
         mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$_GET[group]', 'user', '$_SESSION[userid]', '$time', '$_SESSION[userid]', '$val');");
     }
     else if($_GET[action] == "joinGroup"){
         mysql_query("UPDATE groupAttachments SET validated='1' WHERE id='$_GET[id]'");
         jsAlert("Joined :)"); 
         ?>
                <script>
                parent.$('#favTab_Group').load('doit.php?action=showUserGroups');
                </script>
         <?
     }else if($_GET[action] == "declineGroup"){
         
         mysql_query("DELETE FROM groupAttachments WHERE id='$_GET[id]'");
         jsAlert("Declined..");
     }else if($_GET[action] == "loadPersonalFileFrame"){
         //is used to load filelists into the reader home view
         $query = save($_GET[query]);
         switch($query){
             case allFiles:
                  
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
             break;
             case myFiles:
                  $userData = mysql_query("SELECT myFiles FROM user WHERE userid='$_SESSION[userid]'");
                  $userData = mysql_fetch_array($userData);
                  //show files
                  $fileQuery = "id='$userData[myFiles]'";
                  echo'<table width="100%">';
                  showFileList('', $fileQuery);
                  echo"</table>";
             break;
         }
         
     }else if($_GET[action] == "loadMiniBrowser"){
     	
		showMiniFileBrowser("$_GET[folder]", "$_GET[element]", "$_GET[level]", false);
     	
     }else if($_GET[action] == "addFolder"){
         if(proofLogin()){
         if($_POST[submit]) {
             
             
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];
                if(!empty($_POST[folder]) AND !empty($_POST[name])){
                createFolder($_POST[folder], $_POST[name], $user, $privacy);
				}
                
                
                $message="The folder has been added!";
                jsAlert($message);
                ?>
                <script>
                    parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$_POST[folder];?>&reload=1');
                </script>
                <?

                }
                $selectsql = mysql_query("SELECT id, name, privacy FROM folders WHERE id='$_GET[folder]'");
                $selectdata = mysql_fetch_array($selectsql);
                ?>
                <form action="./doit.php?action=addFolder" method="post" target="submitter"> 
                                <div class="jqPopUp border-radius transparency" id="addFolder">
                                <a class="jqClose" id="closeFolder">X</a>
                                <header>Create Folder</header>
                                <div class="jqContent">
                                    <table>
                                    <tr valign="top">
                                    <td valign="top">
                                    <table>
                                        <tr>
                                        <td>
                                        <table>
                                        <tr height="30">
                                            <td align="right" valign="top">Name:&nbsp;</td>
                                            <td><input type="text" name="name" style="width: 300px;"></td>
                                        </tr>
                                        <tr height="50">
                                            <td align="right">Folder:&nbsp;</td>
                                            <td><?=$selectdata[name];?>/<input type="hidden" name="folder" value="<?=$_GET[folder];?>"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <?
                                                showPrivacySettings($selectdata[privacy]);
                                                ?>
                                            </td>
                                        </tr>
                                        </table>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                    </tr>
                                    </table>
                                </div>
	                            <footer>
	                            	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	                            	<span class="pull-right"><input type="submit" value="Add" name="submit" id="submitFolder" class="btn btn-success"></span>
	                            </footer>
                                </div>
                </form>
                <script>
                    $("#submitFolder").click(function () {
                    $('#addFolder').slideUp();
                    });
                    $(".jqClose").click(function(){
                        $('.jqPopUp').slideUp();
                    });
                </script>
         
<?}
     }else if($_GET[action] == "addElement"){
         
         if(proofLogin()){
            $selectsql = mysql_query("SELECT * FROM folders WHERE id='$_GET[folder]'");
            $selectdata = mysql_fetch_array($selectsql);
            if($_POST[submit]) {
    
                 
    
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];

        $time = time();
        $title = save($_POST[elementName]);
        $name = save($_POST[name]);
        mysql_query("INSERT INTO `elements` (`title`, `folder`, `creator`, `name`, `year`, `type`, `author`, `timestamp`, `privacy`) VALUES('$title', '$_POST[folder]', '$_POST[creator]', '$name', '$_POST[year]', '$_POST[type]', '$user', '$time', '$privacy');");

        //add feed
        $elementId = mysql_insert_id();
        $feed = "has created an element";
        createFeed($user, $feed, "", "showThumb", $privacy, "element", $elementId);
    
        if($_POST[type] == "image"){
            $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$_POST[folder]'");
            $fileFolderData = mysql_fetch_array($filefolderSQL);
            //here could be a fail but Its workin right o0
            $folderpath = "$folderData[path]";
            mkdir("./upload$folderpath/thumbs");
        }
        ?>
        <script>
            parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$_POST[folder];?>&reload=1');
        </script>
        <?
        echo jsAlert("you just added an element :)");
        } else {
        ?>
       <form action="./doit.php?action=addElement" method="post" target="submitter">  
        <div class="jqPopUp border-radius transparency" id="addElement" style="">
            <a class="jqClose" id="closeElement">X</a>
            <header>
                Add an Element
            </header>
            <div class="jqContent">
            <table>
                <tr>
                    <td align="right">Name:&nbsp;</td>
                    <td><input type="text" name="elementName" style="width:300px;"><input type="hidden" name="folder" value="<?=$_GET[folder];?>"></td>
                </tr>
                <tr>
                    <td align="right">Type:&nbsp;</td>
                    <td>
                        <select name="type">
                            <option value="document">document</option>
                            <option value="link">link</option>
                            <option value="audio">audio</option>
                            <option value="video">video</option>
                            <option value="image">image</option>
                            <option value="app">execute</option>
                            <option value="other">other<option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td align="right">Author:&nbsp;</td>
                    <td><input type="text" name="creator"></td>
                </tr>
                
                <tr>
                    <td align="right">Title:&nbsp;</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td align="right">Year:&nbsp;</td>
                    <td><input type="text" name="year"></td>
                </tr>
                <tr>
                    <td align="right">Folder:&nbsp;</td>
                    <td><?=$selectdata[name];?></td>
                </tr>
                    <tr>
                        <td colspan="2">
                            <?
                                    showPrivacySettings($selectdata[privacy]);
                            ?>
                            
                        </td>
                    </tr>
            </table>
            </div>
	        <footer>
	        	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	        	<span class="pull-right"><input type="submit" name="submit" value="add" class="btn btn-success" id="elementSubmit"><input type="hidden" name="step1"></span>
	        </footer>
        </div>
        </form>
        <script>
            $("#elementSubmit").click(function () {
            $('#addElement').slideUp();
            });
            $("#closeElement").click(function () {
            $('#addElement').slideUp();
            });
        </script>


<?
        }}    
     }else if($_GET[action] == "addInternLink"){
     	if(proofLogin()){
     		
     		if(isset($_POST[submit])){
     			
				if(createInternLink($_POST[parentType], $_POST[parentId], $_POST[type], $_POST[typeId], $_POST[title])){
					jsAlert("The shortcut has been added :)");
				}
                                
                                //
                                if($_POST[parentType] == "folder"){
                                ?>
                                <script>
                                    parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$_POST[parentId];?>&reload=1');
                                </script>
                                <?
                                }else if($_POST[parentType] == "element"){
                                    $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysq_real_escape_string("$_POST[parentId]")."'"));
                                    ?>
                                    <script>
                                        parent.addAjaxContentToTab('<?=substr($parentData[title], 0, 10);?>', 'modules/filesystem/showElement.php?element=<?=$_POST[parentId];?>&reload=1');
                                    </script>
                                    <?
                                }
     			
     		}else{
												
                    if(!empty($_GET[parentFolder])){
                            $parentData = mysql_fetch_array(mysql_query("SELECT name FROM folders WHERE id='".mysql_real_escape_string($_GET[parentFolder])."'"));
                            $titleText = "folder $parentData[name]";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentType\" value=\"folder\">";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentId\" value=\"$_GET[parentFolder]\">";
                    }
                    if(!empty($_GET[parentElement])){
                            $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysql_real_escape_string($_GET[parentElement])."'"));
                            $titleText = "element $parentData[title]";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentType\" value=\"element\">";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentId\" value=\"$_GET[parentElement]\">";
                    }
     			?>
                <form action="./doit.php?action=addInternLink" method="post" target="submitter"> 
                                <div class="jqPopUp border-radius transparency" id="addInternLink">
                                <a class="jqClose" id="closeFolder">X</a>
                                <header>Create intern Link in <?=$titleText;?></header>
                                <div class="jqContent">
                                    <table>
                                    <tr valign="top">
                                    <td valign="top">
                                    <table>
                                        <tr>
                                        <td>
                                        <table>
                                        <tr height="30">
                                            <td align="right" valign="top">Title:&nbsp;</td>
                                            <td><input type="text" name="title" style="width: 300px;"></td>
                                        </tr>
										<tr>
											<td align="right" valign="top">Choose Item:</td>
											<td>
												
												<?
												echo "$hiddenInput";
												
												showMiniFileBrowser("1");
												
												?>
												
											</td>
											
										</tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        </table>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                    </tr>
                                    </table>
                                </div>
						        <footer>
						        	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
						        	<span class="pull-right"><input type="submit" value="Add" name="submit" id="submitInternLink" class="btn btn-success"></span>
						        </footer>
                                </div>
                </form>
                <script>
                    $("#submitInternLink").click(function () {
                    $('#addInternLink').slideUp();
                    });
                    $(".jqClose").click(function(){
                        $('.jqPopUp').slideUp();
                    });
                </script>
         
			<?
			
     	}
     	}
     }else if($_GET[action] == "addLink"){
         if(proofLogin()){
                  if($_POST[submit]) {
             
             
    
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];

                $time = time();
                mysql_query("INSERT INTO `links` (`folder`, `type`, `title`, `link`, `privacy`, `author`, `timestamp`)
                 VALUES ( '".mysql_real_escape_string($_POST[folder])."',
                  '".mysql_real_escape_string($_POST[type])."',
                   '".mysql_real_escape_string($_POST[title])."',
                    '".mysql_real_escape_string($_POST[link])."',
                     '$privacy', '$user', '$time');");
                $message="The Link has been added!";
                ?>
                <script>
                    parent.addAjaxContentToTab('<?=$_POST[tabTitle];?>', 'modules/filesystem/showElement.php?element=<?=$_POST[folder];?>&reload=1');
                </script>
                <?
                $feedText = "has created the link $_POST[title] in the folder";
                    $feedLink1 = mysql_insert_id();
                    $feedLink2 = "$_POST[folder]";
                    addFeed($_SESSION[userid], $feedText, folderAdd, $feedLink1, $feedLink2);
                }
                $selectsql = mysql_query("SELECT title FROM elements WHERE id='$_GET[element]'");
                $selectdata = mysql_fetch_array($selectsql);
                $title = $selectdata[title];
                $title10 = substr("$title", 0, 10);
                ?>

                <form action="./doit.php?action=addLink" method="post" target="submitter">
                <div class="jqPopUp border-radius transparency" id="addLink">
                <a class="jqClose" id="closeLink">X</a>
                <header>
                Add Link
                </header>
                <div class="jqContent">
                        <table width="100%">
                            <tr valign="top" height="25">
                                <td align="center">&nbsp;</td>
                            </tr>
                            <tr valign="top">
                                <td valign="top">
                                    <table width="100%">
                                        <input type="hidden" name="tabTitle" value="<?=$title10;?>">
                                        <tr>
                                            <td align="center"><input type="text" name="link" placeholder="http://transpareny-everywhere.com/wtf" size="40"></td>
                                        </tr>
                                        <tr>
                                        <td>
                                        <table>
                                        <tr height="50">
                                            <td>&nbsp;<?=$message;?></td>
                                        </tr>
                                        <tr height="45">
                                            <td>Title</td>
                                            <td><input type="text" name="title"></td>
                                        </tr>
                                        <tr>
                                            <td>Type:</td>
                                            <td>
                                                <select name="type">
                                                    <option value="link">Standard Link<option>
                                                    <option value="RSS">RSS</option>
                                                    <option value="youTube">Youtube</option>
                                                    <option value="soundcloud">Soundcloud</option>
                                                    <option value="file">File</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr height="65">
                                            <td>Element</td>
                                            <td><?=$selectdata[title];?>/<input type="hidden" name="folder" value="<?=$_GET[element];?>"</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <?
                                                    showPrivacySettings();
                                                ?>
                                            </td>
                                        </tr>
                                        </table>   
                                        </td>
                                        </tr>
                                        <tr>
                                        <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                </div>
		        <footer>
		        	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
		        	<span class="pull-right"><input type="submit" name="submit" value="add" class="btn btn-success" id="submitLink"></span>
		        </footer>
                </div>
                </form>
        <script>
            $("#submitLink").click(function () {
            $('#addLink').slideUp();
            });
            $("#closeLink").click(function () {
            $('#addLink').slideUp();
            });
        </script>

        <? 
         }
     }else if($_GET[action] == "changeBackgroundImage"){
        if(proofLogin()){
        if($_GET[type] == "file"){
            $fileSql = mysql_query("SELECT * FROM files WHERE id='$_GET[id]'");
            $fileData = mysql_fetch_array($fileSql);
                $documentElementSQL = mysql_query("SELECT id, folder FROM elements WHERE id='$documentData[folder]'");
                $documentElementData = mysql_fetch_array($documentElementSQL);
                $documentFolderSQL = mysql_query("SELECT id, path FROM folders WHERE id='$documentElementData[folder]'");
                $documentFolderData = mysql_fetch_array($documentFolderSQL);
                $folderPath = urldecode($documentFolderData[path]);
                $img = "upload$folderPath/$fileData[title]";
                    }
        else if($type == "link"){
                
        }
        mysql_query("UPDATE user SET backgroundImg='$img' WHERE userid='$_SESSION[userid]'");
     }}else if($_GET[action] == "writeMessage"){
        if(proofLogin()){
        $user = save($_GET[buddy]);
        if(isset($_POST[feed])) {
        if(!isset($postCheck)) {
        $time = time();
        $message = $_POST[feed];
        $crypt = "0";
        
    mysql_query("INSERT INTO messages (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('$_SESSION[userid]', '$user', '$time', '$message', '0', '$crypt');");
    $postCheck = 1;
    jsAlert("Worked :)");
    }
    }         
         ?><form action="./doit.php?action=writeMessage&buddy=<?=$user;?>" method="post" target="submitter">
        <div class="jqPopUp border-radius transparency" id="writeMessage">
            <a class="jqClose" id="closeMessage">X</a>
            <header>
                Write Message
            </header>
            <div class="jqContent">
            <table align="center" width="100%">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>To:</td>
                    <td>
         <?
         if(isset($_GET[buddy])){
             $userSql = mysql_query("SELECT username FROM user WHERE userid='$_GET[buddy]'");
             $userData = mysql_fetch_array($userSql);
             echo $userData[username];
         }else{?>
             <input type="text" name="reveiver">
         <? } ?>
                        
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top">Message:</td>
                    <td>
                        <textarea name="feed" style="width:90%; height: 150px; overflow-y:hidden; margin-top:5px;" onkeyup="autoGrowField(this ,300)" cols="30" rows="10"></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <footer>
        	<span class="pull-left"><a class="btn" onclick="$('#writeMessage').slideUp();">back</a></span>
        	<span class="pull-right"><input type="submit" name="submit" value="send" id="messageSubmit" class="btn btn-success"></span>
        </footer>
        </div>
        </form>
        <script>
            $("#messageSubmit").click(function () {
            $('#writeMessage').slideUp();
            });
            $("#closeMessage").click(function () {
            $('#writeMessage').slideUp();
            });
        </script>
        <?
        }
     }
     

//GROUPS
//GROUPS
//GROUPS
 
     
     else if($_GET[action] == "groupAdmin"){
         
         $group = $_GET[id];
         if($_POST[submit]){
             mysql_query("UPDATE groups SET public='$_POST[privacy]', description='$_POST[description]', membersInvite='$_POST[membersInvite]' WHERE id='$group'");
         jsAlert("Saved :)");
         }
         $groupSql = mysql_query("SELECT * FROM groups WHERE id='$group'");
         $groupData = mysql_fetch_array($groupSql);
         if($groupData[membersInvite] == "1"){
             $membersInvite = "checked=\"checked\"";
         }
         if($groupData["public"] == "1"){
             $public = "checked";
         }else{
             $unpublic = "checked";
         }
         ?>
        <div class="jqPopUp border-radius transparency" id="groupAdmin">
            <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closeAdmin">X</a>
            <header>
            Admin - <?=$groupData[title];?>
            </header>
            <div class="jqContent">
                <form action="doit.php?action=groupAdmin&id=<?=$group;?>" method="post" target="submitter">
                    <table height="200" width="400" style="margin: 20px; line-height:22pt;">
                        <tr>
                            <td style="vertical-align: top;" align="right">Description:</td>
                            <td width="20"></td>
                            <td><textarea name="description" cols="30" rows="3"><?=$groupData[description];?></textarea></td>
                        </tr>
                        <tr>
                            <td align="right">Privacy:</td>
                            <td></td>
                            <td align="center"><input type="radio" name="privacy" value="1" <?=$public;?>>Private&nbsp;&nbsp;<input type="radio" name="privacy" value="0" <?=$unpublic;?>>Public</td>
                        </tr>
                        <tr>
                            <td align="right"></td>
                            <td></td>
                            <td><input type="checkBox" name="membersInvite" value="1" <?=$membersInvite;?>> Allow members to invite users</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;" align="right">Users:</td>
                            <td></td>
                            <td>
                                    <table cellspacing="0" style="font-size: 9px; font-color: #000; color: #000;" width="200"  height="50" style="overflow: scroll;">
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
                                        <tr bgcolor="#<?=$color;?>">
                                            <td><?=showUserPicture($groupUseData, 15);?></td>
                                            <td style="font-color: #000; color: #000;"><?=$groupUserData[username];?></td>
                                            <td style="font-color: #000; color: #000;">Admin</td>
                                            <td style="font-color: #000; color: #000;">Delete</td>
                                        </tr>
                                    <? } ?>
                                    </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" id="adminSubmit" name="submit" value="save" class="btn"></form>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script>
            $("#adminSubmit").click(function () {
            $('#groupAdmin').slideUp();
            });
            $("#closeAdmin").click(function () {
            $('#groupAdmin').slideUp();
            });
        </script>
        <?
     }else if($_GET[action] == "groupInviteUsers"){
         if(proofLogin()){
         $group = $_GET[id];
         $time = time();
         if($_POST[submit]){
         $userlist = $_POST[users];
         if(isset($userlist)){
         foreach ($userlist as &$value) {
         mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$group', 'user', '$value', '$time', '$_SESSION[userid]');");
         }}jsAlert("worked:)");
         }
         $groupSql = mysql_query("SELECT * FROM groups WHERE id='$group'");
         $groupData = mysql_fetch_array($groupSql);
		 
		 
		 //query to slow needs to be replaced
		 $userAttachmentsSQL = mysql_query("SELECT itemId FROM groupAttachments WHERE `group`='".mysql_real_escape_string($group)."' AND `item`='user' AND `validated`='1'");
		 while($userAttachmentsData = mysql_fetch_assoc($userAttachmentsSQL)){
		 	$users[] = $userAttachmentsData[itemId];
		 }
		 ?>
       <div class="jqPopUp border-radius transparency" id="groupInvite">
            <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closeInvite">X</a>
            <header>
            Invite Users - <?=$groupData[title];?>
            </header>
            <div class="jqContent">
            <table height="250"><form action="doit.php?action=groupInviteUsers&id=<?=$group;?>" method="post" target="submitter">
                <tr>
                    <td valign="top">Choose Friends:&nbsp;</td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <div style="width: 300px; height: 200px; overflow: auto; font-color: #000;">
                                        <ul>
                        <?
                        $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' && request='0'");
                        while($buddylistData = mysql_fetch_array($buddylistSql)) {
                        	if(!in_array($buddylistData[buddy], $users)){
                            
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
			                            	<li style="line-height: 37px;">
			                            		&nbsp;<input type="checkbox" name="users[]" value="<?=$blUserData[userid];?>">&nbsp;<?=showUserPicture($blUserData[userid] , 25);?>&nbsp;<?=$blUserData[username];?>
			                            	</li>
                            <?}}?> 
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="invite" class="btn"></td></form>
                </tr>
            </table>
            </div>
        </div>
        <script>
            $("#inviteSubmit").click(function () {
            $('#groupInvite').slideUp();
            });
            $("#closeInvite").click(function () {
            $('#groupInvite').slideUp();
            });
        </script>
        <?}
     }else if($_GET[action] == "groupLeave"){
         $group = "$_GET[id]";
         mysql_query("DELETE FROM groupAttachments WHERE `group`='$group' AND `item`='user' AND `itemId`='$_SESSION[userid]'");
         jsAlert("You left the group");   
     }
     
     
//CHAT - IM
//CHAT - IM
//CHAT - IM
     
     
     else if($_GET[action] == "chatSendMessage"){
        if(proofLogin()){
        $buddy = "$_GET[buddy]";
             if(!isset($postCheck)) {
                if($_POST[cryption] == "true"){
                    $crypt = "1";
                }else{
                    $crypt = "0";
                } 
				
				$message = addslashes($_POST[message]);
                mysql_query("INSERT INTO messages (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('$_SESSION[userid]', '$buddy', '$time', '$message', '0', '$crypt');");
                $postCheck = 1;?>
        <script>
            parent.$('.chatInput').val('');
            parent.$('#test_<?=str_replace(" ","_",$_GET[buddyname]);?>').load('modules/chat/chatt.php?buddy=<?=urlencode($_GET[buddyname]);?>&initter=1');
        
        </script>
        <?
                }
        } 
        }else if($_GET[action] == "updateMessageStatus"){
             
        //updates if the message was seen, after the receiver clicked the input in the textarea

            
            
        $user = $_SESSION[userid];
        $buddy = $_GET[buddy];
	        $chatSQL = mysql_query("SELECT * FROM messages WHERE (sender='$user' && receiver='$buddy') OR (sender='$buddy' && receiver='$userid') AND (read='0' OR seen='0') ORDER BY timestamp DESC LIMIT 0, 30");
	        while($chatData = mysql_fetch_array($chatSQL)) {
	        jsAlert("lol");
	            
	            if($chatData[receiver] == $_SESSION[userid] && $chatData[read] == "0"){
	            mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
	            }
	            if($chatData[sender] == $_SESSION[userid] && $chatData[seen] == "0"){
	            mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
	            }
	            
	        }
        }else if($_GET[action] == "chatLoadMore"){
            
            $userid = $_SESSION[userid];
            $buddyName = save("$_GET[buddy]");
            $buddy = usernameToUserid($buddyName);
            
            //when chatframe is loaded $limit = 0, when load more is clicked the first time $limit=1 etc.
            //it always adds thirty messages
            $limit = save("$_GET[limit]");
            $newLimit = $limit+1;
            //convert $limit to a mysql LIMIT conform string 
            $limit = $limit*30;
            $limit = ($limit).",".($limit+30);
            
            
            
            $chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddy' OR sender='$buddy' && receiver='$userid' ORDER BY timestamp DESC LIMIT $limit");
                while($chatData = mysql_fetch_array($chatSQL)) {

                    if($chatData[receiver] == $_SESSION[userid] && $chatData[read] == "0"){
                    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
                    }
                    if($chatData[sender] == $_SESSION[userid] && $chatData[seen] == "0"){
                    mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
                    }

                    $sender = $chatData[sender];
                    $whileid = $_SESSION[userid];
                    if($sender == $whileid){
                    $authorid =  $userData[userid];
                    $authorName = $userData[username];
                    $authorImage = $userData[userPicture];
                    $reverse = NULL;
                    $css = 'messageOut'; 
                    $css = 'margin-right: 15px; margin-left: 5px;';
                    } else {

                    $authorid =  $buddyData[userid];
                    $authorName = $buddyData[username];
                    $authorImage = $buddyData[userPicture]; 
                    $css = 'margin-left: 15px; margin-right: 5px;';
                    $reverse = "1";   
                    }
                    if($chatData[crypt] == "1"){
                        if(isset($_SESSION[$intWindows])){
                        $message = universeDecode("$chatData[text]", "$_SESSION[$intWindows]");
                    }else{
                        $message = "[s]crypted[/s]";
                    }} else{
                        $message = $chatData[text];
                    }
                    $message = universeText($message);
                    ?>
                              <div class="box-shadow space-top border-radius chatText" style="<?=$css;?> padding: 10px;"><span style="position: absolute; margin-top: -20px; color: #c0c0c0;"><?=showUserPicture($authorid, "15");?><?=$authorName;?></span><?=$message;?></div>
                <? }
                echo "<div onclick=\"chatLoadMore('$buddyName', '$newLimit'); $(this).hide();\">...load more</div>";
            
                
        }else if($_GET[action] == "createFeed"){
            
            
            if(!empty($_POST[feedInput]) || !empty($_POST[feed1])){
            
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                    $user = $_SESSION[userid];
                    $feed = "$_POST[feedInput]$_POST[feed1]";
                    
                    //create feed
                    $id = createFeed("$_SESSION[userid]", $feed, "", "feed", "p");
                    // $Groups is privacy right now and not used => "p" is used instead
            ?>

            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
            <script>
            $('.addFeed', parent.document).load('../../doit.php?action=showSingleFeed&feedId=<?=$id;?>'); 
            $('#feedInput', parent.document).val('');
            </script>
            <?
                }
            
        }
        else if($_GET[action] == "showSingleFeed"){
        $feedId = save("$_GET[feedId]");
        echo'<div id="addFeed"></div>';
        showFeedNew("singleEntry", "", "", "$feedId");
        
        
        }else if($_GET[action] == "reloadMainFeed"){
            
            
                showFeedNew("friends", "$_SESSION[userid]");
                echo "<div onclick=\"feedLoadMore('.feedMain' ,'friends', 'NULL', '1'); feedLoadMore('friends','1'); $(this).hide();\">...load more</div>";
                
            
        }else if($_GET[action] == "feedLoadMore"){
            $type = save("$_GET[type]");
            $user = save($_GET[user]);
            $limit = save($_GET[limit]);
            
            //when chatframe is loaded $limit = 0, when load more is clicked the first time $limit=1 etc.
            //it always adds thirty messages
            $limit = save("$_GET[limit]");
            $newLimit = $limit+1;
            //convert $limit to a mysql LIMIT conform string 
            $limit = $limit*30;
            $limit = ($limit).",".($limit+30);
            showFeedNew("$type", "$user","$limit");
            
            
        }else if($_GET[action] == "showYoutube"){?>
         <?
         if(isset($_GET[start])){?>
                    <div id="playListReaderTab" style="background: #000000;">
                    <?}?>
                    <center>
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
            <script type="text/javascript" src="./inc/swfObj/swfobject.js"></script>    
            <div id="ytapiplayer" style="position: absolute; top:30px; left: 0px; right: 0px; bottom: 0px;">
                You need Flash player 8+ and JavaScript enabled to view this video.
            </div>

            <script type="text/javascript">
                var width = document.width;
                var height = document.height;
                var params = { allowScriptAccess: "always" };
                var atts = { id: "myytplayer" };
                swfobject.embedSWF("http://www.youtube.com/v/<?=$_GET[id];?>?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1",
                                "ytapiplayer", "1000", "600", "8", null, null, params, atts);


            function onYouTubePlayerReady(playerId) {
            ytplayer = document.getElementById("myytplayer");
            ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
            }
            <?
            if(!empty($_GET[playList])){
                $row = ($_GET[row]+1);?>
            function onytplayerStateChange(newState) {
            if(newState == "0"){

                parent.nextPlaylistItem('<?=$_GET[playList];?>','<?=$row;?>');
            }
            }
            <? } ?>

            var youTubeObj = {
                                'width' : width,
                                'height' : height
                                    }
                                $("#ytapiplayer").css(youTubeObj);
            </script>
                    </center>
        <?
        if(isset($_GET[playList])){
            //load new next and start button
            ?>
                        <script> 
                        parent.$('#togglePlayListTitle_<?=$_GET[playList];?>').text('<?=youTubeIdToTitle($_GET[id]);?>');
                        parent.$('#togglePlayList_<?=$_GET[playList];?>').html( function(){
                            
               
                            
                            var playListToggleLink = '<a href="#" onclick="nextPlaylistItem(\'<?=$_GET[playList];?>\',\'<?=($row-2);?>\');" title="last Track" class="btn btn-mini"><i class=\"icon-backward\"></i></a>&nbsp;<a href="#" onclick="nextPlaylistItem(\'<?=$_GET[playList];?>\',\'<?=($row);?>\');" title="next Track" class="btn btn-mini"><i class=\"icon-forward\"></i></a>';

                            $(this).html(playListToggleLink);
                        });
                        </script>
            <?
            
            }
        if(isset($_GET[start])){?>
                    </div>
         <?}
         
     }else if($_GET[action] == "addYouTubeItemToPlaylistVeryLongName"){
         $playlist = save($_POST[playlistId]);
         $vId = save($_GET[vId]);
         
         $UpdateStringSql = mysql_query("SELECT id, youTube FROM playlist WHERE id='$playlist'");
         $UpdateData = mysql_fetch_array($UpdateStringSql);
   
            if(!empty($vId)){
             $items= "$vId;$UpdateData[youTube]";
             if(mysql_query("UPDATE playlist SET youTube='$items' WHERE id='$playlist'")){
                 jsAlert("worked ;)");
             }
            }
     }else if($_GET[action] == "showSingleImage"){
         $element = "$_GET[element]";
         $elementName = "$_GET[elementName]";
         $file = "$_GET[file]";
         //maybe dont works with strange letters etc.
         $title = "$_GET[title]";
        $path = getFilePath($file);
        $path = "$path/$title";
        ?>
        <div style="position: absolute; top: 57px; right: 0px; bottom: 100px; left: 0px; overflow: auto;" id="<?=$elementName;?>">
            <img src="./upload/<?=$path;?>" width="100%">
        </div>
    <? }else if($_GET[action] == "addGroup"){
        if(proofLogin()){
            
        if(isset($_POST[submit])){
            $description = $_POST[description];
            $title = $_POST[title];
            $privacy = $_POST[privacy];
            $userlist = $_POST[users];

            //check if nessecary informations are given
            if((isset($description)) && (isset($title)) && (isset($privacy))){
            //insert group into db    
            mysql_query("INSERT INTO `groups` (`title`, `description`, `public`, `admin`) VALUES ('$title', '$description', '$privacy', '$userid');");
            $groupId = mysql_insert_id();

                //add users to group
                if(isset($userlist)){
                foreach ($userlist as &$value) {

                mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$groupId', 'user', '$value', '$time', '$userid');");


                }}
                
                $groupFolder = createFolder("3", $groupId, $userid, "$groupId//$groupId");
				$groupElement = createElement($groupFolder, $title, "other", $userid,  "$groupId//$groupId");
                mysql_query("UPDATE `groups` SET `homeFolder`='$groupFolder', `homeElement`='$groupElement' WHERE id='$groupId'");

            //add user which added group to group and validate
            mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$groupId', 'user', '$_SESSION[userid]', '$time', '$_SESSION[userid]', '1');");
            jsAlert("worked:)");  ?>
                <script>
                parent.$('#favTab_Group').load('doit.php?action=showUserGroups');
                </script>
                <?
            }else{
                jsAlert("please fill out everything");
            }

        }
    ?>
    
         <div class="jqPopUp border-radius transparency" id="addGroup" style="width: 600px; height: 400px;">
            <a class="jqClose" id="closeGroup">X</a>
         <header>
             Add Group
         </header>
         <form action="doit.php?action=addGroup" method="post" target="submitter">
         <div class="jqContent">
	         <table>
	             <tr>
	                 <td>&nbsp;</td>
	             </tr>
	             <tr>
	                 <td width="150" align="right">Groupname:&nbsp;</td>
	                 <td><input type="text" name="title" style="width: 400px;"></td>
	             </tr>
	             <tr>
	                 <td>&nbsp;</td>
	             </tr>
	             <tr>
	                 <td align="right">Type:&nbsp;</td>
	                 <td><input type="radio" name="privacy" value="0" style="margin-left:30px;">Public&nbsp;&nbsp;<input type="radio" name="privacy" value="1">Private</td>
	             </tr>
	             <tr>
	                 <td>&nbsp;</td>
	             </tr>
	             <tr>
	                 <td style="vertical-align:top;" align="right">Description:&nbsp;</td>
	                 <td><textarea name="description" style="width: 400px; height: 50px;"></textarea></td>
	             </tr>
	             <tr>
	                 <td>&nbsp;</td>
	             </tr>
	             <tr>
	                 <td valign="top">Invite Buddies:</td>
	                 <td>
	                     <div style="width: 400px; height: 200px; overflow: auto;" cellspacing="0">
	                         <table cellspacing="0" cellspacing="0" width="100%">
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
	                $bgcolor="000000";
	
	            } else {    
	                $bgcolor="0f0f0f";
	            }
	            $i++;
	            ?>
	                             <tr bgcolor="#<?=$bgcolor;?>" width="399" height="30">
	                                 <td width="20"><input type="checkbox" name="users[]" value="<?=$blUserData[userid];?>"></td>
	                                 <td width="40"><?=showUserPicture($blUserData[userid], 20);?></td>
	                                 <td><?=$blUserData[username];?></td>
	                             </tr>
	            <?}?> 
	                         </table>
	                     </div>
	                 </td>
	             </tr>
	         </table>
         </div>
         <footer>
         	<span class="pull-left"><a class="btn" onclick="$('.jqClose').hide();">Back</a></span>
         	<span class="pull-right"><input type="submit" value="add" name="submit" id="groupSubmit" class="btn"></span>
         </footer>
         </form>
     </div>
<script>
    $("#groupSubmit").click(function () {
    $('#addGroup').slideUp();
    });
    $("#closeGroup").click(function () {
    $('#addGroup').slideUp();
    });
</script>
     
     <?
    }}else if($_GET[action] == "showSingleComment"){
        if($_GET[type] == "feed"){
            showFeedComments($_GET[itemid]);
        }else{
            showComments($_GET[type], $_GET[itemid]);
        }
    }else if($_GET[action] == "showStartMessage"){
            if(empty($_GET[step])){ ?>
            <script>
                    $(".fenster").hide("slow");
            </script>
            <div class="blueModal border-radius container">
            	<div>
            		<h2>Thank you for joining the universe OS</h2>
	                <p style="margin-top: 20px;">
	                    We try to give you the experience of an operatingsystem, without the disadvantage that its bound to a singlecomputer. <br>You joined this project at a very early state, so please excuse us if you trap over some errors.<br>
	                </p>
	                <h3>We want to tell you more about the Universe within the next three steps.</h3>
	            </div>
	            <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=1&noJq=true'); return false" class="btn btn-primary pull-right">Next</a>
	            </footer>
            </div>
            <?
            }else if($_GET[step] == "1"){?>
            
            <div class="blueModal border-radius container">
            	<div>
	            	<h2>
	                    Your Desktop
	                </h2>
	                <span>
	                    <h3>Apps</h3><br>
	                    Apps are the most important component of the Universe. They are little programms like the reader, the feed or the buddylist which can be opened with the "Start" button in your Dock.
	                </span>
	                <span>
	                    <h3>The Dock</h3><br>
	                    The Dock conatains everything you always need to see.<br>Click on Start to see all your Apps.
	                </span>
	                <span>
	                    <h3>The Search</h3><br>
	                    Look for your friends, your favourite writer, a song on Spotify or your favourite Youtube movie.<br>Just type what you are looking for!
	                </span>
	                <span>
	                    <h3>Your Userbutton</h3><br>
	                    Your Userbutton shows whats new. It shows the number of news (like buddyrequests or messages) on your user picture
	                </span>
	                </div>
	                <footer>
	                 	<a href="#" onclick="popper('doit.php?action=showStartMessage'); return false" class="btn pull-left">Back</a>
	                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=2'); return false" class="btn btn-primary pull-right">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>
	                </footer>
	               </div>
            
            <?}else if($_GET[step] == "2"){?>
            <div class="blueModal border-radius">
            	<div>
                <h2>
                    The Filesystem
                </h2>
                <p>
                    <b>Folders</b>
                    Like on your own computer you can create folders within the filesystem
                </p>
                <p>
                    <b>Elements</b>
                    Elements contain files and links which belong together. They are listed in the filesystem like folders.<br><i><b>For example</b> you could create the image-element "My Nice Holiday In Somalia" and upload all your holiday pictures in it.</i>
                </p>
                <p>
                    <b>Create Something Big</b>
                    If you want to, everyone can see, download, edit and rate your files, elements and folders.<br>So use the intelligence of the swarm!
                </p>
                <p>
                    <b>Privacy</b>
                    When you add a folder, an element, a file or a link you always have to choose the privacy. Eitheir you choose that everyone can see it, that only particular groups, you are member of, or just you can see it.
                </p>
                </div>
                <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=1'); return false" class="btn pull-left">Back</a>
                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=3&noJq=true'); return false" class="btn btn-primary pull-right" style="">Next<a>
                </footer>
            </div>  
            <?}else if($_GET[step] == "3"){
			?>
            <div class="blueModal border-radius">
            	<div>
                <h2>
                    Buddylist & Chat
                </h2>
                <p>
                   will be added
                </p>
               </div>
                <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=2'); return false" class="btn pull-left">Back</a>
                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=4&noJq=true'); return false" class="btn btn-primary pull-right" style="">Next<a>
                </footer>
            </div>
            <?}else if($_GET[step] == "4"){
                mysql_query("UPDATE user SET startLink='' WHERE userid='$_SESSION[userid]'");?>
            <script>
                $("#finalStep").click(function(){
                    $(".blueModal").hide("slow", function(){
                        
                        $(".fenster").show("slow", function(){
                            initDraggable();
                        });
                        
                    });
                });
            </script>
            <div class="blueModal border-radius">
            	<div>
                <h2>
                    Update your profile
                </h2>
                <div>
                	<?
                    $AccSetSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
                    $AccSetData = mysql_fetch_array($AccSetSql);
                    if($AccSetData[birthdate]){
                    $birth_day = date("d", $AccSetData[birthdate]);
                    $birth_month = date("m", $AccSetData[birthdate]);
                    $birth_year = date("Y", $AccSetData[birthdate]);
                    }
                 ?>
		            <form action="modules/settings/index.php" method="post" target="submitter">
		                <div class="controls">
		                    
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
		                            <input type="submit" id="finalStep" name="AccSetSubmit" value="enter the universe" class="btn btn-info pull-right">
		                            <a href="#" class="btn pull-left" onclick="javascript: popper('doit.php?action=showStartMessage&step=3'); return false">back</a>
		                        </span>
		                    </div>
		                </div>
		            </form>
                </div>
               </div>
            </div>
            <?}
        }else if($_GET[action] == "showSingleRssFeed"){
            $rssSql = mysql_query("SELECT link FROM links WHERE id='".mysql_real_escape_string($_GET[id])."'");
            $rssData = mysql_fetch_array($rssSql);
            
            echo"<div style=\"padding: 20px;\">";
            echo getRssfeed("$rssData[link]","$linkData[title]","auto",10,3);
            echo"</div>";
        }else if($_GET[action] == "deleteFromPersonals"){
            $personalSql = mysql_query("SELECT owner FROM personalEvents WHERE id='".mysql_real_escape_string($_GET[id])."'");
            $personalData = mysql_fetch_array($personalSql);
            if($personalData[owner] == $_SESSION[userid]){
                mysql_query("DELETE FROM personalEvents WHERE id='".mysql_real_escape_string($_GET[id])."'");
            }
        }else if($_GET[action] == "deleteFromPlaylist"){
             //deletes a single file, link or youtube icon from a playlist
             $type = save($_GET[type]);
             $itemId = $_GET[itemId];
             $playlist = save($_GET[playlist]);
             jsAlert("$type-$itemId-$playlist");
             $checkPlaylistSql = mysql_query("SELECT * FROM playlist WHERE id='$playlist'");
             $checkPlaylistData = mysql_fetch_array($checkPlaylistSql);
             if($checkPlaylistData[user] == "$_SESSION[userid]"){
                 if($type == "file"){
                     $files = explode(";", $checkPlaylistData[files]);
                     foreach($files as $file){
                         if($file == $itemId){
                         }else{
                             $newFiles[] = $file; 
                         }
                         
                     }
                         $saveFiles = implode(";", $newFiles);
                         mysql_query("UPDATE playlist SET files='$saveFiles' WHERE id='$playlist'");
                         jsAlert("$saveFiles");
                     
                 }else if($type == "link"){
                     $links = explode(";", $checkPlaylistData[links]);
                     foreach($links as $link){
                         if($link == "$itemId"){
                         }else{
                             $newLinks[] = $link; 
                         }
                         
                     }
                         $saveLinks = implode(";", $newLinks);
                         mysql_query("UPDATE playlist SET links='$saveLinks' WHERE id='$playlist'");
                     
                 }elseif($type == "youTube"){
                     $youTubes = explode(";", $checkPlaylistData[youTube]);
                     foreach($youTubes as $youTube){
                         if($youTube == "$itemId"){
                         }else{
                             $newYouTubes[] = $youTube;
                         }
                         
                     }
                         $saveYouTubes = implode(";", $newYouTubes);
                         mysql_query("UPDATE playlist SET youTube='$saveYouTubes' WHERE id='$playlist'");
                         jsAlert("worked");
                 }
             ?>
             <script>
             parent.$('.playList<?=$type;?>No<?=$itemId;?>').remove();
             </script>
             <?
             }
        }else if($_GET[action] == "deleteItem"){
            if(proofLogin()){
            $type = $_GET[type];
            $itemId = $_GET[itemId];
            
            if($type == "feed"){
                $feedCheckSql = mysql_query("SELECT * FROM feed WHERE id='$itemId'");
                $feedCheckData = mysql_fetch_array($feedCheckSql);
                if(authorize($feedCheckData[privacy], "edit", $feedCheckData[author])){
                    mysql_query("DELETE FROM feed WHERE id='$itemId'");
                    ?>
                    <script>
                    parent.$('.feedNo<?=$itemId;?>').remove();
                    </script>
                    <?
                }
            }else if($type == "comment"){
                $commentCheck = mysql_query("SELECT privacy, author, type, typeid FROM comments WHERE id='$itemId'");
                $commentData = mysql_fetch_array($commentCheck);
                if(authorize($commentData[privacy], "edit", $commentData[author])){
                    mysql_query("DELETE FROM comments WHERE id='$itemId'");
                    ?>
                    <script>
                    parent.$('.commentBox<?=$itemId;?>').remove();
                    </script>
                    <?
                }
                if($commentData[type] == "profile" AND $commentData[typeid] == "$_SESSION[userid]"){
                    mysql_query("DELETE FROM comments WHERE id='$itemId'");
                    ?>
                    <script>
                    parent.$('.commentBox<?=$itemId;?>').remove();
                    </script>
                    <?
                }
            }else if($type == "playlist"){
                $checkPlaylistSql = mysql_query("SELECT user, privacy FROM playlist WHERE id='$itemId'");
                $checkPlaylistData = mysql_fetch_array($checkPlaylistSql);
                if(authorize($checkPlaylistData[privacy], "edit", $checkPlaylistData[user])){
                    mysql_query("DELETE FROM playlist WHERE id='$itemId'"); 
                    ?>
                    <script>
                    parent.$('#favTab_playList').load('doit.php?action=showUserPlaylists');
                    </script>
                    <?
                }else{
                	jsAlert("Not authorized!");
                }
                
            }else if($type == "folder"){
                $checkFolderSql = mysql_query("SELECT  privacy, creator, folder FROM folders WHERE id='$itemId'");
                $checkFolderData = mysql_fetch_array($checkFolderSql);
                if(authorize($checkFolderData[privacy], "edit", $checkFolderData[creator])){
                    deleteFolder("$itemId");
					?>
               	    <script>
                    	parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$checkFolderData[folder];?>&reload=1');
                	</script>
                	<?
                }
                
                
            }else if($type == "element"){
                $checkElementSql = mysql_query("SELECT folder, privacy, author FROM elements WHERE id='$itemId'");
                $checkElementData = mysql_fetch_array($checkElementSql);
                if(authorize($checkElementData[privacy], "edit", $checkElementData[creator])){
				
                   	deleteElement($itemId);
                    jsAlert("The Element has been deleted");
                    ?>
                        <script>
                            parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?reload=1&folder=<?=$checkElementData[folder];?>');
                        </script>
                    <?
                }
                
                
            }else if($type == "file"){
                $fileId = $itemId;
                $fileSql = mysql_query("SELECT * FROM files WHERE id='$fileId'");
                $fileData = mysql_fetch_array($fileSql);
                if(authorize($fileData[privacy], "edit", $fileData[owner])){
				
                    if(deleteFile($fileId) OR true){
                            $fileElementSql = mysql_query("SELECT id, title FROM elements WHERE id='$fileData[folder]'");
                            $fileElementData = mysql_fetch_array($fileElementSql);
                            jsAlert("File has been deleted :( ");
                                ?>
                                    <script>
                                        parent.addAjaxContentToTab('<?=substr($fileElementData[title], 0, 10);?>', 'modules/filesystem/showElement.php?element=<?=$fileElementData[id];?>&reload=1');
                                    </script>
                                <?
                        
                    }
				}
            }else if($type == "internLink"){
                $checkInternLinkData = mysql_fetch_array(mysql_query("SELECT * FROM internLinks WHERE id='$itemId'"));
                
                    if($checkInternLinkData[type] == "folder"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy, creator FROM folders WHERE id='$checkInternLinkData[typeId]'"));

                        $user = $shortCutItemData[creator];

                    }else if($checkInternLinkData[type] == "element"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, creator FROM elements WHERE id='$checkInternLinkData[typeId]'"));
                        $user = $shortCutItemData[creator];
                    }else if($checkInternLinkData[type] == "file"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type, owner FROM files WHERE id='$checkInternLinkData[typeId]'"));
                        $user = $shortCutItemData[owner];

                    }else if($checkInternLinkData[type] == "link"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type, author FROM links WHERE id='$checkInternLinkData[typeId]'"));
                        $user = $shortCutItemData[author];

                    }

                    if(authorize($shortCutItemData[privacy], "edit", $user)){
                        
                        if(deleteInternLink($itemId)){
                            jsAlert("The Shortcut has been deleted");
                            
                                if($checkInternLinkData[parentType] == "folder"){
                                    ?>
                                    <script>
                                      parent.addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$checkInternLinkData[parentId];?>&reload=1');
                                    </script>
                                    <?
                                }else if($checkInternLinkData[parentType] == "element"){
                                    $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysq_real_escape_string("$checkInternLinkData[parentId]")."'"));
                                    ?>
                                    <script>
                                        parent.addAjaxContentToTab('<?=substr($parentData[title], 0, 10);?>', 'modules/filesystem/showElement.php?element=<?=$checkInternLinkData[parentId];?>&reload=1');
                                    </script>
                                    <?
                                }
                        }

                    }
                
            }
                
            }
        }else if($_GET[action] == "changePrivacy"){
            if(proofLogin()){
            if(isset($_POST[submit])){
                
                    
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                
                    //setting privacy
                    $groups = $_POST[groups];
                    foreach ($groups as $group){
                        $Groups = "$group; $Groups";
                    }
                    if(empty($Groups)){
                        $Groups = "p";
                    }
                    //checking if upload is anonymous
                    if(!empty($_POST[anonymous])){
                        $user = "0";
                    }else{
                        $user = $userid;
                    }
                    if(isset($_POST[hidden])){
                        $Groups = "u";
                    }
                    if(isset($_POST[publ])){
                        $Groups = "p";
                    }
                    if($_GET[type] == "folder"){
                        mysql_query("UPDATE folders SET privacy='$privacy' WHERE id='$_GET[itemId]'");
                        if(!empty($_POST[hidden])){
                            mysql_query("UPDATE folders SET creator='$user' WHERE id='$_GET[itemId]'");   
                        }
                        jsAlert("Saved :)");
                    }
                    else if($_GET[type] == "element"){
                        mysql_query("UPDATE elements SET privacy='$privacy' WHERE id='$_GET[itemId]'");
                        if(!empty($_POST[hidden])){
                            mysql_query("UPDATE elements SET author='$user' WHERE id='$_GET[itemId]'");   
                        }
                        jsAlert("Saved :)");
                    }
                    else if($_GET[type] == "comment"){
                        mysql_query("UPDATE comments SET privacy='$privacy' WHERE id='$_GET[itemId]'");
                        if(!empty($_POST[hidden])){
                            mysql_query("UPDATE commments SET author='$user' WHERE id='$_GET[itemId]'");   
                        }
                        jsAlert("Saved :)");
                    }
                    else if($_GET[type] == "feed"){
                        mysql_query("UPDATE feed SET privacy='$privacy' WHERE id='$_GET[itemId]'");
                        jsAlert("Saved :) $_GET[itemId] $privacy");
                    }
                    else if($_GET[type] == "file"){
                        mysql_query("UPDATE files SET privacy='$privacy' WHERE id='$_GET[itemId]'");
                        jsAlert("Saved :)");
                    }
            }
            
            
            
            
            //get type
            if($_GET[type] == "folder"){
                $privacySql = mysql_query("SELECT name, privacy FROM folders WHERE id='$_GET[itemId]'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "folder $privacyData[name]";
            }
            if($_GET[type] == "element"){
                $privacySql = mysql_query("SELECT title, privacy FROM elements WHERE id='$_GET[itemId]'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "element $privacyData[title]";
            }
            
            if($_GET[type] == "comment"){
                $privacySql = mysql_query("SELECT privacy FROM comments WHERE id='$_GET[itemId]'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your comments";
            }
            if($_GET[type] == "feed"){
                $privacySql = mysql_query("SELECT privacy FROM feed WHERE id='$_GET[itemId]'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your feeds";
            }
            if($_GET[type] == "file"){
                $privacySql = mysql_query("SELECT privacy FROM files WHERE id='$_GET[itemId]'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your files";
            }
            
            //set values
            if($privacyData[privacy] == "p"){
                $public = 'checked="checked"';
            }else if($privacyData[privacy] == "u"){
                $hidden = 'checked="checked"';
            }else{
                $groupsArray = explode(";", $privacyData[privacy]);
            }
            ?>
            <form action="doit.php?action=changePrivacy&type=<?=$_GET[type];?>&itemId=<?=$_GET[itemId];?>" target="submitter" method="post">
            <div class="jqPopUp border-radius transparency" id="editPrivacy">
                <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closePrivacy">X</a>
                <header>Set privacy of <?=$title;?></header>
                <p style="font-size: 13pt;">
                <?
                  showPrivacySettings($privacyData[privacy]);
                ?>
                    
                <input type="submit" name="submit" value="save" class="btn btn-info" style="margin-top: 15px;">
                </p>
            </div>
            </form>
            <script>
                $("#submitPrivacy").click(function () {
                $('#editPrivacy').slideUp();
                });
                $("#closePrivacy").click(function () {
                $('#editPrivacy').slideUp();
                });
            </script>
            <?}
            }else if($_GET[action] == "editItem"){
                if(proofLogin()){
                $itemId = $_GET[itemId];
                $type = $_GET[type];
                if(isset($_POST[submit])){
                    if($type == "folder"){
                        $checkFolderSql = mysql_query("SELECT * FROM folders WHERE id='$itemId'");
                        $checkFolderData = mysql_fetch_array($checkFolderSql);
                        if(authorize($checkFolderData[privacy], "edit", $checkFolderData[creator])){
                            $parentFolderPath = getFolderPath($checkFolderData[folder]);
							
							if (!file_exists("$parentFolderPath".urldecode($_POST[name]))) {
                            if(rename("$parentFolderPath$checkFolderData[name]", "$parentFolderPath".urldecode($_POST[name]))){
                            mysql_query("UPDATE folders SET name='$_POST[name]' WHERE id='$itemId'");
                            jsAlert("Saved :)");
							}}else{
								jsAlert("A folder with this title already exists");
							}
                        }
                    }else if($type == "element"){
                        $checkElementSql = mysql_query("SELECT * FROM elements WHERE id='$itemId'");
                        $checkElementData = mysql_fetch_array($checkElementSql);
                        if($checkElementData[author] == "$_SESSION[userid]"){
                            mysql_query("UPDATE elements SET title='$_POST[title]', type='$_POST[type]', creator='$_POST[creator]', name='$_POST[name]', year='$_POST[year]' WHERE id='$itemId'");
                            jsAlert("Saved :)");
                        }
                    }else if($type == "file"){

                    }else if($type == "link"){
                    	$checkLinkData = mysql_fetch_array(mysql_query("SELECT * FROM links WHERE id='$itemId'"));
                    	
                        if(authorize($checkLinkData[privacy], "edit", $checkLinkData[creator])){
                        			
                            	$privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                        		
                        		mysql_query("UPDATE links SET title='$_POST[title]', link='$_POST[link]', type='$_POST[type]', privacy='$privacy' WHERE id='$itemId'");
								jsAlert("Saved :)");
                        	
                         }else{
                         	
                        		jsAlert("not authorized");
                         }
                    }else if($type == "playlist"){
                        $checkPlaylistSql = mysql_query("SELECT user FROM playlist WHERE id='$itemId'");
                        $checkPlaylistData = mysql_fetch_array($checkPlaylistSql);
                        if($checkPlaylistData[user] == "$_SESSION[userid]"){
                            
                            
                            
                            //set privacy
                            $customShow = $_POST[privacyCustomSee];
                            $customEdit = $_POST[privacyCustomEdit];

                            $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                            
                            mysql_query("UPDATE playlist SET title='$_POST[title]', privacy='$privacy'  WHERE id='$itemId'");
                            jsAlert("Saved :)");
                        }

                    }
                    
                }
                if($type == "folder"){
                    $editSQL = mysql_query("SELECT name FROM folders WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    
                    $title = $editData[name];
                    $headTitle = "folder $title";
                    $edit = "
                    <tr>
                        <td>Name:</td>
                        <td><input type=\"text\" name=\"name\" value=\"$title\"></td>
                    </tr>
                        ";
                    
                }else if($type == "element"){
                    $editSQL = mysql_query("SELECT name, type, creator, title, year  FROM elements WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $title = $editData[title];
                    $headTitle = "element $title";
                    $edit = "
                    <tr>
                        <td>Name:</td>
                        <td><input type=\"text\" name=\"title\" value=\"$title\"></td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td>
                        <select name=\"type\">
                            <option value=\"$editData[type]\">$editData[type]</option>
                            <option value=\"other\">other<option>
                            <option value=\"document\">document</option>
                            <option value=\"link\">link</option>
                            <option value=\"audio\">audio</option>
                            <option value=\"video\">video</option>
                            <option value=\"image\">image</option>
                            <option value=\"app\">execute</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>author:</td>
                        <td><input type=\"text\" name=\"creator\" value=\"$editData[creator]\"></td>
                    </tr>
                    <tr>
                        <td>title:</td>
                        <td><input type=\"text\" name=\"name\" value=\"$editData[name]\"></td>
                    </tr>
                    <tr>
                        <td>year:</td>
                        <td><input type=\"text\" name=\"year\" value=\"$editData[year]\"></td>
                    </tr>
                        ";
                }else if($type == "file"){
                    $editSQL = mysql_query("SELECT title FROM files WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $headTitle = "file";
                }else if($type == "link"){
                    $editSQL = mysql_query("SELECT type, title, link, privacy FROM links WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $title = $editData[title];
                    $headTitle = "link $title";
					
					//the type select needs to show the value
					//whis is defined in the db
					switch($editData[type]){
						case 'link':
							$selected['link'] = 'selected="selected"';
							break;
						case 'RSS':
							$selected[RSS] = 'selected="selected"';
							break;
						case 'youTube':
							$selected[youTube] = 'selected="selected"';
							break;
						case 'soundcloud':
							$selected[soundcloud] = 'selected="selected"';
							break;
						case 'file':
							$selected['file'] = 'selected="selected"';
							break;
						case 'other':
							$selected[other] = 'selected="selected"';
							break;
					}
                    $edit = "
                    <tr>
                        <td style=\"vertical-align: middle\">Link:</td>
                        <td><input type=\"text\" name=\"link\" value=\"$editData[link]\"></td>
                    </tr>
                    <tr>
                        <td style=\"vertical-align: middle\">Title:</td>
                        <td><input type=\"text\" name=\"title\" value=\"$title\"></td>
                    </tr>
                    <tr>
                        <td style=\"vertical-align: middle\">Type:</td>
                        <td>
                              <select name=\"type\">
                                  <option value=\"link\" $selected[link]>Standard Link<option>
                                  <option value=\"RSS\" $selected[RSS]>RSS</option>
                                  <option value=\"youTube\" $selected[youTube]>Youtube</option>
                                  <option value=\"soundcloud\" $selected[youTube]>Soundcloud</option>
                                  <option value=\"file\" $selected[file]>File</option>
                                  <option value=\"other\" $selected[other]>Other</option>
                              </select>
                        </td>
                    </tr>
                        ";
                	
                    $privacy = "$editData[privacy]";
					
                }else if($type == "playlist"){
                    $editSQL = mysql_query("SELECT title, privacy FROM playlist WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    
                    $title = $editData[title];
                    
                    $headTitle = "playlist $editData[title]";
                    
                    $edit = "
                    <tr>
                        <td style=\"vertical-align: middle\">Title:</td>
                        <td><input type=\"text\" name=\"title\" value=\"$title\"></td>
                    </tr>
                        ";
                    $privacy = "$editData[privacy]";
                    
					$back = "";
                    
                    $delete = "<a href=\"doit.php?action=deleteItem&type=playlist&itemId=$itemId\" target=\"submitter\" class=\"btn btn-danger\" onclick=\"$('#editItem').hide();\"><i class=\"icon-remove icon-white\"></i>&nbsp;delete</a>";
                }
                ?>
                    <form action="doit.php?action=editItem&type=<?=$_GET[type];?>&itemId=<?=$_GET[itemId];?>" method="post" target="submitter">
                        <div class="jqPopUp border-radius transparency" id="editItem" style="display: block">
                            <a class="jqClose" id="closeEditItem">X</a>  
                            <header>
                                Edit <?=$headTitle;?>
                            </header>
                            <div class="jqContent">
                            <table style="width: 90%; margin-left: 05%;">
                                <?=$edit;?>
                                <?
                                if(!empty($privacy)){
                                    echo"<tr><td colspan=\"2\">";
                                    showPrivacySettings($privacy);
                                    echo"</td></tr>";
                                }
                                ?>
                                <tr>
                                	<td>&nbsp;</td>
                                </tr>
                            </table>
                            </div>
                            <footer>
                            	<span class="pull-left"><?=$back;?></span>
                            	<span class="pull-right">
                            		<input type="submit" value="save" name="submit" id="submitPrivacy" class="btn btn-success">&nbsp;&nbsp;
                            		<?=$delete;?>
                            		</span>
                            </footer>
                        </div>
                    </form>
            <script>
                $("#submitPrivacy").click(function () {
                $('#editPrivacy').slideUp();
                });
                $("#closeEditItem").click(function () {
                $('#editItem').slideUp();
                });
            </script>
                <?}
            }else if($_GET[action] == "showItemThumb"){ 
                $type = $_GET[type];
                $itemId = $_GET[itemId];
                ?>
                <style>
                    .itemThumb{
                        border: 1px solid #c9c9c9;
                        width: 100%;
                        padding: 10px;
                    }
                    
                    .itemThumb td{
                        color: #58585A !important;
                    }
                </style>
                <div class="jqPopUp border-radius transparency" id="addFolder">
                    <a class="jqClose" id="closeFolder">X</a>
                    <header>Test Popup</header>
                        <div>
                            <div style="margin: 10px;">
                            
                                
                                
                                <?php
                                showPrivacySettings();
                                ?>
                                
                                
                                
                            </div>
                        </div>
                </div>
            <?    
            }else if($_GET[action] == "feedUpload"){
            if(proofLogin()){
            if(empty($_FILES['feedFile']['tmp_name'])){
                $error = "please select a file";
            }
            if(true){    
            $userSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
            $userData = mysql_fetch_array($userSql);
            
            $element = $userData[myFiles];
            $folder = $userData[homefolder];
            
            //privacy
            $privacy = "p";
            $user = $_SESSION[userid];

            $file = $_FILES['Filedata'];
            addFile($file, $element, $folder, $privacy, $user);
            }}
            }else if($_GET[action] == "reportFile"){
                if(isset($_POST[submit])){
                    $timstamp = time();
                    jsAlert("A report message has been send");
                    mysql_query("INSERT INTO `adminMessages` (`timestamp` ,`author` ,`category` ,`type` ,`message`) VALUES ('$time', '$_SESSION[userid]', '1', '$_POST[reason]', '$_POST[message]');");
                }
                ?>
            <form action="doit.php?action=reportFile&fileId=<?=$_GET[fileId];?>" method="post" target="submitter">
                <div class="jqPopUp border-radius transparency" id="reportFile" style="display: block">
                    <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closereportFile">X</a>  
                    <header>
                        Report File
                    </header>
                    <div class="jqContent">
                        <table width="500">
                            <tr height="70">
                                <td width="120">Reason:</td>
                                <td>
                                    <select name="reason">
                                        <option value="copyright">Copyright</option>
                                        <option value="humanRights">Human Rights</option>
                                        <option value="other">Other</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span style="font-size: 8pt;">Please give us a litle hint, why we should delete this File.</span>
                                    <textarea name="message" style="width: 500px; height: 120px;"></textarea>
                                </td>
                            </tr>
                            <tr height="70">
                                <td><input type="submit" value="save" name="submit" id="reportFileSubmit" class="button"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
            <script>
                $("#reportFileSubmit").click(function () {
                $('#reportFile').slideUp();
                });
                $("#closereportFile").click(function () {
                $('#reportFile').slideUp();
                });
            </script>
                <?
            }else if($_GET[action] == "updateUserActivity"){
                if(proofLogin()){
                updateActivity("$_SESSION[userid]");
                }
            }else if($_GET[action] == "reportBug"){
                if(isset($_POST[submit])){
                    $time=time();
                    if(mysql_query("INSERT INTO `adminMessages` (`timestamp` ,`author` ,`category` ,`type` ,`message`) VALUES (".time().",'$_SESSION[userid]', '2', 'Bug', '$_POST[message]');")){  
                    jsAlert("Thanks dude! The bug report has been send to our admins.");
                    }
                }?>
                <form action="doit.php?action=reportBug&fileId=<?=$_GET[fileId];?>" method="post" target="submitter">
                    <div class="jqPopUp border-radius transparency" id="reportBug" style="display: block">
                        <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closereportBug">X</a>  
                        <header>
                            Report a bug
                        </header>
                        <div class="jqContent">
                            <table width="500">
                                <tr height="70">
                                    <td colspan="2">Please describe as detailed as possibile what youve found.<br>And please look into the <a href="#" onclick="openUniverseWikiArticle('Buglist');">buglist</a> if your bug was allready reported.</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea name="message" style="width: 500px; height: 120px;"></textarea>
                                    </td>
                                </tr>
                                <tr height="70">
                                    <td><input type="submit" value="send" name="submit" id="reportBugSubmit" class="button"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
                <script>
                    $("#reportBugSubmit").click(function () {
                    $('#reportBug').slideUp();
                    });
                    $("#closereportBug").click(function () {
                    $('#reportBug').slideUp();
                    });
                </script>
            <?}else if($_GET[action] == "deleteFile"){
//cann propably be deleted
                if(proofLogin()){
                    
                    $fileId = save($_GET[fileId]);
                    if(deleteFile($fileId)){
                        $fileSql = mysql_query("SELECT * FROM files WHERE id='$fileId'");
                        $fileData = mysql_fetch_array($fileSql);
                            $fileElementSql = mysql_query("SELECT id, title FROM elements WHERE id='$fileData[folder]'");
                            $fileElementData = mysql_fetch_array($fileElementSql);
                            jsAlert("File has been deleted :( ");
                                ?>
                                    <script>
                                        parent.addAjaxContentToTab('<?=substr($fileElementData[title], 0, 10);?>', 'modules/filesystem/showElement.php?element=<?=$fileElementData[id];?>&reload=1');
                                    </script>
                                <?
                        
                    }
                }
            }else if($_GET[action] == "deleteLink"){
                
                $linkId = save($_GET[linkId]);
                if(proofLogin()){
                    if(deleteLink($linkId)){
                        jsAlert("The Link has been deleted");
                    }
                }
            }else if($_GET[action] == "protectFileSystemItems"){
            	protectFilesystemItem($_GET[type], $_GET[itemId]);
				jsAlert("worked!");
            }
            
            
// ajax stuff
// ajax stuff
// ajax stuff
            
            else if($_GET[action] == "mousePop"){
                $type = $_POST[type];
                $id = $_POST[id];
                $html = $_POST[html];
                switch($type){
                    case none:
                        $text = $html;
                    break;
                    case youTube:
                        $title = youTubeIdToTitle($id);
                        if(!empty($title)){
                        $text = "
                                <table>
                                    <tr>
                                        <td class=\"title\">$title</td>
                                    </tr>
                                    <tr height=\"05\">
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td><img src=\"http://img.youtube.com/vi/$id/1.jpg\" width=\"70\">&nbsp;&nbsp;</td>
                                                    <td>Watch this video</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>";
                        }
                    break;
                }
                ?>
                <div class="mousePop grayGradient" id="mousePop_<?=$type;?><?=$id;?>">
                    <span onclick="$('.mousePop').hide();" class="close">x</span>
                    <div class="content">
                    <?=$text;?>
                    </div>
                </div>
                <script>
                    $("#mousePop_<?=$type;?><?=$id;?>").ready(function(){
                        
                        //bring popUp to mouse position
                        var top = window.mouseY - $('#mousePop_<?=$type;?><?=$id;?>').height();
                        var left = window.mouseX - $('#mousePop_<?=$type;?><?=$id;?>').width();
                        $('#mousePop_<?=$type;?><?=$id;?>').css({'top' : top, 'left' : left});
                        
                        //bring popup to the front
                        
                        $("#mousePop_<?=$type;?><?=$id;?>").css('z-index', 99999);
                        $("#mousePop_<?=$type;?><?=$id;?>").css('position', 'absolute');
                    });
                </script>
            <?}else if($_GET[action] == "createNewUFF"){
                
                $element = save($_GET[element]);
                if(isset($_POST[submit])){
                    $element = save($_POST[element]);
                    $title = save($_POST[title]);
                    $filename = save($_POST[filename]);
                    //set privacy
                    $customShow = $_POST[privacyCustomSee];
                    $customEdit = $_POST[privacyCustomEdit];
                    
                    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
                            $user = $_SESSION[userid];


                    //upload file
                    $elementSQL = mysql_query("SELECT folder, title FROM elements WHERE id='$element'");
                    $elementData = mysql_fetch_array($elementSQL);
					
					    
            			$title10 = addslashes(substr("$elementData[title]", 0, 10));
                        
                        $path = getFolderPath($elementData[folder]);
                        $filename = "$filename.UFF";
                        $folder = $element;
                        $timestamp = time();
                        
                        
                        $ourFileName = "$path$filename";
                        
                        $ourFileHandle = fopen($ourFileName, 'w') or jsAlert("can\'t open file");
                        fclose($ourFileHandle);
                        if(mysql_query("INSERT INTO `files` (`id`, `folder`, `title`, `size`, `timestamp`, `filename`, `language`, `type`, `owner`, `votes`, `score`, `privacy`) VALUES (NULL, '$folder', '$title', '', '$timestamp', '$filename', '', 'UFF', '$user', '0', '0', '$privacy');")){
                          
                            //add feed
                            $fileId = mysql_insert_id();
                            $feed = "has created a new UFF-file";
                            createFeed($user, $feed, "", "showThumb", $privacy, "file", $fileId);
                            
                           
                            jsAlert("your file has been created");
                            ?>
                            <script>
			                parent.addAjaxContentToTab('<?=$title10;?>', 'modules/filesystem/showElement.php?element=<?=$element;?>&reload=1');
			               
                           	</script>
			                <?
                        }
                }else{
                $elementData = mysql_fetch_array(mysql_query("SELECT privacy FROM elements WHERE id='$element'"));
                ?>
            <div class="jqPopUp border-radius transparency" id="newUFF" style="width: 600px; height: 400px;">
                <a href="#" class="jqClose" id="closeNewUff">X</a>
                <header>
                    Add UFF File
                </header>
                <div class="jqContent">
                    <form action="doit.php?action=createNewUFF" method="post" target="submitter">
                        <table>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top" align="right">Title:</td>
                                <td>&nbsp;</td>
                                <td><input type="text" name="title" style="width: 300px;"></td>
                            </tr>
                            <tr>
                                <td valign="top" align="right">Filename:</td>
                                <td></td>
                                <td><input type="text" name="filename" style="width: 300px;"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?=showPrivacySettings($elementData[privacy]);?>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="element" value="<?=$element;?>"></td>
                                <td></td>
                                <td>
                                    <input type="submit" value="add" name="submit" id="submitNewUFF" class="btn btn-success">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <script>
                $("#submitNewUFF").click(function () {
                $('#newUFF').slideUp();
                });
                $("#closeNewUff").click(function () {
                $('#newUFF').slideUp();
                });
            </script>
                
            <? }}else if($_GET[action] == "loadUff"){
                //is used to load content of a UFF
                $id = save($_GET[id]);
                echo showUffFile($id, '1');
                
            }else if($_GET[action] == "writeUff"){
                $id = save($_POST[id]);
                $input = $_POST[input];
                writeUffFile($id, $input, '1');
                
            }else if($_GET[action] == "removeUFFcookie"){
                $id = save($_POST[id]);
                removeUFFcookie($id);
            }else if($_GET[action] == "tester"){
            	echo "woff";
echo getMime('dings.pdf') . "\n";
echo mime_content_type('test.pdf');
            }
?>