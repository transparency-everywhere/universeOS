<?

$userid = getUser();
$userSql = mysql_query("SELECT username FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);
//fav, //playlists, //groups, //files

$dashboard = new dashBoard();
?>
<div id="dashBoard">
	<?php
	echo $dashboard->showWelcomeBox();
	
	echo $dashboard->showAppBox();
	
	echo $dashboard->showGroupBox();
	
	echo $dashboard->showPlaylistBox();
	
	echo $dashboard->showMessageBox();
	?>
	
	<div class="dashBox" id="fileBox">
		<a class="dashClose"></a>
		<header>Your Files</header>
		<div class="content">
			<li>Upload File</li>
			<li>Add Link</li>
			<li>Add Link</li>
		</div>
		<footer>
			<a href="#uploadFile" onclick="openUploadTab();" title="Upload a File"><i class="icon icon-plus"></i></a>
		</footer>
	</div>
	<?php
	
		echo $dashboard->showFavBox();
	
		//echo $dashboard->showTaskBox();
	?>
</div>