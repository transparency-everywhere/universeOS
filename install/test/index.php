<?

ini_set('error_reporting', E_ALL);


$imageString = file_get_contents("http://universeos.org/current.zip");
$save = file_put_contents('current.zip',$imageString);

	$zip = new ZipArchive();
	$x = $zip->open('current.zip');
	if ($x === true) {
		$zip->extractTo("./"); // change this to the correct site path
		$zip->close();
	    unlink('current.zip');
	}
$message = "Your .zip file was uploaded and unpacked.";

?>