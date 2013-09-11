
<?PHP
// is used to handle incomming links in the filesystem usually refered from /out/index.php


if(!empty($_GET[openFromLink])){
	
	echo"<script>";
	switch($_GET[type]){
		case folder:
			$folder = $_GET[folder];
			echo"openFolder('$folder');";
			break;
		case element:
			$element = $_GET[element];
			$elementData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='".mysql_real_escape_string($element)."'"));
			echo"openElement('$element', '$elementData[title]');";
			break;
		case 'file':
			$file = $_GET['file'];
			$fileData = mysql_fetch_array(mysql_query("SELECT type, title FROM files WHERE id='".mysql_real_escape_string($file)."'"));
			echo"openFile('$fileData[type]', '$file', '$fileData[title]')";
			break;
		case 'link':
			$link = $_GET['link'];
			$linkData = mysql_fetch_array(mysql_query("SELECT type, title FROM links WHERE id='".mysql_real_escape_string($link)."'"));
			
			echo"openFile('$linkData[type]', '$link', '$linkData[title]')";
			
			break;
	}
	echo"</script>";
}
?>
