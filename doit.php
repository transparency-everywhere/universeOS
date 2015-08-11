<?PHP
session_start();
include("inc/config.php");
include("inc/functions.php");
$time = time();

if($_GET['action'] == "addToNotSuggestList"){
                $buddylistClass = new buddylist();
		$buddylistClass->addToNotSuggestList($_GET['user']);
		echo"<script>parent.$('#buddySuggestions').load('doit.php?action=showBuddySuggestList');</script>";
		 
}
else if($_GET['action'] == "showSingleComment"){
        $classComments = new comments();
        if($_GET['type'] == "feed"){
            $classComments->showFeedComments($_GET['itemid']);
        }else{
            $classComments->showComments($_GET['type'], $_GET['itemid']);
        }
    }
else if($_GET['action'] == "showBuddySuggestList"){
     	$false = false;
		showBuddySuggestions($false);
}
     
     
else if($_GET['action'] == "protectFileSystemItems"){
    $item = new item($_GET['type'], $_GET['itemId']);
            	$item->protect($_GET['type'], $_GET['itemId']);
				jsAlert("This Item can not be edited anymore.");
            }
else if($_GET['action'] == "removeProtectionFromFileSystemItems"){
    error_reporting(E_ALL);
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
        
        
        
else if($_GET['action'] == "writeUff"){
    
    error_reporting(E_ALL);
                $id = save($_POST['id']);
                $input = $_POST['input'];
                $uff = new uff($id);
                $uff->write($input);
                
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
       
        
        //mysql_query("UPDATE user SET backgroundImg='$img' WHERE userid='$_SESSION[userid]'");
     }}
     
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
?>