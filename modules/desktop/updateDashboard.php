<?
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");
$dashboard = new dashBoard();
$type = $_GET['type'];
switch($type){
	
	case 'welcome':
	echo $dashboard->showWelcomeBox(false);
		break;
	case 'app':
	echo $dashboard->showAppBox(false);
		break;
	case 'group':
	echo $dashboard->showGroupBox(false);
		break;
	case 'playlist':
	echo $dashboard->showPlaylistBox(false);
		break;
	case 'message':
	echo $dashboard->showMessageBox(false);
		break;
	case 'fav':
	echo $dashboard->showFavBox(false);
		break;
	case 'task':
	echo $dashboard->showTaskBox(false);
		break;
	
}
	
	
	
	
	?>
	