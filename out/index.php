<?
session_start();
include_once("../inc/config.php");
include_once("../inc/functions.php");
echo"<html>";
echo"<head>";

//define type and itemId
if(!empty($_GET['folder'])){
	$type = "Folder";
	$itemId = $_GET['folder'];
	
	$query = mysql_query("SELECT name, folder FROM folders WHERE id='".mysql_real_escape_string($itemId)."'");
	$data = mysql_fetch_array($query);
	$title = $data['name'];
	
}else if(!empty($_GET['element'])){
	$type = "Element";
	$itemId = $_GET['element'];
}else if(!empty($_GET['file'])){
	$type = "File";
	$fileId = $_GET['file'];
	$query = mysql_query("SELECT `title` FROM `files` WHERE id='".mysql_real_escape_string($fileId)."'");
	$data = mysql_fetch_array($query);
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
	echo'<meta name=\"title\" content=\"universeOS - $type $title\">';
	echo'<meta name=\"keywords\" content=\"universeOS $type $title, $type, $title, $type $title\">';
    echo'<meta name=\"Robots\" content=\"index,follow\">';
echo'</head>';
echo'<body>';
echo'<table width="100%">';

switch($type){
	case "Folder":
                        showFileBrowser($itemId, '', '', false,  '../');
		
		break;
	case "Element":
						echo'<style>';
						echo'img{ margin-top: -16px; }';
						echo'</style>';
                        showFileList($itemId, '', true, '../');
		break;
	case "File":
						echo"</table>";
						echo '<div class="openFile">';
                        echo openFile($fileId, $linkId, '', '', '', '', '', '', '', '', '../');
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