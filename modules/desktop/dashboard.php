<?
class dashBoard{
	function __construct() {
		$userid = getUser();
		$userSql = mysql_query("SELECT username FROM user WHERE userid='$userid'");
		$userData = mysql_fetch_array($userSql);
	}
	
	function showDashBox($title, $content, $footer=NULL){
		
		$output .= "<div class=\"dashBox\">";
			$output .= "<a class=\"dashClose\"></a>";
			$output .= "<header>$title</header>";
			$output .= "<div class=\"content\">$content</div>";
			if(!empty($footer)){
			$output .= "<footer>$footer</footer>";
			}
		$output .= "</div>";
		return $output;
	}
	
	function showWelcomeBox(){
		$title = "Welcome";
		$content = showUserPicture($message[sender],07,false,true)." Hey <a href=\"#\" onclick=\"showProfile('$userid')\">$userData[username]</a>,<br>good to see you!";
		$content .= "<div>";
		$content .= "<div class=\"listContainer\">";
		$content .= "<ul class=\"list messageList\" id=\"dockMenuSystemAlerts\"></ul>";
		$content .= "<header>System</header>";
		$content .= "</div>";
		$content .= "<div class=\"listContainer\">";
		$content .= "<ul class=\"list messageList\" id=\"dockMenuBuddyAlerts\"></ul>";
		$content .= "<header>Buddies</header>";
		$content .= "</div>";
		$content .= "</div>";

		$output = $this->showDashBox($title, $content);
		return $output;
	}
	
	function showAppBox(){
		
		$title = "Your Apps";
		
		$content = "<ul class=\"appList content\" style=\"padding:5px; width:220px;\">";
	    	$content .= "<li onclick=\"toggleApplication('feed')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/feed.png\" border=\"0\" height=\"16\">Feed</li>";
			$content .= "<li onclick=\"toggleApplication('filesystem')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/filesystem.png\" border=\"0\" height=\"16\">Filesystem</li>";
	 		$content .= "<li onclick=\"javascript: toggleApplication('reader')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/viewer.png\" border=\"0\" height=\"16\">Reader</li>";
	   		$content .= "<li onclick=\"javascript: toggleApplication('buddylist')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Buddylist</li>";
	    	$content .= "<li onclick=\"javascript: toggleApplication('chat')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Chat</li>";
	    	$content .= "<li onclick=\"javascript: showModuleSettings();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/settings.png\" border=\"0\" height=\"16\">Settings</li>";
	    $content .= "</ul>";
		
		$output = $this->showDashBox($title, $content);
		
		return $output;
		
	}
	function showGroupBox(){
		
		//groups
		$groups = getGroups();
		
		$title = "Your Groups";
		
		$output .= "<ul class=\"content\">";
			$i = 0;
			foreach($groups AS $group){
				$output .="<li>";
					$output .="<span class=\"marginRight\">";
						$output .="<img src=\"./gfx/icons/group.png\" height=\"14\">";
					$output .="</span>";
					$output .="<span>";
						$output .="<a href=\"#\" onclick=\"showGroup('$group');\">";
						$output .= getGroupName($group);
						$output .="</a>";
					$output .="</span>";
				$output .="</li>";
				$i++;
			}
			if($i == 0){
				$output .="<li>";
				$output .="You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .="</li>";
			}
			
		$output .= "</ul>";
		
		$footer = "<a href=\"#addGroup\" onclick=\"popper('doit.php?action=addGroup')\" title=\"Create a new Group\"><i class=\"icon icon-plus\"></i></a>";
		
		$output = $this->showDashBox($title, $output, $footer);
		
		return $output;
		
	}
	function showPlaylistBox(){
		
			//playlists
			$playlists = getPlaylists();
			
			$title = "your Playlists";
			$i = 0;
			$output .= "<ul class=\"content\">";
			foreach($playlists AS $playlist){
				$output .= "<li>";
					$output .= "<span class=\"marginRight\">";
						$output .= "<img src=\"./gfx/icons/playlist.png\" height=\"14\">";
					$output .= "</span>";
					$output .= "<span>";
						$output .= "<a href=\"#\" onclick=\"showPlaylist('$playlist');\">";
						$output .=  getPlaylistTitle($playlist);
						$output .= "</a>";
					$output .= "</span>";
				$output .= "</li>";
				$i++;
			}
			if($i == 0){
				$output .= "<li>";
				$output .= "You don't have any playlists so far.";
				$output .= "<li>";
			}
			$output .= "</ul>";
			
		$footer = "<a href=\"#addPlaylist\" onclick=\"popper('doit.php?action=addPlaylist')\" title=\"Create a new Playlist\"><i class=\"icon icon-plus\"></i></a>";
			
		
		$output = $this->showDashBox($title, $output, $footer);
		
		$footer = "";
		
		return $output;
		
	}
	function showMessageBox(){
			//unseenMessages
			$lastMessages = getLastMessages();
		
			$title = "show Messages";
			
			$output .= "<ul id=\"messageList\">";
			$i = 0;
			foreach($lastMessages AS $message){
				
				$class = "";
				if($message['seen'] == "0"){
					$class = "unseen";
				}
				
				$output .= "<li class=\"$class\" style=\"clear:both;\">";
					$output .=  "<span>";
					$output .=  showUserPicture($message[sender],07,'',true);
					$output .=  "</span>";
					$output .=  "<span>";
					$output .=  "$message[senderUsername]";
					$output .=  "</span>";
					//$output .=  "<span>";
					//$output .=  universeTime($message[timestamp]);
					//$output .=  "</span>";
					$output .=  "<span>";
					$output .=  "<a href=\"#\" onclick=\"openChatDialoge('$message[senderUsername]');\">";
					$output .=  substr($message['text'], 0, 15);
					$output .=  "</a>";
					$output .=  "</span>";
				$output .= "</li>";
				$i++;
			}
			if($i == 0){
				$output .= "<li>";
				$output .= "You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .= "</li>";
			}
			
			$output .= "</ul>";
		
			$output = $this->showDashBox($title, $output);
			
			return $output;
		
	}
	function showTaskBox(){
		$title = "yourTasks";
		
		return $this->showDashBox($title, $output);
	}
	
}


		$userid = getUser();
		$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
		$userData = mysql_fetch_array($userSql);




//fav, //playlists, //groups, //files

$dashboard = new dashBoard();
?>
<script>
$(document).ready(function(){
	$('.dashBox .dashClose').click(function(){
		$(this).parent('.dashBox').slideUp();
	});	
});
</script>
<div id="dashBoard">
	<?php
	echo $dashboard->showWelcomeBox();
	
	echo $dashboard->showAppBox();
	
	echo $dashboard->showGroupBox();
	
	echo $dashboard->showPlaylistBox();
	
	echo $dashboard->showMessageBox();
	?>
	
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Files</header>
		<div class="content">
		<?php
		
			                  //show folders and elements
			                  $folderQuery = "WHERE creator='$userid' ORDER BY timestamp DESC";
			                  $elementQuery = "WHERE author='$userid' ORDER BY timestamp DESC";
			                  showFileBrowser($folder, "$folderQuery", "$elementQuery");
			                  
			                  //show files
			                  $fileQuery = "owner='$userid' ORDER BY timestamp DESC";
			                  echo'<table class="fileTable">';
			                  showFileList('', $fileQuery);
			                  echo"</table>";
		?>
		</div>
		<footer>
			<a href="#uploadFile" onclick="openUploadTab();" title="Upload a File"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Fav</header>
		<div class="content">
        <?php
		 showFav($userid);
		?>
		</div>
	</div>
	<?php
		echo $dashboard->showTaskBox();
	?>
</div>