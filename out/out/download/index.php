<?
session_start();
include_once("../../../inc/config.php");
include_once("../../../inc/functions.php");
        
        
        $db = new db();
        $data = $db->query('files', array('id', $itemId), array('id', 'title', 'type', 'filename', 'privacy', 'owner'));
        
		if(authorize($documentData['privacy'], "show", $documentData['owner'])){
                $classFile = new file($_GET['fileId']);
	        $downloadfile = $classFile->getPath();
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