
<?PHP
// is used to handle incomming links in the filesystem usually refered from /out/index.php


if(isset($_GET[openFromLink])){
	echo"<script>";
	echo"alert('handler')";
	switch($_GET[type]){
		case folder:
			
			break;
		case element:
			
			break;
		case file:
			
			break;
		case link:
			
			break;
	}
	echo"<script>";
}
?>
