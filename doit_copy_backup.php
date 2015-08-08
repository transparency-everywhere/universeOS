<?PHP
session_start();
include("inc/config.php");
include("inc/functions.php");
$time = time();
if($_GET['action'] == "showScore"){
    $item = new item($_GET['type'], $_GET['typeid']);
    echo $item->showScore(1);
}
else if($_GET['action'] == "removeFav"){
        $classFav = new fav();
    	$classFav->remove($_POST['type'], $_POST['typeId']);
  	}
else if($_GET['action'] == "requestpositive"){
    	$buddy = $_GET['buddy'];
        $buddyListClass = new buddylist();
        if($buddyListClass->replyRequest($buddy)){
        	
			$_SESSION['personalFeed'] = 0;
	        echo"<script>parent.$('#friendRequest_$buddy').hide()</script>";
	        jsAlert("worked :)");
		}
    }
else if($_GET['action'] == "requestnegative"){
    	$buddy = $_GET['buddy'];
        $buddyListClass = new buddylist();
        if($buddyListClass->denyRequest($_GET['buddy'])){
        	
	        echo"<script>parent.$('#friendRequest_$buddy').hide()</script>";
	        jsAlert("worked :)");
			
        }
    }
else if($_GET['action'] == "addToNotSuggestList"){
                $buddylistClass = new buddylist();
		$buddylistClass->addToNotSuggestList($_GET['user']);
		echo"<script>parent.$('#buddySuggestions').load('doit.php?action=showBuddySuggestList');</script>";
		 
     }
else if($_GET['action'] == "showBuddySuggestList"){
     	$false = false;
		showBuddySuggestions($false);
     }
else if($_GET['action'] == "deleteBuddy"){
     	$buddy = $_GET['buddy'];
        $buddyListClass = new buddylist();
     	$buddyListClass->deleteBuddy($buddy);
		jsAlert("The Buddy was removed from your Buddylist.");
		echo "<script>parent.$('.buddy_$buddy').hide()</script>";
     }
else if($_GET['action'] == "addFileToPlaylist"){
         
         $playlist = save($_POST['playlistId']);
         $newPlaylist = save($_POST['playlistName']);
         $file = save($_GET['file']);
         $folder = save($_GET['folder']);
         $element = save($_GET['element']);
         $link = save($_GET['link']);
         
         
                    if((!empty($playlist))OR(!empty($newPlaylist))){
                    $UpdateStringSql = mysql_query("SELECT id, files, folders, links FROM playlist WHERE id='$playlist'");
                    $UpdateData = mysql_fetch_array($UpdateStringSql);
                    if(!empty($newPlaylist)){
                        mysql_query("INSERT INTO playlist (user, title) VALUES('".$_SESSION['userid']."', '$newPlaylist')");
                        $playlist = mysql_insert_id();
                    }
                        if(!empty($file)){
                        $files= "$file;".$UpdateData['files'];
                        mysql_query("UPDATE playlist SET files='$files' WHERE id='$playlist'");
                        }
                        if(!empty($folder)){
                        $folders = "$folder;".$UpdateData['folders'];
                        mysql_query("UPDATE playlist SET folders='$folders' WHERE id='$playlist'");
                        }
                        if(!empty($element)){
                        $elements = "$element;".$UpdateData['elements'];
                        mysql_query("UPDATE playlist SET elements='$elements' WHERE id='$playlist'");
                        }
                        if(!empty($link)){
                        $links = "$link;".$UpdateData['links'];
                        mysql_query("UPDATE playlist SET links='$links' WHERE id='$playlist'");
                        }
						
                }else{
                    if(!empty($file)){
                    $fileSql = mysql_query("SELECT id, title FROM files WHERE id='$file'");
                    $fileData = mysql_fetch_array($fileSql);
                    }
                    if(!empty($folder)){
                    $fileSql = mysql_query("SELECT id, name FROM folders WHERE id='$folder'");
                    $fileData = mysql_fetch_array($fileSql);
                    $fileData['title'] = $fileData['name'];
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

                <div class="jqPopUp border-radius transparency">
                    <a class="jqClose" id="closePlaylist">X</a>
                    <header>
                        <?=$fileData['title'];?>
                    </header>
                    <div class="jqContent">
                        <p>Please choose a playlist:</p>
                        <select name="playlistId">
                            <option value=""></option>
                            <?
                            $playListsSql = mysql_query("SELECT id, title FROM playlist WHERE user='".$_SESSION['userid']."'");
                            while($playListsData = mysql_fetch_array($playListsSql)){
                            ?>
                            <option value="<?=$playListsData['id'];?>"><?=$playListsData['title'];?></option>
                            <? } ?>
                        </select>
                        <p>Add to new Playlist:</p>
                        <input type="text" name="playlistName">
                    </div>
			        <footer>
			         	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').hide();">Back</a></span>
			         	<span class="pull-right"><input type="submit" value="add" id="playlistSubmit" class="btn btn-success"></span>
			        </footer>
                </div> 
            </form>
            <script>
                $("#playlistSubmit").click(function () {
                    $('#addPlaylist').slideUp();
                });
            </script>
     <?}
     
     }
else if($_GET['action'] == "copyPlaylist"){
         $playList = save($_GET['playlist']);
         $classDb = new db();
         $playListData = $classDb->select('playlist', array('id', $playList));
         
         if(isset($_POST['submit'])){
                        
                    //set privacy
                    $customShow = $_POST['privacyCustomSee'];
                    $customEdit = $_POST['privacyCustomEdit'];
                    
                    $privacy = exploitPrivacy("".$_POST['privacyPublic']."", "".$_POST['privacyHidden']."", $customEdit, $customShow);
                    $user = $_SESSION['userid'];
					
                mysql_query("INSERT INTO playlist (user, title, privacy, links, files, youTube) VALUES('$user', '".$_POST['title']."', '$privacy', '".$playlistData['links']."', '".$playlistData['files']."', '".$playlistData['youTube']."')");
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
                Copy Playlist <?=$playlistData['title'];?>
            </header>
            <div class="jqContent">
                <table width="100%">
                    <tr>
                        <td width="180">Title:</td>
                        <td><input type="text" name="title" style="width:300px;" value="<?=$playlistData['title'];?>"></td>
                    </tr>
                    <tr>
                        <td valign="top">Privacy</td>
                        <td>
							<?
                                                        $privacyClass = new privacy("h//f");
							$privacyClass->showPrivacySettings();
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
else if($_GET['action'] == "showUserPlaylists"){ ?>
                 
                    <h3 class="readerStartItem">
                        <img src="./gfx/icons/playlist.png" height="14">&nbsp;Your Playlists<span style="float: right;"><a href="javascript: playlists.showCreationForm();" class="btn"><img src="./gfx/icons/playlist.png" height="14">&nbsp;Add Playlist</a></span>
                    </h3>
                      <table class="border-top-radius border-box readerStartItem" cellspacing="0" style="border: 1px solid #c9c9c9; margin-top: -15px;">
                          <tr class="grayBar" height="35">
                              <td width="27">&nbsp;</td>
                              <td width="200">Title</td>
                              <td>Played</td>
                          </tr>
                            <?PHP
                            unset($i);
                            
                            
                            
                                $userGroupsSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".$_SESSION['userid']."'");
                                while($userGroupsData = mysql_fetch_array($userGroupsSql)){
                                    $userGroups[] = $userGroupsData['group'];
                                }
                                foreach($userGroups AS &$userGroup){
                                    $query = "$query OR INSTR(`privacy`, '{$userGroup}') > 0";
                                }
                            
                            
                            $playListSql = mysql_query("SELECT id, user, title, played FROM playlist WHERE user='".getUser()."' $query");
                            while($playListData = mysql_fetch_array($playListSql)){
                            $i++;
                            ?>
                            <tr border="0" class="strippedRow" width="100%" height="30">
                                <td width="27">&nbsp;<img src="./gfx/icons/playlist.png"></td>
                                <td width="150"><a href="showPlaylist('<?=$playListData['id'];?>')"><?=$playListData['title']?></a></td>
                                <td><?=$playListData['played'];?></td>
                            </tr>
                            <?PHP
                            } 
                            if(empty($i)){
                                echo"Add playlist to automaticly play mp3´s and Youtube Songs in a row.";
                            }
?>
                        </table><br><br>
     <?PHP
     }
else if($_GET['action'] == "showUserGroups"){ ?>
                 
                      <h3 class="readerStartItem">
                        <img src="./gfx/icons/group.png" height="14">&nbsp;Your Groups <span style="float:right"><a href="javascript: popper('doit.php?action=addGroup');" class="btn"><img src="./gfx/icons/group.png" height="14">&nbsp;Add Group</a>  </span>
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
                           $classDb = new db();
                           $result = $classDb->select('groupAttachments', array('group', '2'));
                           $num_rows = mysql_num_rows($result);
                           $title = $groupData['title'];
                           $title10 = substr("$title", 0, 10);
                           $title15 = substr("$title", 0, 25);
                           ?>
                               <tr height="30" class="strippedRow">
                                   <td width="27">&nbsp;<img src="./gfx/icons/group.png" height="15"></td>
                                   <td width="300">&nbsp;<a href="#" onclick="groups.showProfile('<?=$groupData['id'];?>');"><?=$title15;?></a></td>
                                   <td align="right"><?=groups::countGroupMembers($groupData['id']);?>&nbsp;&nbsp;</td>
                               </tr>
                       <?}
                       if($i < 1){
                           echo'<tr><td colspan="3">You are in no group</td></tr>';
                       }
                       ?>
                     </table>
     <?    
     }
else if($_GET['action'] == "showUserGroups"){
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
                           $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='".$attData['group']."'");
                           $groupData = mysql_fetch_array($groupSql);
                       $classDb = new db();
                           $result = $classDb->select('groupAttachments', array('group', '2'));
                       $num_rows = mysql_num_rows($result);
                           $title = $groupData['title'];
                           $title10 = substr("$title", 0, 10);
                           $title15 = substr("$title", 0, 25);
                           ?>
                               <tr height="30" class="strippedRow">
                                   <td width="27">&nbsp;<img src="./gfx/icons/group.png" height="15"></td>
                                   <td width="300">&nbsp;<a href="#" onclick="groups.showProfile('<?=$groupData['id'];?>'); return false"><?=$title15;?></a></td>
                                   <td><?=groups::countGroupMembers($groupData['id']);?></td>
                               </tr>
                       <?}
                       if($i < 1){
                           echo"Your are in no group";
                       }
                       ?>
                     </table>
         <?
         
     }
else if($_GET['action'] == "requestGroup"){
         $time = time();
		 $group = save($_GET['group']);
         mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('".$group."', 'user', '".getUser()."', '$time', '".getUser()."', '0');");
     }
else if($_GET['action'] == "loadPersonalFileFrame"){
    
         //is used to load filelists into the reader home view
         $query = save($_GET['query']);
         switch($query){
             case allFiles:
                  
                  $user = $_SESSION['userid'];
                  $fileSystem = new fileSystem();
                 
                  //show folders and elements
                  $folderQuery = "WHERE creator='$user' ORDER BY timestamp DESC";
                  $elementQuery = "WHERE author='$user' ORDER BY timestamp DESC";
                  $fileSystem->showFileBrowser($folder, "$folderQuery", "$elementQuery");
                  
                  //show files
                  $element = new element();
                  $fileQuery = "owner='$user' ORDER BY timestamp DESC";
                  echo'<table width="100%">';
                  $element->showFileList('', $fileQuery);
                  echo"</table>";
             break;
             case myFiles:
                  $userData = mysql_query("SELECT myFiles FROM user WHERE userid='".$_SESSION['userid']."'");
                  $userData = mysql_fetch_array($userData);
                  //show files
                  $fileQuery = "id='".$userData['myFiles']."'";
                  echo'<table width="100%">';
                  $element = new element();
                  $element->showFileList('', $fileQuery);
                  echo"</table>";
             break;
         }
         
     }
else if($_GET['action'] == "loadMiniBrowser"){
                $fileSystem = new fileSystem();
		$fileSystem->showMiniFileBrowser("".$_GET['folder']."", "".$_GET['element']."", "".$_GET['level']."", false, $_GET['select']);
}
else if($_GET['action'] == "addInternLink"){
     	if(proofLogin()){
     		
     		if(isset($_POST['submit'])){
                                $shortcutClass = new shortcut();
     			
				if($shortcutClass->create($_POST['parentType'], $_POST['parentId'], $_POST['type'], $_POST['typeId'], $_POST['title'])){
					jsAlert("The shortcut has been added :)");
				}
                                
                                //
                                if($_POST['parentType'] == "folder"){
                                ?>
                                <script>
                                    parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?folder=<?=$_POST['parentId'];?>&reload=1'));
                                </script>
                                <?
                                }else if($_POST['parentType'] == "element"){
                                    $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysq_real_escape_string("".$_POST['parentId']."")."'"));
                                    ?>
                                    <script>
                                    parent.filesystem.tabs.updateTabContent('<?=substr($parentData['title'], 0, 10);?>' ,parent.gui.loadPage('modules/filesystem/showElement.php?element=<?=$_POST['parentId'];?>&reload=1'));
                                    </script>
                                    <?
                                }
     			
     		}else{
												
                    if(!empty($_GET['parentFolder'])){
                            $parentData = mysql_fetch_array(mysql_query("SELECT name FROM folders WHERE id='".mysql_real_escape_string($_GET['parentFolder'])."'"));
                            $titleText = "folder ".$parentData['name']."";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentType\" value=\"folder\">";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentId\" value=\"".$_GET['parentFolder']."\">";
                    }
                    if(!empty($_GET['parentElement'])){
                            $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysql_real_escape_string($_GET['parentElement'])."'"));
                            $titleText = "element ".$parentData['title']."";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentType\" value=\"element\">";
                            $hiddenInput .= "<input type=\"hidden\" name=\"parentId\" value=\"".$_GET['parentElement']."\">";
                    }
     			?>
                <form action="./doit.php?action=addInternLink" method="post" target="submitter"> 
                                <div class="jqPopUp border-radius transparency" id="addInternLink">
                                <a class="jqClose" id="closeFolder">X</a>
                                <header>Create Shortcut in <?=$titleText;?></header>
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
												$fileSystem = new fileSystem();
												$fileSystem->showMiniFileBrowser("1");
												
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
     }
else if($_GET['action'] == "changeBackgroundImage"){
        if(proofLogin()){
        if($_GET['type'] == "file"){
            $dbClass = new db();
            $fileData = $dbclass->select('files', array('id', $_GET['id']));
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
     }}
else if($_GET['action'] == "writeMessage"){
        if(proofLogin()){
        $user = save($_GET['buddy']);
        if(isset($_POST['feed'])) {
        if(!isset($postCheck)) {
        $time = time();
        $message = $_POST['feed'];
        $crypt = "0";
        
    mysql_query("INSERT INTO messages (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('".getUser()."', '$user', '$time', '$message', '0', '$crypt');");
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
         if(isset($_GET['buddy'])){
			 useridToUsername(save($_GET['buddy']));
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
else if($_GET['action'] == "groupInviteUsers"){
         if(proofLogin()){
         $group = save($_GET['id']);
         $time = time();
         if($_POST['submit']){
         $userlist = $_POST['users'];
         $groupsClass = new groups();
         if(isset($userlist)){
                    foreach ($userlist as &$value) {
	         	$groupsClass->sendRequestToUser($group, $value,getUser());
                    }
		 }
		 jsAlert("worked:)");
         }
         $dbClass = new db();
         $groupData = $dbClass->select('groups', array('id', $group));
		 
		 
		 //query to slow needs to be replaced
		 $userAttachmentsSQL = mysql_query("SELECT itemId FROM groupAttachments WHERE `group`='".save($group)."' AND `item`='user'");
		 while($userAttachmentsData = mysql_fetch_assoc($userAttachmentsSQL)){
		 	$users[] = $userAttachmentsData['itemId'];
		 }
		 ?>
	<form action="doit.php?action=groupInviteUsers&id=<?=$group;?>" method="post" target="submitter" id="groupInviteForm">
       <div class="jqPopUp border-radius transparency" id="groupInvite">
            <header>
            <a class="jqClose" id="closeInvite" onclick="$('.jqPopUp').slideUp();">X</a>
            Invite Users - <?=$groupData['title'];?>
            </header>
            <div class="jqContent">
	            <table height="250">
	                <tr>
	                    <td valign="top">Choose Friends:&nbsp;</td>
	                    <td>
	                        <table>
	                            <tr>
	                                <td>
	                                    <div>
	                                        <ul>
					                        <?
                                                                            $buddyListClass = new buddylist();
								            $buddies = $buddyListClass->buddyListArray();
											foreach($buddies AS $buddy){
												if(!in_array($buddy, $users)){
													$username =  useridToUsername($buddy);
												
					                            	$i++;
					                            echo"<li style=\"line-height: 37px;\">";
				                            		echo"&nbsp;<input type=\"checkbox\" name=\"users[]\" value=\"$buddy\">&nbsp;".showUserPicture($buddy , 25)."&nbsp;$username";
				                            	echo"</li>";
	                            			}}?> 
	                                        </ul>
	                                    </div>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                </tr>
	            </table>
            </div>
	        <footer>
	        	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	        	<span class="pull-right"><input type="submit" name="submit" value="invite" class="btn"></span>
	        </footer>
        </div>
        </form>
        <script>
            $("#groupInviteForm").submit(function () {
            $('.jqPopUp').slideUp();
            });
        </script>
        <?}
     }
else if($_GET['action'] == "groupMakeUserAdmin"){
     	
	$groupsClass = new groups();
     	echo $groupsClass->makeUserAdmin($_GET['groupId'], $_GET['userId']);
		
		
     }
else if($_GET['action'] == "groupremoveAdmin"){
     	
     }
else if($_GET['action'] == "groupLeave"){
         $group = $_GET['id'];
        $groupsClass = new groups();
     	if($groupsClass->deleteUserFromGroup(getUser(), $group)){
         jsAlert("You left the group");
		}
     }
else if($_GET['action'] == "deleteUserFromGroup"){
        $groupsClass = new groups();
     	if($groupsClass->deleteUserFromGroup($_GET['userid'], $_GET['groupid'])){
			echo'<script>parent.$(".groupMember_'.$_GET['groupid'].'_'.$_GET['userid'].'").hide();</script>';
     		jsAlert('The user left the group.');
     	}
     }
     
     
//CHAT - IM
//CHAT - IM
//CHAT - IM
     
     
else if($_GET['action'] == "chatSendMessage"){
                $messageClass = new message();
		if($messageClass->send($_GET['buddy'], $_POST['message'], $_POST['cryption'])){
			
	        echo"<script>";
				?>
				parent.updateDashbox('message');
	            parent.$('.chatInput').val('');
	            parent.$('#test_<?=str_replace(" ","_",$_GET['buddyname']);?>').load('modules/chat/chatreload.php?buddy=<?=urlencode($_GET['buddyname']);?>&initter=1');
	        	<?
	        echo"</script>";
		}
		
        }
else if($_GET['action'] == "updateMessageStatus"){
             
        	//updates if the message was seen, after the receiver clicked the input in the textarea
	        $user = getUser();
	        $buddy = $_GET['buddy'];
                    $messageClass = new message();
		    $messageClass->markAsRead($buddy, $user);
			echo "1";
		
        }
else if($_GET['action'] == "chatReload"){
        	
		  $buddyName = $_GET['buddyName'];
		  $buddy = user::usernameToUserid($buddyName);
                  $messageClass = new message();
			
          echo'<script>';
		   	echo"chatEncrypt('$buddyName');";
		  echo'</script>';
          echo"<div class=\"chatMainFrame_<?=$buddyName;?>\">";
          
                $messageClass->showMessages(getUser(), $buddy, "0,10");
          echo"<div onclick=\"chatLoadMore('<?=$buddyName;?>', '1');\">...load more</div>";
          echo'</div>';
        }
else if($_GET['action'] == "chatLoadMore"){
            
            $userid = getUser();
            $buddyName = save($_GET['buddy']);
            $buddy = user::usernameToUserid($buddyName);
            
            //when chatframe is loaded $limit = 0, when load more is clicked the first time $limit=1 etc.
            //it always adds thirty messages
            $limit = save($_GET['limit']);
            $newLimit = $limit+1;
            //convert $limit to a mysql LIMIT conform string 
            $limit = $limit*10;
            $limit = ($limit).",".($limit+10);
            
            
	
                $messageClass = new message();
           	$messageClass->showMessages($userid, $buddy, $limit);
				
				
				
                echo "<div onclick=\"chatLoadMore('$buddyName', '$newLimit'); $(this).hide();\">...load more</div>";
            
                
        }
else if($_GET['action'] == "chatSendItem"){
        	if(isset($_POST['submit'])){
        		
                    $messageClass = new message();
                    $message = "[itemThumb type=".$_POST['type']." typeId=".$_POST['typeId']."]";
                    if($messageClass->send($_POST['buddy'], $message, $_POST['cryption'])){
                            jsAlert("message");
                    echo"<script>";
                                    ?>
                        parent.$('.chatInput').val('');
                        parent.$('#test_<?=str_replace(" ","_",$_POST['buddyName']);?>').load('modules/chat/chatt.php?buddy=<?=urlencode($_POST['buddyName']);?>&initter=1');
                            <?
                    echo"</script>";
                    }
        	}
        	$buddy = $_GET['buddy'];
			$buddyName = useridToUsername($buddy);
                ?>
                			<form action="doit.php?action=chatSendItem" method="post" target="submitter" onsubmit="$('.jqPopUp').slideUp();">
                                <div class="jqPopUp border-radius transparency" id="chatSendItem">
                                <a class="jqClose" id="closeFolder">X</a>
                                <header>Send Item to <?=$buddyName;?></header>
                                <div class="jqContent">
                				<input type="hidden" name="buddy" value="<?=$buddy;?>">
                				<input type="hidden" name="buddyName" value="<?=$buddyName;?>">
                                	
                                	<?php
                                		echo"<h3>Please choose a File from the Filesystem:</h3>";
                                		echo"<div>";
                                                $fileSystem = new fileSystem();
                                                $fileSystem->showMiniFileBrowser("1", '', '', true);
                                		echo"</div>";
									?>
                                </div>
	                            <footer>
	                            	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	                            	<span class="pull-right"><input type="submit" name="submit" id="submitSend" class="btn btn-success" value="Send"></span>
	                            </footer>
                                </div>
                               </form>
                <script>
                    $(".jqClose").click(function(){
                        $('.jqPopUp').slideUp();
                    });
                </script>
         
		<?
        }
else if($_GET['action'] == "createFeed"){
            
            if(!empty($_POST['feedInput']) || !empty($_POST['feed1'])){
            
                    //set privacy
                    $customShow = $_POST['privacyCustomSee'];
                    $customEdit = $_POST['privacyCustomEdit'];
                    
                    $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                    $user = getUser();
                    $feed = $_POST['feedInput'].$_POST['feed1'];
                    
                    //create feed
                    $feedClass = new feed();
                    $id = $feedClass->create(getUser(), $feed, "", "feed", "p");
            ?>
            <script>
            parent.reloadFeed('friends');
            parent.$('#feedInput').val('');
            </script>
            <?php
                }
            
        }
else if($_GET['action'] == "showSingleFeed"){
        $feedClass = new feed();
        $feedId = save($_GET['feedId']);
        echo'<div id="addFeed"></div>';
        $feedClass->show("singleEntry", "", "", "$feedId");
        
        
        }
else if($_GET['action'] == "feedLoadMore"){
            $feedClass = new feed();
            $type = save($_GET['type']);
            $user = save($_GET['user']);
            $limit = save($_GET['limit']);
            
            //when chatframe is loaded $limit = 0, when load more is clicked the first time $limit=1 etc.
            //it always adds thirty messages
            $newLimit = $limit+1;
            //convert $limit to a mysql LIMIT conform string 
            $limit = $limit*30;
            $limit = ($limit).",".($limit+30);
            $feedClass->show("$type", "$user","$limit");
            
            
        }
else if($_GET['action'] == "showYoutube"){
         if(isset($_GET['start'])){?>
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
                swfobject.embedSWF("http://www.youtube.com/v/<?=$_GET['id'];?>?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1",
                                "ytapiplayer", "1000", "600", "8", null, null, params, atts);


            function onYouTubePlayerReady(playerId) {
            ytplayer = document.getElementById("myytplayer");
            ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
            }
            <?php
            if(!empty($_GET['playList'])){
                $row = ($_GET['row']+1);?>
            function onytplayerStateChange(newState) {
            if(newState == "0" || newState == ""){

                parent.nextPlaylistItem('<?=$_GET['playList'];?>','<?=$row;?>');
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
        <?php
        if(isset($_GET['playList'])){
            //load new next and start button
            ?>
                        <script> 
                        parent.$('#togglePlayListTitle_<?=$_GET['playList'];?>').text('<?=addslashes(youTubeIdToTitle($_GET['id']));?>');
                        parent.$('#togglePlayList_<?=$_GET['playList'];?>').html( function(){
                            
               
                            
                            var playListToggleLink = '<a href="#" onclick="nextPlaylistItem(\'<?=$_GET['playList'];?>\',\'<?=($row-2);?>\');" title="last Track" class="btn btn-mini"><i class=\"icon-backward\"></i></a>&nbsp;<a href="#" onclick="nextPlaylistItem(\'<?=$_GET[playList];?>\',\'<?=($row);?>\');" title="next Track" class="btn btn-mini"><i class=\"icon-forward\"></i></a>';

                            $(this).html(playListToggleLink);
                        });
                        </script>
            <?php
            
            }
        if(isset($_GET['start'])){?>
                    </div>
         <?}
         
     }
else if($_GET['action'] == "addYouTubeItemToPlaylistVeryLongName"){
         $playlist = save($_POST['playlistId']);
         $vId = save($_GET['vId']);
         
         $UpdateStringSql = mysql_query("SELECT id, youTube FROM playlist WHERE id='$playlist'");
         $UpdateData = mysql_fetch_array($UpdateStringSql);
   
            if(!empty($vId)){
            	
				//faster than explode-implode
            	if(!empty($UpdateData['youTube'])){
			 		$itemString = $UpdateData['youTube'].$vId.";"; 
            	}else{
            		$itemString = $vId.";";
            	}
			 
             	if(mysql_query("UPDATE playlist SET youTube='$itemString' WHERE id='$playlist'")){
                 	jsAlert("worked ;)");
             	}
            }
     }
else if($_GET['action'] == "showSingleImage"){
         $element = $_GET['element'];
         $elementName = $_GET['elementName'];
         $file = $_GET['file'];
         //maybe dont works with strange letters etc.
         $title = $_GET['title'];
         $fileClass = new file($file);
        $path = $fileClass->getPath();
        $path = "$path/$title";
        ?>
        <div style="position: absolute; top: 57px; right: 0px; bottom: 100px; left: 0px; overflow: auto;" id="<?=$elementName;?>">
            <img src="./upload/<?=$path;?>" width="100%">
        </div>
    <? }
else if($_GET['action'] == "addGroup"){
        if(proofLogin()){
            
        if(isset($_POST['submit'])){
            $description = $_POST['description'];
            $title = $_POST['title'];
            $privacy = $_POST['privacy'];
            $users = $_POST['users'];
                $groupsClass = new groups();
           	if($groupsClass->createGroup($title, $privacy, $description, $users)){
           		echo"<script>";
				echo"parent.$('.jqPopUp').hide();";
				echo"parent.updateDashbox('group');";
				echo"parent.$('#favTab_Group').load('doit.php?action=showUserGroups');";
				echo"</script>";
				jsAlert("Your Group has been created.");
           	}

        }else{
    ?>
    
         <div class="jqPopUp border-radius transparency" id="addGroup">
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
	                 <td><input type="radio" name="privacy" value="1" style="margin-left:30px;">Public&nbsp;&nbsp;<input type="radio" name="privacy" value="0">Private</td>
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
	        <?php
	        $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$_SESSION[userid]' && request='0'");
	        while($buddylistData = mysql_fetch_array($buddylistSql)) {
	            $blUserSql = mysql_query("SELECT userid, username FROM user WHERE userid='$buddylistData[buddy]'");
	            $blUserData = mysql_fetch_array($blUserSql);
	            if(!empty($buddylistData['alias'])){
	                $username = $buddylistData['alias'];
	            } else{
	            $username = htmlspecialchars($blUserData['username']);
	                }
	            if($i%2 == 0) {
	                $bgcolor="000000";
	
	            } else {    
	                $bgcolor="0f0f0f";
	            }
	            $i++;
	            ?>
	                             <tr bgcolor="#<?=$bgcolor;?>" width="399" height="30">
	                                 <td width="20"><input type="checkbox" name="users[]" value="<?=$blUserData['userid'];?>"></td>
	                                 <td width="40"><?=showUserPicture($blUserData['userid'], 20);?></td>
	                                 <td><?=$blUserData['username'];?></td>
	                             </tr>
	            <?}?> 
	                         </table>
	                     </div>
	                 </td>
	             </tr>
	         </table>
         </div>
         <footer>
         	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').hide();">Back</a></span>
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
     
     <?php
    }}}
else if($_GET['action'] == "showSingleComment"){
        $classComments = new comments();
        if($_GET['type'] == "feed"){
            $classComments->showFeedComments($_GET['itemid']);
        }else{
            $classComments->showComments($_GET['type'], $_GET['itemid']);
        }
    }
else if($_GET['action'] == "showStartMessage"){
            if(empty($_GET['step'])){ ?>
            <script>
                    $("#dashBoard").hide("slow");
                    $(".fenster").hide("slow");
            </script>
            <div class="blueModal border-radius container">
            	<header>
            		Welcome
            	</header>
            	<div class="content">
            		<h2>Thank you for joining universeOS</h2>
	                <p style="margin-top: 20px;">
	                    We try to give you the experience of an operating system without the disadvantage that its bound to a single computer. <br>You joined this project at a very early state, so please keep in mind that this is only the beta version and errors might still be occuring.<br>
	                </p>
	                <h3>In the following three steps you'll get to know more about the project universeOS.</h3>
	            </div>
	            <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=1&noJq=true'); return false" class="btn btn-primary pull-right">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>
	            </footer>
            </div>
            <?php
            }else if($_GET['step'] == "1"){?>
            
            <div class="blueModal border-radius container">
            	<header>
            		Welcome
            	</header>
            	<div class="content">
	            	<h2>
	                    Your Desktop
	                </h2>
	                <span><br>
	                    <h3>The Dock</h3>
	                    The Dock gives you an overview of any activity thats going on in your universeOS.<br>Here you'll find your "User" button including all of your Apps, your "Player", your "logout button" and the search engine.
	                </span>
	                <span><br>
	                    <h3>The Search</h3>
	                    Look for your friends, your favourite writer, a song on Spotify or your favourite Youtube video.<br>Just type in whatever you are looking for!
	                </span>
	                <span><br>
	                    <h3>The "User" button</h3>
	                    Your "User" Button is the gateway to all of your universeOS functions. Here you'll find your Apps including your "Messenger", your "Buddylist", your "Filesystem" and the Reader. Additionally you can keep track of everything thats going on by clicking on your "Feed" or make changes to your profile by clicking on your "Settings".
	                </span>
	                <span><br>
	                    <h3>Apps</h3>
	                    Apps are the most important component of the Universe. They are little programs like the "Reader", the "Feed" or the "Buddylist" that can be opened with the "User" button in your Dock. 
	                </span>
	                </div>
	                <footer>
	                 	<a href="#" onclick="popper('doit.php?action=showStartMessage'); return false" class="btn pull-left">Back</a>
	                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=2'); return false" class="btn btn-primary pull-right">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>
	                </footer>
	               </div>
            
            <?}else if($_GET['step'] == "2"){?>
            <div class="blueModal border-radius">
            	<header>
            		Welcome
            	</header>
            	<div class="content">
	                <h2>
	                    The Filesystem
	                </h2>
	                <p>Enter the Filesystem by clicking your "User" Button.<br></p>
	                <p><br>
	                    <h3>Folders</h3>
	                    You can create folders within the filesystem in your universeOS just like on your own computer.
	                </p>
	                <p><br>
	                    <h3>Elements</h3>
	                    Elements are part of your filesystem, and can be found within you folders. They structure files and links and give you the opportunity to rate, edit or comment on the entire data contained within one element instead of having to do so on each file separately.<br><i><b>For example</b> you could create the Element "My Favorite Artist" and upload all your artist's songs into the element so that you or your friends can choose to comment either on the entire music or on each song individually.</i>
	                </p>
	                <p><br>
	                    <h3>Create Something Big Together</h3>
	                    If you feel like sharing you can decide to open up your files, elements or folders so that everyone can see, download, edit and rate them.<br>This way you can spread, share and increase your knowledge with the entire community. 
	                </p>
	                <p><br>
	                    <h3>Privacy</h3>
	                    Each time you add a folder, an element, a file or a link you can chose to adjust the privacy settings. The preset privacy thereby orientates itself at the superordinated element or folder. You can choose who can see or edit your data: whether it is everyone, particular groups or just you.
	                </p>
                </div>
                <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=1'); return false" class="btn pull-left">Back</a>
                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=3&noJq=true'); return false" class="btn btn-primary pull-right" style="">&nbsp;&nbsp;Next&nbsp;&nbsp;<a>
                </footer>
            </div>  
            <?}else if($_GET['step'] == "3"){
			?>
            <div class="blueModal border-radius">
            	<header>
            		Welcome
            	</header>
            	<div class="content">
                <h2>
                    Buddylist & Chat
                </h2>
	                <p>Open the Buddylist and the Chat by clicking your "User" Button.<br></p>
	                <p><br>
	                    <h3>Buddylist</h3>
	                    The Buddylist gives you an overview of all your buddies. You can add friends to your buddylist by looking them up in the search engine or by accepting open friend requests on your "User" button. Click your buddies' user pictures to open their profiles and check out their information and latest updates. Here you'll have the possibility to write them a message, chat with them or browse their unlocked folders, elements and files.
	                </p>
	                <p><br>
	                    <h3>Chat</h3>
	                     The chat gives you the opportunity to communicate with your buddies in a safe encrypted way. Open up a dialogue window by leftclicking on your buddies' name in the buddylist or reopen an existing conversation by clicking directly on the chat button to review your chat history. To give your conversation extra protection we would recommend you to secure your conversations with a password that you and your conversation partner choose at the beginning of each dialogue.
	                </p>
               </div>
                <footer>
	                <a href="#" onclick="popper('doit.php?action=showStartMessage&step=2'); return false" class="btn pull-left">Back</a>
                	<a href="#" onclick="popper('doit.php?action=showStartMessage&step=4&noJq=true'); return false" class="btn btn-primary pull-right" style="">&nbsp;&nbsp;Next&nbsp;&nbsp;<a>
                </footer>
            </div>
            <?}else if($_GET['step'] == "4"){
                mysql_query("UPDATE user SET startLink='' WHERE userid='".getUser()."'");?>
            <script>
                $("#finalStep").click(function(){
                    $(".blueModal").hide("slow", function(){
                        
                        $("#buddylist").show("slow");
                        $("#filesystem").show("slow");
                        $("#chat").show("slow");
                        $("#feed").show("slow");
                        $("#reader").show("slow");
                        $("#dashBoard").show("slow");
                        initDraggable();
                        
                    });
                });
            </script>
		            <form action="modules/settings/index.php" method="post" target="submitter">
            <div class="blueModal border-radius">
            	<header>
            		Welcome
            	</header>
            	<div class="content">
                <h2>
                    &nbsp;Update your profile
                </h2>
                <div>
                	<?php
                    $dbClass = new db();
                    $AccSetData = $dbClass->select('user', array('userid', getUser()));
                    if($AccSetData['birthdate']){
                    $birth_day = date("d", $AccSetData['birthdate']);
                    $birth_month = date("m", $AccSetData['birthdate']);
                    $birth_year = date("Y", $AccSetData['birthdate']);
                    }
                 ?>
		                <div class="controls">
		                    
		                    <div class="controls controlls-row">
		                        <span class="span2">Name</span>
		                        <input type="text" name="AccSetRealname" class="span3" value="<?=$AccSetData['realname'];?>" placeholder="Your Name">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">City</span>
		                        <input type="text" name="place" class="span3" value="<?=$AccSetData['place'];?>" placeholder="Metropolis">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">Hometown</span>
		                        <input type="text" name="home" class="span3" value="<?=$AccSetData['home'];?>" placeholder="Los Santos">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">Birthdate</span>
		                        <input type="text" name="birth_day" class="span1" value="<?=$birth_day;?>" placeholder="DD">
		                        <input type="text" name="birth_month" class="span1" value="<?=$birth_month;?>" placeholder="MM">
		                        <input type="text" name="birth_year" class="span1" value="<?=$birth_year;?>" placeholder="YYYY">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">School</span>
		                        <input type="text" name="school1" class="span3" value="<?=$AccSetData['school1'];?>" placeholder="Hogwarts">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">University</span>
		                        <input type="text" name="university1" class="span3" value="<?=$AccSetData['university1'];?>" placeholder="Oaksterdam University">
		                    </div>
		                    <div class="controls controlls-row">
		                        <span class="span2">Work</span>
		                        <input type="text" name="work" class="span3" value="<?=$AccSetData['employer'];?>" placeholder="Charlie's Chocolate Factory">
		                    </div>
		                </div>
	                </div>
	               </div>
	               <footer>
				       <input type="submit" id="finalStep" name="AccSetSubmit" value="enter the universe" class="btn btn-success pull-right">
				       <a href="#" class="btn pull-left" onclick="javascript: popper('doit.php?action=showStartMessage&step=3'); return false">back</a>
	               </footer>
            </div>
		       </form>
            <?}
        }
else if($_GET['action'] == "updatePasswordAndCreateSignatures"){
        	//called by guest.js->updatePasswordAndCreateSignatures()
        	
			
			
			
			
        }
else if($_GET['action'] == "showSingleRssFeed"){
            $rssSql = mysql_query("SELECT link FROM links WHERE id='".mysql_real_escape_string($_GET['id'])."'");
            $rssData = mysql_fetch_array($rssSql);
            
            echo"<div style=\"padding: 20px;\">";
            $rss = new rss();
            echo $rss->getRssfeed("$rssData[link]","$linkData[title]","auto",10,3);
            echo"</div>";
        }
else if($_GET['action'] == "deleteFromPersonals"){
            $personalSql = mysql_query("SELECT owner FROM personalEvents WHERE id='".mysql_real_escape_string($_GET['id'])."'");
            $personalData = mysql_fetch_array($personalSql);
            if($personalData['owner'] == $_SESSION['userid']){
                mysql_query("DELETE FROM personalEvents WHERE id='".mysql_real_escape_string($_GET['id'])."'");
            }
        }
else if($_GET['action'] == "deleteFromPlaylist"){
             //deletes a single file, link or youtube icon from a playlist
             $type = save($_GET['type']);
             $itemId = $_GET['itemId'];
             $playlist = save($_GET['playlist']);
             jsAlert("$type-$itemId-$playlist");
             $dbClass = new db();
             $checkPlaylistData = $dbClass->select('playlist', array('id', $playlist));
             if($checkPlaylistData['user'] == "$_SESSION[userid]"){
                 if($type == "file"){
                     $files = explode(";", $checkPlaylistData['files']);
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
                     $links = explode(";", $checkPlaylistData['links']);
                     foreach($links as $link){
                         if($link == "$itemId"){
                         }else{
                             $newLinks[] = $link; 
                         }
                         
                     }
                         $saveLinks = implode(";", $newLinks);
                         mysql_query("UPDATE playlist SET links='$saveLinks' WHERE id='$playlist'");
                     
                 }elseif($type == "youTube"){
                     $youTubes = explode(";", $checkPlaylistData['youTube']);
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
             <?php
             }
        }
else if($_GET['action'] == "deleteItem"){
            if(proofLogin()){
            $type = $_GET['type'];
            $itemId = $_GET['itemId'];
            
            if($type == "feed"){
                $dbClass = new db();
                $feedCheckData = $dbClass->select('feed', array('id', $itemId));
                if(authorize($feedCheckData['privacy'], "edit", $feedCheckData['author'])){
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
                if(authorize($commentData['privacy'], "edit", $commentData['author'])){
                    mysql_query("DELETE FROM comments WHERE id='$itemId'");
                    ?>
                    <script>
                    parent.$('.commentBox<?=$itemId;?>').remove();
                    </script>
                    <?
                }
                if($commentData['type'] == "profile" AND $commentData['typeid'] == "$_SESSION[userid]"){
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
                if(authorize($checkPlaylistData['privacy'], "edit", $checkPlaylistData['user'])){
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
                if(authorize($checkFolderData['privacy'], "delete", $checkFolderData['creator'])){
                    $classFolder = new folder($itemId);
                    $classFolder->delete();
					?>
               	    <script>
                        parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?folder=<?=$checkFolderData['folder'];?>&reload=1'));
                                
                	</script>
                	<?
                }
                
                
            }else if($type == "element"){
                $checkElementSql = mysql_query("SELECT folder, privacy, author FROM elements WHERE id='$itemId'");
                $checkElementData = mysql_fetch_array($checkElementSql);
                if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){
                        $element = new element($itemId);
                   	$element->delete();
                        jsAlert("The Element has been deleted");
                    ?>
                        <script>
                        parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?reload=1&folder=<?=$checkElementData['folder'];?>'));
                                
                        </script>
                    <?
                }
                
                
            }else if($type == "file"){
                $fileId = $itemId;
                $dbClass = new db();
                $fileData = $dbClass->select('files', array('id', $fileId));
                if(authorize($fileData['privacy'], "edit", $fileData['owner'])){
                    $fileClass = new file($fileId);
                    if($fileClass->delete()){
                            $fileElementSql = mysql_query("SELECT id, title FROM elements WHERE id='".$fileData['folder']."'");
                            $fileElementData = mysql_fetch_array($fileElementSql);
                            jsAlert("File has been deleted :( ");
                                ?>
                                    <script>
                                        
                                        parent.filesystem.tabs.updateTabContent('<?=$_POST['tabTitle'];?>' ,parent.gui.loadPage('modules/filesystem/showElement.php?element=<?=$fileElementData['id'];?>&reload=1'));
                                
                                    </script>
                                <?
                        
                    }else{
                        jsAlert('error');
                    }
				}
            }else if($type == "internLink"){
                $dbClass = new db();
                $checkInternLinkData = $dbClass->select('internLinks', array('id', $itemId));
                
                    if($checkInternLinkData['type'] == "folder"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy, creator FROM folders WHERE id='".$checkInternLinkData['typeId']."'"));

                        $user = $shortCutItemData['creator'];

                    }else if($checkInternLinkData['type'] == "element"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, creator FROM elements WHERE id='".$checkInternLinkData['typeId']."'"));
                        $user = $shortCutItemData['creator'];
                    }else if($checkInternLinkData['type'] == "file"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type, owner FROM files WHERE id='".$checkInternLinkData['typeId']."'"));
                        $user = $shortCutItemData['owner'];

                    }else if($checkInternLinkData['type'] == "link"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type, author FROM links WHERE id='$checkInternLinkData[typeId]'"));
                        $user = $shortCutItemData['author'];

                    }

                    if(authorize($shortCutItemData['privacy'], "edit", $user)){
                        $shortcutClass = new shortcut();
                        if($shortcutClass->delete($itemId)){
                            jsAlert("The Shortcut has been deleted");
                            
                                if($checkInternLinkData['parentType'] == "folder"){
                                    ?>
                                    <script>
                                    parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?folder=<?=$checkInternLinkData['parentId'];?>&reload=1'));
                                
                                    </script>
                                    <?
                                }else if($checkInternLinkData['parentType'] == "element"){
                                    $parentData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysq_real_escape_string($checkInternLinkData['parentId'])."'"));
                                    ?>
                                    <script>
                                        
                                        parent.filesystem.tabs.updateTabContent('<?=substr($parentData['title'], 0, 10);?>' ,parent.gui.loadPage('modules/filesystem/showElement.php?element=<?=$checkInternLinkData['parentId'];?>&reload=1'));
                                
                                    </script>
                                    <?
                                }
                        }

                    }
                
            }
                
            }
        }
else if($_GET['action'] == "loadPrivacySettings"){
        	//is used bei js privacy.load to load privacy selection with privacy = $_POST['val'] into DOM
		$editable =  ($_POST['editable'] === 'true'); //str to bool
                        
                $privacyClass = new privacy($_POST['val']);
          	$privacyClass->showPrivacySettings($editable);
        	
        }
else if($_GET['action'] == "editItem"){
                if(proofLogin()){
                $itemId = save($_GET['itemId']);
                $type = save($_GET['type']);
                if(isset($_POST['submit'])){
                	
					
					//folders
                    if($type == "folder"){
                        $dbClass = new db();
                        $checkFolderData = $dbClass->select('folders', array('id', $itemId));
                        if(authorize($checkFolderData['privacy'], "edit", $checkFolderData['creator'])){
                            $folderClass = new folder($checkFolderData['folder']);
                            $parentFolderPath = $folderClass->getPath();
							
							//check if folder exists
							if (!file_exists("$parentFolderPath".urldecode($_POST['name']))) {
								//rename folder
	                            if(rename("$parentFolderPath$checkFolderData[name]", "$parentFolderPath".urldecode(save($_POST['name'])))){
	                            	//update db
	                            	mysql_query("UPDATE folders SET name='".save($_POST['name'])."' WHERE id='".$itemId."'");
		                            jsAlert("Saved :)");
									
									//close modal, updatefilebrowser.
									echo"<script>";
									echo"parent.$('.jqPopUp').slideUp();";
                                                                        echo"parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?folder=".$checkInternLinkData['parentId']."&reload=1'));";
									echo"</script>";
								
								}
							}else{
								jsAlert("A folder with this title already exists");
							}
                        }
						
					//elements
                    }else if($type == "element"){
                        $dbClass = new db();
                        $checkElementData = $dbClass->select('elements', array('id', $itemId));
                        if($checkElementData['author'] == getUser()){
                            mysql_query("UPDATE elements SET title='".save($_POST['title'])."', type='".save($_POST['type'])."', creator='".save($_POST['creator'])."', name='".save($_POST['name'])."', year='".save($_POST['year'])."', originalTitle='".save($_POST['originalTitle'])."' WHERE id='$itemId'");
                            jsAlert("Saved :)");
							echo"<script>";
							echo"parent.$('.jqPopUp').slideUp();";
                                                                        echo"parent.filesystem.tabs.updateTabContent(1 ,parent.gui.loadPage('modules/filesystem/fileBrowser.php?folder=".$checkElementData['folder']."&reload=1'));";
									echo"</script>";
                        }
                    }else if($type == "file"){


					//links
                    }else if($type == "link"){
                    	$dbClass = new db();
                        $checkLinkData = $dbClass->select('links', array('id', $itemId));
                    	
                        if(authorize($checkLinkData['privacy'], "edit", $checkLinkData['creator'])){
                        			
                            	$privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                        		
                        		mysql_query("UPDATE links SET title='".save($_POST['title'])."', link='".save($_POST['link'])."', type='".save($_POST['type'])."', privacy='$privacy' WHERE id='$itemId'");
								jsAlert("Saved :)");
								echo"<script>parent.$('.jqPopUp').slideUp();</script>";
                        	
                         }else{
                         	
                        		jsAlert("not authorized");
                         }
						 
					//playlist
                    }else if($type == "playlist"){
                        $checkPlaylistSql = mysql_query("SELECT user FROM playlist WHERE id='$itemId'");
                        $checkPlaylistData = mysql_fetch_array($checkPlaylistSql);
                        if($checkPlaylistData['user'] == getUser()){
                            
                            
                            
                            //set privacy
                            $customShow = $_POST['privacyCustomSee'];
                            $customEdit = $_POST['privacyCustomEdit'];

                            $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                            
                            mysql_query("UPDATE playlist SET title='".save($_POST['title'])."', privacy='$privacy'  WHERE id='$itemId'");
                            jsAlert("Saved :)");
            				echo"parent.updateDashbox('playlist');";
							echo"<script>parent.$('.jqPopUp').slideUp();</script>";
                        }

                    }
                    
                }
                
                //folder
                if($type == "folder"){
                    $editSQL = mysql_query("SELECT name FROM folders WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    
                    $title = $editData['name'];
                    $headTitle = "folder $title";
                    $edit = "
                    <tr>
                        <td>Name:</td>
                        <td><input type=\"text\" name=\"name\" value=\"$title\"></td>
                    </tr>
                        ";
                    
                }else if($type == "element"){
                    $editSQL = mysql_query("SELECT *  FROM elements WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $title = $editData['title'];
                    $headTitle = "element $title";
                    $guiClass = new gui();
                    $edit = "
                    <tr>
                        <td>Name:</td>
                        <td><input type=\"text\" name=\"title\" value=\"$title\"></td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td>
                        <select name=\"type\">
                            <option value=\"".$editData['type']."\">".$editData['type']."</option>
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
                        <td><input type=\"text\" name=\"creator\" value=\"".$editData['creator']."\"></td>
                    </tr>
                    <tr>
                        <td>title:</td>
                        <td><input type=\"text\" name=\"name\" value=\"".$editData['name']."\"></td>
                    </tr>
                    <tr>
                        <td>year:</td>
                        <td><input type=\"text\" name=\"year\" value=\"".$editData['year']."\"></td>
                    </tr>
	                <tr>
	                    <td>Original Title:&nbsp;</td>
	                    <td><input type=\"text\" name=\"originalTitle\" value=\"".$editData['originalTitle']."\"></td>
	                </tr>
	                <tr>
	                    <td>Language:</td>
	                    <td>
	                    	".$guiClass->showLanguageDropdown($editData['language'])."
	                    </td>
	                </tr>
                        ";
                }else if($type == "file"){
                    $editSQL = mysql_query("SELECT title FROM files WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $headTitle = "file";
                }else if($type == "link"){
                    $editSQL = mysql_query("SELECT type, title, link, privacy FROM links WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    $title = $editData['title'];
                    $headTitle = "link $title";
					
					//the type select needs to show the value
					//whis is defined in the db
					switch($editData['type']){
						case 'link':
							$selected['link'] = 'selected="selected"';
							break;
						case 'RSS':
							$selected['RSS'] = 'selected="selected"';
							break;
						case 'youTube':
							$selected['youTube'] = 'selected="selected"';
							break;
						case 'soundcloud':
							$selected['soundcloud'] = 'selected="selected"';
							break;
						case 'file':
							$selected['file'] = 'selected="selected"';
							break;
						case 'other':
							$selected['other'] = 'selected="selected"';
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
                                  <option value=\"soundcloud\" $selected[soundcloud]>Soundcloud</option>
                                  <option value=\"file\" $selected[file]>File</option>
                                  <option value=\"other\" $selected[other]>Other</option>
                              </select>
                        </td>
                    </tr>
                        ";
                	
                    $privacy = $editData['privacy'];
					
                }else if($type == "playlist"){
                    $editSQL = mysql_query("SELECT title, privacy FROM playlist WHERE id='$itemId'");
                    $editData = mysql_fetch_array($editSQL);
                    
                    $title = $editData['title'];
                    
                    $headTitle = "playlist $editData[title]";
                    
                    $edit = "
                    <tr>
                        <td style=\"vertical-align: middle\">Title:</td>
                        <td><input type=\"text\" name=\"title\" value=\"$title\"></td>
                    </tr>
                        ";
                    $privacy = $editData['privacy'];
                    
					$back = "";
                    
                    $delete = "<a href=\"doit.php?action=deleteItem&type=playlist&itemId=$itemId\" target=\"submitter\" class=\"btn btn-danger\" onclick=\"$('#editItem').hide();\"><i class=\"icon-remove icon-white\"></i>&nbsp;delete</a>";
                }
                ?>
                    <form action="doit.php?action=editItem&type=<?=$_GET['type'];?>&itemId=<?=$_GET['itemId'];?>" method="post" target="submitter">
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
                                    $privacyClass = new privacy($privacy);
                                    $privacyClass->showPrivacySettings();
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
            }
else if($_GET['action'] == "shareItem"){
                ?>
                                <div class="jqPopUp border-radius transparency" id="addFolder">
                                <a class="jqClose" id="closeFolder">X</a>
                                <header>Share Item</header>
                                <div class="jqContent">
                                
                                	
                                </div>
	                            <footer>
	                            	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	                            </footer>
                                </div>
                <script>
                    $(".jqClose").click(function(){
                        $('.jqPopUp').slideUp();
                    });
                </script>
         
		<?
            }
else if($_GET['action'] == "showItemThumb"){ 
                $type = $_GET['type'];
                $itemId = $_GET['itemId'];
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
                                $privacyClass = new privacy();
                                $privacyClass->showPrivacySettings();
                                ?>
                                
                                
                                
                            </div>
                        </div>
                </div>
            <? 
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            }
else if($_GET['action'] == "validateTempFile"){
    $classFile = new file($_POST['fileid']);
				 if($classFile->validateTempFile($privacy))
				 	echo'true';
				 else
				 	echo'false';
				
            }
            
            
            
            
            
            
            
            
else if($_GET['action'] == "submitUploader"){
			//handler for form submit in upload.php
			//adds privacy and removes temp status
			//from temp files
			
            	 
				 
			    //set privacy
			    $customShow = $_POST['privacyCustomSee'];
			    $customEdit = $_POST['privacyCustomEdit'];
			    
			    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
				
				$files = $_POST['uploadedFiles'];
				$successfullUploadedFiles = 0;
				foreach($files AS $file){
					$woff .= $file;
                                        $fileClass = new file($file);
					if($fileClass->validateTempFile($privacy)){
						$successfullUploadedFiles++;
					}else
						$filesWithError[] = $file; //add fileid to error list
				}
				echo'<script>parent.deleteTab("Upload File");</script>';
				jsAlert("The files have successfully been added to the Element.");
			
				
			
				
            }

            
            
            
            
            
            
            
            
            
            else if($_GET['action'] == "manageUpload"){
            	
            	switch($_GET['type']){
            		
					
					case 'uploadTemp':
						
						//upload temp_file
                                                $file = $_FILES['Filedata'];
						
						$user = getUser();
                                                $filesClass = new files();
						$id = $filesClass->uploadTempfile($file, $_POST['element'], '', $privacy, $user);
                                                
						$li = "<li data-fileid=\"$id\">     <img src=\"gfx/icons/fileIcons/".$filesClass->getFileIcon($filesClass->getMime($file['name']))."\" height=\"16\">     ".$file['name']."      <input type=\"hidden\" name=\"uploadedFiles[]\" value=\"$id\">    <i class=\"icon-remove pointer pull-right\" onclick=\"$(this).parent(\\'li\\').remove()\"></i></li>";
						
						//add file to filelist in the uploader
						echo'$(".tempFilelist").append(\''.$li.'\');';
						
						//echo'</script>';
						break;
                                                
                                                
                                                
                                                
					case 'validateUpload':
						
						break;
            		
            	}
            }

            
            
            
            else if($_GET['action'] == "feedUpload"){
            if(proofLogin()){
            if(empty($_FILES['feedFile']['tmp_name'])){
                $error = "please select a file";
            }
            if(true){
            $dbClass = new db();
            $userData = $dbClass->select('user', array('userid', getUser()));
            
            $element = $userData['myFiles'];
            $folder = $userData['homefolder'];
            
            //privacy
            $privacy = "p";
            $user = getUser();

            $file = $_FILES['Filedata'];
            addFile($file, $element, $folder, $privacy, $user);
            }}
            }

            
            
            
            
            
            
            
            
            
            
            else if($_GET['action'] == "reportFile"){
    //@del
    
                if(isset($_POST['submit'])){
                    $timstamp = time();
                    jsAlert("A report message has been send");
                    mysql_query("INSERT INTO `adminMessages` (`timestamp` ,`author` ,`category` ,`type` ,`message`) VALUES ('$time', '$_SESSION[userid]', '1', '$_POST[reason]', '$_POST[message]');");
                }
                ?>
            <form action="doit.php?action=reportFile&fileId=<?=$_GET['fileId'];?>" method="post" target="submitter">
                <div class="jqPopUp border-radius transparency" id="reportFile" style="display: block">
                    <a style="position: absolute; top: 10px; right: 10px; color: #FFF;" id="closereportFile">X</a>  
                    <header>
                        Report File
                    </header>
                    <div class="jqContent">
                        <table>
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
                                    <textarea name="message" style="width: 300px; height: 120px;"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
	                <footer>
	                	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
	                	<span class="pull-right"><input type="submit" value="save" name="submit" id="reportFileSubmit" class="button"></span>
	                </footer>
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
            }
else if($_GET['action'] == "reportBug"){
                if(isset($_POST['submit'])){
                    $time=time();
                    if(mysql_query("INSERT INTO `adminMessages` (`timestamp` ,`author` ,`category` ,`type` ,`message`) VALUES (".time().",'$_SESSION[userid]', '2', 'Bug', '$_POST[message]');")){  
                    jsAlert("Thanks dude! The bug report has been send to our admins.");
                    }
                }?>
                <form action="doit.php?action=reportBug&fileId=<?=$_GET['fileId'];?>" onsubmit="$('.jqPopUp').slideUp();" method="post" target="submitter">
                    <div class="jqPopUp border-radius transparency" id="reportBug" style="display: block">
                        <a class="jqClose" id="closereportBug">X</a>  
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
                            </table>
                        </div>
		                <footer>
		                	<span class="pull-left"><a class="btn" onclick="$('.jqPopUp').slideUp();">back</a></span>
		                	<span class="pull-right"><input type="submit" value="send" name="submit" id="reportBugSubmit" class="btn btn-success"></span>
		                </footer>
                    </div>
                </form>
                <script>
                    $(".jqClose").click(function () {
                    $('.jqPopUp').slideUp();
                    });
                </script>
            <?}
else if($_GET['action'] == "deleteLink"){
                
                $linkId = save($_GET['linkId']);
                if(proofLogin()){
                    $linkClass = new link();
                    if($linkClass->deleteLink($linkId)){
                    		
                    	echo"<script>parent.$('.link_$linkId').hide();</script>";
                        jsAlert("The Link has been deleted");
                    }
                }
            }
else if($_GET['action'] == "protectFileSystemItems"){
    $item = new item($_GET['type'], $_GET['itemId']);
            	$item->protectFilesystemItem($_GET['type'], $_GET['itemId']);
				jsAlert("This Item can not be edited anymore.");
            }
else if($_GET['action'] == "removeProtectionFromFileSystemItems"){
            	$item = new item($_GET['type'], $_GET['itemId']);
            	$item->removeProtection($_GET['type'], $_GET['itemId']);
				jsAlert("This Item can be edited again.");
            }
else if($_GET['action'] == "makeFileSystemItemUndeletable"){
            	
            	$item = new item($_GET['type'], $_GET['itemId']);
            	$item->makeUndeletable($_GET['type'], $_GET['itemId']);
				jsAlert("This Item can not be deleted anymore.");
            }
else if($_GET['action'] == "makeFileSystemItemDeletable"){
            	
            	$item = new item($_GET['type'], $_GET['itemId']);
            	$item->makeDeletable($_GET['type'], $_GET['itemId']);
				jsAlert("This Item can be deleted again.");
            }
            
            
// ajax stuff
// ajax stuff
// ajax stuff
            
else if($_GET['action'] == "mousePop"){
                $type = $_POST['type'];
                $id = $_POST['id'];
                $html = $_POST["html"];
                switch($type){
                    case 'none':
                        $text = $html;
                    break;
                    case 'youTube':
                        $classYoutube = new youtube($id);
                        $title = $classYoutube->getTitle();
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
            <?}
else if($_GET['action'] == "writeUff"){
    
    error_reporting(E_ALL);
                $id = save($_POST['id']);
                $input = $_POST['input'];
                $uff = new uff($id);
                $uff->write($input);
                
            }
else if($_GET['action'] == "removeUFFcookie"){
                $id = save($_POST['id']);
                removeUFFcookie($id);
            }
else if($_GET['action'] == "logout"){
            	if(!empty($_SESSION['userid'])){
            		
                            unset($_SESSION['userid']);
                            unset($_SESSION['personalFeed']);
				session_unset();
    			jsAlert( "good bye");
				?>
				<script>
	    							parent.localStorage.currentUser_userid = 'userid';
	    							parent.localStorage.currentUser_username = '';
	    							parent.localStorage.currentUser_passwordHashMD5 = '';
	    							top.window.location.href='http://amnesty.org';</script>
				<?
				}
            	
            }
else if($_GET['action'] == "tester"){
    echo 'test';
    error_reporting(E_ALL);
    include('inc/classes/handlers/youtube/class.php');
    $yt =  new youtube_handler();
    echo var_dump($yt->query('test', 0, 100));
        
}
?>