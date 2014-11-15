<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class dashBoard{
	public $userid;
	public $userdata;
	
	function __construct() {

		$user = getUser();
      	$this->userid = $user;
		$this->userdata = mysql_fetch_array(mysql_query("SELECT username FROM user WHERE userid='$user'"));

	}
	
	function showDashBox($title, $content, $footer=NULL, $id=NULL, $grid=true){
                $output = '';
		if($grid){
			$output .= "<div class=\"dashBox\" id=\"$id"."Box\">";
		}
		
			$output .= "<a class=\"dashClose\"></a>";
			$output .= "<header>$title</header>";
		
			$output .= "<div class=\"content\">$content</div>";
			
			if(!empty($footer)){
			$output .= "<footer>$footer</footer>";
			}
			
		if($grid){
			$output .= "</div>";
		}
		return $output;
	}
	
	function showWelcomeBox($grid=true){
			
		$userData = $this->userdata;
		
		$title = "Welcome";
		$content = str_replace('\\', '' , showUserPicture($this->userid,13,false,true))." Hey <a href=\"#\" onclick=\"showProfile('$this->userid')\">$userData[username]</a>,<br>good to see you!";
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

		$output = $this->showDashBox($title, $content,"", "buddy", $grid);
		return $output;
	}
	
	function showAppBox($grid=true){
		
		$title = "Your Apps";
		
		$content = "<ul class=\"appList\">";
	    	$content .= "<li onclick=\"toggleApplication('feed')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/feed.png\" border=\"0\" height=\"16\">Feed</li>";
	    	$content .= "<li onclick=\"calendar.show();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/feed.png\" border=\"0\" height=\"16\">Calendar</li>";
			$content .= "<li onclick=\"toggleApplication('filesystem')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/filesystem.png\" border=\"0\" height=\"16\">Filesystem</li>";
	 		$content .= "<li onclick=\"javascript: toggleApplication('reader')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/viewer.png\" border=\"0\" height=\"16\">Reader</li>";
	   		$content .= "<li onclick=\"javascript: toggleApplication('buddylist')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Buddylist</li>";
	    	$content .= "<li onclick=\"javascript: toggleApplication('chat')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Chat</li>";
	    	$content .= "<li onclick=\"javascript: standardModules.showSettings();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/settings.png\" border=\"0\" height=\"16\">Settings</li>";
	    $content .= "</ul>";
		
		$output = $this->showDashBox($title, $content," ", "app", $grid);
		
		return $output;
		
	}
	function showGroupBox($grid=true){
		
		//groups
                $groupsClass = new groups();
		$groups = $groupsClass->getGroups();
		$output = '';
		$title = "Your Groups";
		
		if(count($groups) == 0){
			
				$output .='<p style="font-size:15pt;padding: 5px; padding-top: 12px;">';
				$output .="You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .="</p>";
		}else{
			
			$output .= "<ul class=\"\">";
				foreach($groups AS $group){
					$output .="<li>";
						$output .="<span class=\"marginRight\">";
							$output .="<img src=\"./gfx/icons/group.png\" height=\"14\">";
						$output .="</span>";
						$output .="<span>";
							$output .="<a href=\"#\" onclick=\"showGroup('$group');\">";
							$output .= $groupsClass->getGroupName($group);
							$output .="</a>";
						$output .="</span>";
					$output .="</li>";
					$i++;
				}
				
			$output .= "</ul>";
		}
		
		$footer = "<a href=\"#addGroup\" onclick=\"popper('doit.php?action=addGroup')\" title=\"Create a new Group\"><i class=\"icon icon-plus\"></i></a>";
		
		$output = $this->showDashBox($title, $output, $footer, "group", $grid);
		
		return $output;
		
	}
	function showPlaylistBox($grid=true){
		$output = '';
                //playlists
                $playlistClass = new playlists();
                $playlists = $playlistClass->getPlaylists();

                $title = "Your Playlists";
                if(count($playlists) == 0){

                        $output .= '<p style="padding: 5px; padding-top: 12px;">';
                        $output .= 'You don\'t have any playlists so far.';
                        $output .= '</p>';

                }else{

                        $output .= "<ul>";
                                foreach($playlists AS $playlist){
                                        $output .= "<li>";
                                                $output .= "<span class=\"marginRight\">";
                                                        $output .= "<img src=\"./gfx/icons/playlist.png\" height=\"14\">";
                                                $output .= "</span>";
                                                $output .= "<span>";
                                                        $output .= "<a href=\"#\" onclick=\"showPlaylist('$playlist');\">";
                                                        $output .=  $playlistClass->getPlaylistTitle($playlist);
                                                        $output .= "</a>";
                                                $output .= "</span>";
                                        $output .= "</li>";
                                }
                        $output .= "</ul>";

                }
			
		$footer = "<a href=\"#addPlaylist\" onclick=\"popper('doit.php?action=addPlaylist')\" title=\"Create a new Playlist\"><i class=\"icon icon-plus\"></i></a>";
			
		
		$output = $this->showDashBox($title, $output, $footer, "playlist", $grid);
		$footer = "";
		
		return $output;
		
	}
	function showMessageBox($grid=true){
			//unseenMessages
                        $messageClass = new message();
			$lastMessages = $messageClass->getLastMessages();
		
			$title = "Your Messages";
			
			$output .= "<ul id=\"messageList\">";
			$i = 0;
			foreach($lastMessages AS $message){
				
				$class = "";
				if($message['seen'] == "0"){
					$class = "unseen";
				}
				
				$output .= "<li class=\"$class\" style=\"clear:both;\">";
					$output .=  "<span>";
					$output .=  stripslashes(showUserPicture($message['sender'],13,'', true));
					$output .=  $message['senderUsername'].':';
					$output .=  "</span>";
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
		
			$output = $this->showDashBox($title, $output,"", "message", $grid);
			
			return $output;
		
	}

	function showFavBox($grid=true){
                        $output = '';
                        $favClass = new fav();
			$title = "Your Favorites";
			
			$output .= "<div>";
				$output .= "<table width=\"100%\">";
					$output .= $favClass->show();
				$output .= "</table>";
			$output .= "</div>";
		
			$output = $this->showDashBox($title, $output," ", "fav", $grid);
			
			return $output;
	}

	function showTaskBox($grid=true){
		$title = "Future Tasks";
		
		
		$tasks = new tasks();
		
		$taskArray = $tasks->get(getUser(), time()-86400, time()+(7*86400));
		$output = '<ul>';
		foreach($taskArray AS $task){
			$editable = authorize($task['privacy'], 'edit', $task['user']);
			$output .= '<li onclick="tasks.show('.$task['id'].','.$editable.');">'.date('d.m.', $task['timestamp']).' - '.$task['title'].'</li>';
		}
		$output .= '</ul>';
		
		
		$footer = "<a href=\"#addTask\" onclick=\"tasks.addForm();\" title=\"Create a new Task\"><i class=\"icon icon-plus\"></i></a>";
			
		
		
		return $this->showDashBox($title, $output,$footer, "task", $grid);
	}
}