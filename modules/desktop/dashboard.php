<?
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
		width: 500px;
	}

	.dashBox{
		color: #FFFFFF;
		width: 230px;height: 155px;
		border-radius: 1px;
		background: rgba(15,15,15,0.8);
		margin: 10px 0px 0px 10px;
		position: relative;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		
		float:left;
	}
	
	.dashBox .content{
		overflow: auto;
		max-height: 300px;max-height: 115px;
	}
	
	.dashBox header{
		padding:5px;
		background: rgba(30,30,30,0.8);
		
		-webkit-border-top-left-radius: 3px;
		-webkit-border-top-right-radius: 3px;
		-moz-border-radius-topleft: 3px;
		-moz-border-radius-topright: 3px;
		border-top-left-radius: 3px;
		border-top-right-radius: 3px;
	}
	
	.dashBox div, .dashBox ul{
		padding: 5px;
	}
	
	.dashBox .dashClose{
		position: absolute;
		top: 5px;
		right: 5px;
		background-position: -312px 0;
		background-image: url("inc/img/glyphicons-halflings-white.png");display: inline-block;
		width: 14px;
		height: 14px;
		margin-top: 1px;
		line-height: 14px;
		vertical-align: text-top;
		
		cursor: pointer;
	}
	
	.dashBox #messageList .unseen{
		font-weight: bold;
	}
	
	.dashBox #messageList span{
		margin-left:5px;
	}
	
</style>
<div id="dashBoard">
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Welcome</header>
		<div>
	         <p style="float: left"><?=showUserPicture($_SESSION[userid], "20");?>Hey <a href="#" onclick="showProfile(<?=$_SESSION[userid];?>)"><?=$userData[username];?></a>,<br>good to see you!</p>
	         
		</div>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Groups</header>
		<ul class="content">
			<?
			foreach($groups AS $group){
				echo"<li>";
					echo"<span>";
						echo"<img src=\"./gfx/icons/group.png\" height=\"14\">";
					echo"</span>";
					echo"<span>";
						echo"<a href=\"#\" onclick=\"showGroup('$group');\">";
						echo getGroupName($group);
						echo"</a>";
					echo"</span>";
				echo"</li>";
			}
			?>
			
		</ul>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Messages</header>
		<ul id="messageList" class="content">
			<?
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
					echo "<a href=\"#\" onclick=\"openChatDialoge('$message[senderUsername]');\">";
					echo substr($message['text'], 0, 15);
					echo "</a>";
					echo "</span>";
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
			foreach($playlists AS $playlist){
				echo"<li>";
					echo"<span>";
						echo"<img src=\"./gfx/icons/playlist.png\" height=\"14\">";
					echo"</span>";
					echo"<span>";
						echo"<a href=\"#\" onclick=\"showPlaylist('$playlist');\">";
						echo getPlaylistTitle($playlist);
						echo"</a>";
					echo"</span>";
				echo"</li>";
			}
			?>
			
		</ul>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Files</header>
		<div class="content">
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
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Fav</header>
		<div class="content">
        <? 
		 showFav($_SESSION[userid]);
		?>
		</div>
	</div>
</div>