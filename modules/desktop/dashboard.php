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
		color: #474747;
		font-size: 15pt;
		width: 230px;
		height: 155px;
		border-radius: 1px;
		background: rgba(219, 219, 219, 0.95);
		margin: 10px 0px 0px 10px;
		position: relative;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		float: left;
		border: 1px solid #919191;
	}
	
	.dashBox .content{
		overflow: auto;
		max-height: 300px;max-height: 115px;
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
		border: 1px solid #555555;
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
	
</style>
<div id="dashBoard">
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Welcome</header>
		<div style="position: absolute;text-indent: 9px;line-height: 28pt;">
	         <?=showUserPicture($_SESSION[userid], "20");?>Hey <a href="#" onclick="showProfile(<?=$_SESSION[userid];?>)"><?=$userData[username];?></a>,<br>good to see you!
	         
		</div>
	</div>
	<div class="dashBox">
		<a class="dashClose"></a>
		<header>Your Groups</header>
		<ul class="content">
			<?
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
			}
			?>
			
		</ul>
		<footer>
			<a href="#addGroup" onclick="popper('doit.php?action=addGroup')" title="Create a new Group"><i class="icon icon-plus"></i></a>
		</footer>
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
					echo universeTime($message[timestamp]);
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
					echo"<span class=\"marginRight\">";
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
			                  echo'<table>';
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