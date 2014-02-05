<?

//fav, //playlists, //groups, //files

$dashboard = new dashBoard();
?>
<div id="dashBoard">
	<?php
	
	echo $dashboard->showAppBox();
	
	echo $dashboard->showGroupBox();
	
	echo $dashboard->showPlaylistBox();
	
	//echo $dashboard->showMessageBox();
	
	
$userid = getUser();
$userSql = mysql_query("SELECT username, myFiles FROM user WHERE userid='$_SESSION[userid]'");
$userData = mysql_fetch_array($userSql);
	?>
	
	<!-- <div class="dashBox" id="fileBox">
		<a class="dashClose"></a>
		<header>Your Files</header>
		<div class="content">
			<center style="margin: 15px;">
    			<a class="btn btn-info" href="#" onclick="loader('loader', 'doit.php?action=createNewUFF&element=<?=$userData['myFiles'];?>'); " target="submitter"><i class="icon-file icon-white"></i> Create Document</a>
    			<br /><br />
				<a href="#" onclick="openUploadTab();" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;add File</a>
				<a href="#" onclick="popper('./doit.php?action=addLink&element=<?=$userData['myFiles'];?>')" class="btn btn-info"><i class="icon-globe icon-white"></i>&nbsp;add Link</a>
			</center>
		</div>
		<footer>
			<!-- <a href="#uploadFile" onclick="openUploadTab();" title="Upload a File"><i class="icon icon-plus"></i></a>
		</footer>
	</div> -->
	<?php
	
		echo $dashboard->showFavBox();
	
		echo $dashboard->showTaskBox();
	?>
</div>