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
                $userClass = new user($user);
		$this->userdata = $userClass->getData();

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
	    	$content .= "<li onclick=\"feed.show()\" onmouseup=\"closeDockMenu()\"><i class=\"icon icon-navicon\" style=\"height:16px;width:16px;\">Feed</li>";
	    	$content .= "<li onclick=\"calendar.show();\" onmouseup=\"closeDockMenu()\"><i class=\"icon icon-calendar\" style=\"height:16px;width:16px;\">Calendar</li>";
			$content .= "<li onclick=\"filesystem.show()\" onmouseup=\"closeDockMenu()\"><i class=\"icon icon-folder\" style=\"height:16px;width:16px;\">Filesystem</li>";
	 		$content .= "<li onclick=\"javascript: reader.show();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/viewer.png\" border=\"0\" height=\"16\">Reader</li>";
	   		$content .= "<li onclick=\"javascript: buddylist.show()\" onmouseup=\"closeDockMenu()\"><i class=\"icon icon-user\" style=\"height:16px;width:16px;\"></i>Buddylist</li>";
	    	$content .= "<li onclick=\"javascript: chat.init()\" onmouseup=\"closeDockMenu()\">><i class=\"icon icon-user\" style=\"height:16px;width:16px;\"></i>Chat</li>";
	    	$content .= "<li onclick=\"javascript: settings.show();\" onmouseup=\"closeDockMenu()\"><i class=\"icon icon-gear\" style=\"height:16px;width:16px;\"></i>Settings</li>";
	    $content .= "</ul>";
		
		$output = $this->showDashBox($title, $content," ", "app", $grid);
		//is deactivated, loaded threw js in dashboard.init()
		//return $output;
		
	}
	function showGroupBox($grid=true){
		
		//groups
                $groupsClass = new groups();
		$groups = $groupsClass->getGroups();
		$output = '';
		$title = "Your Groups";
		
		if(count($groups) == 0){
			
				$output .='<p class="overlength">';
				$output .="No Groups :/";
				$output .="</p>";
		}else{
			
			$output .= "<ul class=\"\">";
				foreach($groups AS $group){
					$output .="<li onclick=\"groups.showProfile('$group');\">";
						$output .="<span class=\"marginRight\">";
							$output .= '<i class="icon white-user" style="height:20px; width: 20px;margin-bottom: -5px;"></i>';
						$output .="</span>";
						$output .="<span>";
							$output .= shorten($groupsClass->getGroupName($group),19);
						$output .="</span>";
					$output .="</li>";
					$i++;
				}
				
			$output .= "</ul>";
		}
		
		$footer = "<a href=\"#addGroup\" onclick=\"groups.showCreateGroupForm();\" title=\"Create a new Group\"><i class=\"icon white-plus\" style=\"color:#FFF; margin-bottom: -10px;\"></i></a>";
		
		$output = $this->showDashBox($title, $output, $footer, "group", $grid);
		
		return $output;
		
	}
	function showPlaylistBox($grid=true){
		$output = '';
                //playlists
                $playlistClass = new playlist();
                $playlists = $playlistClass->getUserPlaylistArray();

                $title = "Your Playlists";
                if(count($playlists) == 0){

                        $output .= '<p style="padding: 5px; padding-top: 12px;">';
                        $output .= 'You don\'t have any playlists so far.';
                        $output .= '</p>';

                }else{

                        $output .= "<ul>";
                        $i = 0;
                                foreach($playlists['ids'] AS $key=>$list_id){
                                        $output .= "<li>";
                                                $output .= "<span class=\"marginRight\">";
                                                        $output .= '<i class="icon white-play" style="height:20px; width: 20px;margin-bottom: -5px;"></i>';
                                                $output .= "</span>";
                                                $output .= "<span>";
                                                        $output .= "<a href=\"#\" onclick=\"playlists.showInfo('".$list_id."');\">";
                                                        $output .=  $playlists['titles'][$key];
                                                        $output .= "</a>";
                                                $output .= "</span>";
                                        $output .= "</li>";
                                        $i++;
                                }
                        $output .= "</ul>";

                }
			
		$footer = "<a href=\"#addPlaylist\" onclick=\"playlists.showCreationForm();\" title=\"Create a new Playlist\"><i class=\"icon white-plus\" style=\"color:#FFF\"></i></a>";
			
		
		$output = $this->showDashBox($title, $output, $footer, "playlist", $grid);
		$footer = "";
		
		return $output;
	}
	function showMessageBox($grid=true){
			//unseenMessages
                        $messageClass = new message();
			$lastMessages = $messageClass->getLastMessages();
		
			$title = "Your Messages";
			
			$output = "<ul id=\"messageList\">";
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
					$output .=  "<a href=\"#\" onclick=\"im.openDialogue('$message[senderUsername]');\">";
					$output .=  substr($message['text'], 0, 15);
					$output .=  "</a>";
					$output .=  "</span>";
				$output .= "</li>";
				$i++;
			}
			
			$output .= "</ul>";
			if($i == 0){
				$output .= "<span>";
				$output .= "You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .= "</span>";
			}
		
			$output = $this->showDashBox($title, $output,"", "message", $grid);
			
			return $output;
		
	}

	function showFavBox($grid=true){
                        $output = '';
                        $favClass = new fav();
			$title = "Your Favorites";
			
			$output .= "<div>";
				$output .= "<ul id=\"favList\">";
					$output .= $favClass->showUL();
				$output .= "</ul>";
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
		
		
		$footer = "<a href=\"#addTask\" onclick=\"tasks.addForm();\" title=\"Create a new Task\"><i class=\"icon white-plus\" style=\"color:#FFF\"></i></a>";
			
		
		
		return $this->showDashBox($title, $output,$footer, "task", $grid);
	}
}