<?

//fav, //playlists, //groups, //files

$dashboard = new dashBoard();
?>
<div id="dashGrid" class="down">
<div id="dashBoard" style="border-bottom: 1px solid #171717;">
	<div id="dashBoxFrame">
		<div id="scrollFrame">
	<?php
	
	echo $dashboard->showAppBox();
	
	echo $dashboard->showGroupBox();
	
	echo $dashboard->showPlaylistBox();
	
//	echo $dashboard->showMessageBox();
        
		echo $dashboard->showFavBox();
	
		echo $dashboard->showTaskBox();
                
	?></div>
	</div>
</div></div>
