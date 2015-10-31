<?
session_start();
include_once("../../inc/config.php");
include_once("../../inc/functions.php");
        $db = new db();
        $documentData = $db->query("SELECT id, title, type, filename, privacy, owner FROM files WHERE id='".save($_GET['fileId'])."'");
        
		if(authorize($documentData['privacy'], "show", $documentData['owner'])){
                    $fileClass = new file($_GET['fileId']);
                    $downloadfile = $fileClass->getPath();
                    $filename = $documentData['filename'];
                    $downloadfile = "../../$downloadfile";
                    $filesize = filesize($downloadfile);
                    $filetype = end(explode('.', $filename));
                    header("Content-type: application/$filetype");
                    header("Content-Disposition: attachment; filename=".$filename."");
                    readfile("$downloadfile");
                    exit;
	 	}else{
	 		echo("No rights, bro.");
	 	}
?>