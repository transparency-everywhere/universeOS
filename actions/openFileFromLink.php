
<?php
// is used to handle incomming links in the filesystem usually refered from /out/index.php


if(!empty($_GET['openFromLink'])){
	echo"<script>";
	switch($_GET['type']){
		case 'folder':
			$folder = (int)$_GET['folder'];
			echo"folders.open('$folder');";
			break;
		case 'element':
			$element = (int)$_GET['element'];
			echo"elements.open('$element');";
			break;
		case 'file':
			$file = (int)$_GET['file'];
			echo"handlers.files.handler($file);";
			break;
		case 'link':
			$link = (int)$_GET['link'];
			echo"handlers.links.handler($link);";
			break;
	}
	echo"</script>";
}
?>
