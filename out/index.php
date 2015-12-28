<?
include_once("../inc/config.php");
include_once("../inc/functions.php");
echo"<html>";
echo"<head>";
$db = new db();
//define type and itemId
if(!empty($_GET['folder'])){
	$type = "Folder";
	$itemId = $_GET['folder'];
	$data = $db->select('folders', array('id', $itemId), array('name', 'folder'));
	$title = $data['name'];
	
}else if(!empty($_GET['element'])){
	
	$type = "Element";
	$itemId = $_GET['element'];
	$data = $db->select('elements', array('id', $itemId), array('folder', 'title'));
	$title = $data['title'];
	
}else if(!empty($_GET['file'])){
	$type = "File";
	$fileId = $_GET['file'];
	$data = $db->select('files', array('id', $fileId), array('title', 'type'));
	$title = $data['title'];
}
	$metaDescriptionContent = "universeOS $type $title.";
	$metaDescriptionContent .= "Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...";
	echo"<meta name=\"description\" content='$metaDescriptionContent'>";


	
    echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../inc/css/out.css\" />";
    echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../inc/css/plugins.css\" />";

	//page title
	echo"<title>";
	echo"universeOS - $type $title";
	echo"</title>\n";
	
	
	
	//facebook open graph picture
	if(empty($picture)){
            echo"<meta property=\"og:image\" content=\"http://universeos.org/gfx/logo.png\"/>\n";
	}else{
            echo"<meta property=\"og:image\" content=\"http://universeos.org/$picture\"/>\n";
	}
	
	//meta information
	echo"<meta name=\"title\" content=\"universeOS - $type $title\">";
	echo"<meta name=\"keywords\" content=\"universeOS $type $title, $type, $title, $type $title\">";
    echo'<meta name=\"Robots\" content=\"index,follow\">';
echo'</head>';
echo'<body>';
echo'<table width="100%">';

switch($type){
	case "Folder":
                        $fileSystem = new fileSystem();
                        $fileSystem->showFileBrowser($itemId, '', '', false,  '../');
		break;
	case "Element":
            $element = new element();
						echo'<style>';
						echo'img{ margin-top: -16px; }';
						echo'</style>';
						echo'<div>&nbsp;<img src="../gfx/icons/filesystem/folder.png" height="22" style="margin-top:0px;">';
						echo'<a href="../out/?folder='.$data['folder'].'" onclick="openFolder(\''.$data['folder'].'\'); return false;">&nbsp;&nbsp;...</a></td></div>';
                        $element->showFileList($itemId, '', true, '../');
		break;
	case "File":
						echo"</table>";
						echo '<div class="openFile">';
                        echo openFile($fileId, '', $data['type'], '', '', '', '', '', '', '', '../');
						echo '</div>';
						echo"<table>";
		break;
}
echo"</table>";
echo'<div class="watermark">';
echo'<a href="http://universeos.org" target="_parent">universeOS</a>';
echo"</div>";
echo"<iframe name=\"submitter\" style=\"display:none\">";
echo"<script>";
// echo"window.location.href = \"http://universeos.org/index.php?openFromLink=openFromLink=$type\";";
echo"</script>";
echo"</body>";
echo"</html>";
?>