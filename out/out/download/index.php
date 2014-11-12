<?
session_start();
include_once("../../../inc/config.php");
include_once("../../../inc/functions.php");

        $documentSQL = mysql_query("SELECT id, title, type, filename, privacy, owner FROM files WHERE id='".save($_GET['fileId'])."'");
        $documentData = mysql_fetch_array($documentSQL); 
		if(authorize($documentData['privacy'], "show", $documentData['owner'])){
                $classFile = new file($_GET['fileId']);
	        $downloadfile = $classFile->getFilePath();
	        $filename = $documentData['filename'];
	        $downloadfile = "../../../$downloadfile";
	        $filesize = filesize($downloadfile);
	        $filetype = end(explode('.', $filename));
	        header("Content-type: application/$filetype");
	        header("Content-Disposition: attachment; filename=".$filename."");
	        readfile("$downloadfile");
	        exit;
	 	}else{
	 		jsAlert("No rights, bro.");
	 	}
?>