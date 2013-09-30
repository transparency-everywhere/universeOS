<?php
switch($type){
	case'welcome':
		break;
	case 'apps':
		break;
	case 'groups':
		break;
	case 'playlists':
		break;
	case 'files':
		break;
	case 'fav':
		break;
	case 'tasks':
		break;
}



$userid = getUser();


$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);

//groups
$groups = getGroups();

//playlists
$playlists = getPlaylists();

//unseenMessages
$lastMessages = getLastMessages();

//fav, //playlists, //groups, //files
?>
<script>
$(document).ready(function(){
	$('.dashBox .dashClose').click(function(){
		$(this).parent('.dashBox').slideUp();
	});	
});
</script>
<style>
	#dashBoard{
		width: 728px;
	}

	.dashBox{
		color: #474747;
		font-size: 15pt;
		width: 230px;
		height: 180px;
		border-radius: 1px;
		background: rgba(240, 240, 240, 0.85);
		margin: 10px 0px 0px 10px;
		position: relative;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		float: left;
	}
	
	.dashBox .content{
		overflow: auto;
		max-height: 130px;
	}
	
	.dashBox header{
		padding: 5px;
		font-size: 16pt;
		line-height: 15px;
		text-align: right;
		height: 12px;
		border-bottom: 2px solid #3a3a3a;
		color: #FFF;
		padding-top: 10px;
		background: rgb(38,38,38);
		background: -moz-linear-gradient(top, rgba(38,38,38,1) 0%, rgba(40,40,40,1) 49%, rgba(40,40,40,1) 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(38,38,38,1)), color-stop(49%,rgba(40,40,40,1)), color-stop(100%,rgba(40,40,40,1)));
		background: -webkit-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%);
		background: -o-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%);
		background: -ms-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%);
		background: linear-gradient(to bottom, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#262626', endColorstr='#282828',GradientType=0 );
		-webkit-border-top-left-radius: 3px;
		-moz-border-top-left-radius: 3px;
		border-top-left-radius: 3px;
		-webkit-border-top-right-radius: 3px;
		-moz-border-top-right-radius: 3px;
		border-top-right-radius: 3px;
		margin-left: -1px;
		margin-right: -1px;
		margin-top: -1px;
		border-bottom: none;
	}
	
	.dashBox footer{
		position: absolute;
		bottom: 0px;
		left: 0px;
		right: 0px;
		height: 15px;
	}
	
	.dashBox footer a:first-of-type{
		float:left;
	}
	
	.dashBox footer a{
		float:right;
	}
	
	.dashBox div, .dashBox ul{
		padding: 5px;
	}
	
	.dashBox .dashClose{
		position: absolute;
		top: 5px;
		left: 5px;
		background-position: -312px 0;
		background-image: url("inc/img/glyphicons-halflings-white.png");display: inline-block;
		width: 14px;
		height: 14px;
		margin-top: 1px;
		line-height: 14px;
		vertical-align: text-top;
		
		cursor: pointer;
	}
	
	.dashBox .userPicture{
		margin-right:5px;
		margin-bottom:5px;
	}
	
	.dashBox #messageList .unseen{
		font-weight: bold;
	}
	
	.dashBox #messageList span{
		margin-left:5px;
	}
	
	#dashBoard .filetable img{
		min-width:22px;/*force table to display icon right*/
	}
	#dashBox .filetable ul{
		padding:0px;/*force table to display icon right*/
	}
	
	#dashBoard .strippedRow{
		border: none;
	}
	
	.marginRight{
		margin-right: 5px;
	}
	
	
                #dashBoard .appList{
                    border-right: none;
                }
            
                #dashBoard .appList > li{
                    background: #303030; /* Old browsers */
                    background: -moz-linear-gradient(top, #303030 0%, #212121 100%); /* FF3.6+ */
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#303030), color-stop(100%,#212121)); /* Chrome,Safari4+ */
                    background: -webkit-linear-gradient(top, #303030 0%,#212121 100%); /* Chrome10+,Safari5.1+ */
                    background: -o-linear-gradient(top, #303030 0%,#212121 100%); /* Opera 11.10+ */
                    background: -ms-linear-gradient(top, #303030 0%,#212121 100%); /* IE10+ */
                    background: linear-gradient(to bottom, #303030 0%,#212121 100%); /* W3C */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#303030', endColorstr='#212121',GradientType=0 ); /* IE6-9 */
                    padding: 5px;
                    padding-top: 12px;
                    padding-bottom: 12px;
                    margin-left: 0px;
                    color: #8f8f8f;
                    border: 0px;
                    border-top: #1C1C1C 1px solid;
                    border-left: #1C1C1C 1px solid;
                    width: 98px;
                    cursor:pointer;
                    float:left;
                }
                
                #dashBoard .appList > li:hover{
                    background: #303030; /* Old browsers */
                }
	
</style>
<div id="dashBoard">
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Welcome</header>
		<div style="position: absolute;text-indent: 9px;line-height: 28pt; width:220px;">
	         <?=showUserPicture($userid, "20");?>Hey <a href="#" onclick="showProfile(<?=$userid;?>)"><?=$userData[username];?></a>,<br>good to see you!
	         
		</div>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Apps</header>
	    <ul class="appList content" style="padding:5px; width:220px;">
	        <li onclick="toggleApplication('feed')" onmouseup="closeDockMenu()"><img src="./gfx/feed.png" border="0" height="16">Feed</li>
	
	        <li class="" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">Filesystem</li>
	
	
	        <li class="" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">Reader</li>
	        <li class="" onclick="javascript: toggleApplication('buddylist')" onmouseup="closeDockMenu()"><img src="./gfx/buddylist.png" border="0" height="16">Buddylist</li>
	        <li class="" onclick="javascript: toggleApplication('chat')" onmouseup="closeDockMenu()"><img src="./gfx/buddylist.png" border="0" height="16">Chat</li>
	        <li class="" onclick="javascript: showModuleSettings();" onmouseup="closeDockMenu()"><img src="./gfx/settings.png" border="0" height="16">Settings</li>
	    </ul>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Groups</header>
		<ul class="content">
			<?
			$i = 0;
			foreach($groups AS $group){
				echo"<li>";
					echo"<span class=\"marginRight\">";
						echo"<img src=\"./gfx/icons/group.png\" height=\"14\">";
					echo"</span>";
					echo"<span>";
						echo"<a href=\"#\" onclick=\"showGroup('$group');\">";
						echo getGroupName($group);
						echo"</a>";
					echo"</span>";
				echo"</li>";
				$i++;
			}
			if($i == 0){
				echo"<li>";
				echo"You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				echo"</li>";
			}
			?>
			
		</ul>
		<footer>
			<a href="#addGroup" onclick="popper('doit.php?action=addGroup')" title="Create a new Group"><i class="icon icon-plus"></i></a>
			<a href="#addGroup" onclick="popper('doit.php?action=addPlaylist')" title="Create a new Group"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Messages</header>
		<ul id="messageList" class="content">
			<?
			$i = 0;
			foreach($lastMessages AS $message){
				
				$class = "";
				if($message['seen'] == "0"){
					$class = "unseen";
				}
				
				echo"<li class=\"$class\">";
					echo "<span>";
					echo showUserPicture($message[sender],07);
					echo "</span>";
					echo "<span>";
					echo "$message[senderUsername]";
					echo "</span>";
					echo "<span>";
					echo universeTime($message[timestamp]);
					echo "</span>";
					echo "<span>";
					echo "<a href=\"#\" onclick=\"openChatDialoge('$message[senderUsername]');\">";
					echo substr($message['text'], 0, 15);
					echo "</a>";
					echo "</span>";
				echo"</li>";
				$i++;
			}
			if($i == 0){
				echo"<li>";
				echo"You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				echo"</li>";
			}
			?>
			
		</ul>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Playlists</header>
		<ul class="content">
			<?
			$i = 0;
			foreach($playlists AS $playlist){
				echo"<li>";
					echo"<span class=\"marginRight\">";
						echo"<img src=\"./gfx/icons/playlist.png\" height=\"14\">";
					echo"</span>";
					echo"<span>";
						echo"<a href=\"#\" onclick=\"showPlaylist('$playlist');\">";
						echo getPlaylistTitle($playlist);
						echo"</a>";
					echo"</span>";
				echo"</li>";
				$i++;
			}
			if($i == 0){
				echo"<li>";
				echo"You don't have any playlists so far.";
				echo"<li>";
			}
			
			?>
			
		</ul>
		<footer>
			<a href="#addPlaylist" onclick="popper('doit.php?action=addPlaylist')" title="Create a new Playlist"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Files</header>
		<div class="content">
		<?
		
			                  //show folders and elements
			                  $folderQuery = "WHERE creator='$userid' ORDER BY timestamp DESC";
			                  $elementQuery = "WHERE author='$userid' ORDER BY timestamp DESC";
			                  showFileBrowser($folder, "$folderQuery", "$elementQuery");
			                  
			                  //show files
			                  $fileQuery = "owner='$userid' ORDER BY timestamp DESC";
			                  echo'<table>';
			                  showFileList('', $fileQuery);
			                  echo"</table>";
		?>
		</div>
		<footer>
			<a href="#uploadFile" onclick="popper('modules/filesystem/upload');" title="Upload a File"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Fav</header>
		<div class="content">
        <?
		 showFav($userid);
		?>
		</div>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Tasks</header>
		<div class="content">
		</div>
		<footer>
			<a href="#addGroup" onclick="popper('doit.php?action=addGroup')" title="Create a new Group"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
</div>