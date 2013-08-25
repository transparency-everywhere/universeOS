<?
$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);

//groups
$groups = getGroups();

//playlists
$playlists = getPlaylists();

//unseenMessages
$unseenMessages = getUnseenMessages();

//fav, //playlists, //groups, //files
?>
<style>
	.dashBox{
		color: #FFFFFF;
		width: 200px;
		border-radius: 1px;
		background: rgba(15,15,15,0.8);
		margin: 7px 0px 0px 7px;
		position: relative;
	}
	
	.dashBox header{
		padding:5px;
		background: rgba(30,30,30,0.8);
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
	}
	
</style>
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
	<ul>
		<?
		foreach($groups AS $group){
			echo"<li>";
			echo getGroupName($group);
			echo"</li>";
		}
		?>
		
	</ul>
</div>
<div class="dashBox">
	<a class="dashClose"></a>
	<header>Your Playlists</header>
	<ul>
		<?
		foreach($playlists AS $playlist){
			echo"<li>";
			echo getPlaylistTitle($playlist);
			echo"</li>";
		}
		?>
		
	</ul>
</div>
<div class="dashBox">
	<a class="dashClose"></a>
	<header>Your Messages</header>
	<ul>
		<?
		foreach($unseenMessages AS $message){
			echo"<li>";
				echo "<span>";
				echo $message['senderUsername'];
				echo "</span>";
				echo "<span>";
				echo $message['text'];
				echo "</span>";
			echo"</li>";
		}
		?>
		
	</ul>
</div>