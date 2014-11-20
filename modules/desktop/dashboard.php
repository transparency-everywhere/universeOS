<?

//fav, //playlists, //groups, //files

$dashboard = new dashBoard();
?>
<div id="dashGrid">
<div id="dashBoard" class="down" style="border-bottom: 1px solid #171717;">
	<div id="dashBoxFrame">
		<div id="scrollFrame">
	<?php
	
	echo $dashboard->showAppBox();
	
	echo $dashboard->showGroupBox();
	
	echo $dashboard->showPlaylistBox();
	
	echo $dashboard->showMessageBox();
	
	
$userSql = mysql_query("SELECT username, myFiles FROM user WHERE userid='".getUser()."'");
$userData = mysql_fetch_array($userSql);
		echo $dashboard->showFavBox();
	
		echo $dashboard->showTaskBox();
	?></div>
	</div>
	<footer>
		<a href="#" onclick="dashBoard.toggle();" class="disableToggling"><i class="icon-arrow-down"></i></a>
	</footer>
</div></div>
