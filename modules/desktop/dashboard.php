<?
$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);

//groups
$groups = getGroups();

//playlists
$playlists = getPlaylists();

//fav, //playlists, //groups, //files
?>
<style>
	.dashBox{
		width: 200px;
		border-radius: 5px;
		background: #c9c9c9;
		margin: 7px 0px 0px 7px;
	}
</style>
<div class="dashBox">
	<header>Welcome</header>
	<div>
         <p style="float: left"><?=showUserPicture($_SESSION[userid], "20");?>Hey <a href="#" onclick="showProfile(<?=$_SESSION[userid];?>)"><?=$userData[username];?></a>,<br>good to see you!</p>
         
	</div>
</div>
<div class="dashBox">
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