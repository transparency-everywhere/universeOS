<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class files {
    public $id;
    
    function __construct($id=NULL){
        if($id != NULL){
            $this->id = $id;
        }
            
    }
    function uploadTempfile($file, $element, $folder, $privacy, $user, $lang=NULL, $download=true){
	 	
	 	//upload file
                $target_path = basename( $file['tmp_name']);
                $filename = $file['name'];
                $thumbname = "$filename.thumb";
                $size = $file['size'];
                $time = time();
                $classFiles = new files();
                $type = $classFiles->getMime($filename);


                $FileElementSQL = mysql_query("SELECT title, folder FROM elements WHERE id='$element'");
                $FileElementData = mysql_fetch_array($FileElementSQL);
		
		if(empty($folder)){
			$folder = $FileElementData['folder'];
		}
		
                $dbClass = new db();
                $FileElementData = $dbClass->select('folders', array('id', $FileElementData['folder']));
                $folderClass = new folder($folder);
                $folderpath = $folderClass->getPath();
			
			
                $thumbPath = $folderpath."thumbs/";
                $imgName = basename($FileElementData['title']."_".$file['name']);
                $elementName = rawurlencode($FileElementData['title'].'_');
                $finalName = $FileElementData['title']."_".$file['name'];


                //move uploaded file to choosen folder and add .temp 
                move_uploaded_file($file['tmp_name'], universeBasePath.'/'.$folderpath.$file['name']);
                rename(universeBasePath.'/'.$folderpath.$file['name'], universeBasePath.'/'.$folderpath.$finalName);
			
                //if type is image => create thumbnail before .temp suffix is added
	        if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){
	                    $thumbPath= "$thumbPath";
	                    $path = "$folderpath";
                            $imageClass = new image();
	                    if(is_dir(universeBasePath.'/'.$thumbPath)){
	                        $imageClass->mkthumb("$finalName",300,300,$path,"$thumbPath");
	                    } else{
	                        mkdir(universeBasePath.'/'.$thumbPath);
	                        $imageClass->mkthumb("$finalName",300,300,$path,"$thumbPath");
	                    }
	        }
            rename(universeBasePath.'/'.$folderpath.$finalName, universeBasePath.'/'.$folderpath.$finalName.".temp");
			//add db entry and add temp value
			if(mysql_query("INSERT INTO `files` (`id` ,`folder` ,`title` ,`size` ,`timestamp` ,`filename` ,`language` ,`type` ,`owner` ,`votes` ,`score` ,`privacy` ,`var1` , `download`, `status`) VALUES (NULL ,  '$element',  '".mysql_real_escape_string($imgName)."',  '$size',  '$time',  '".mysql_real_escape_string($imgName)."',  '$lang',  '$type',  '$user',  '0',  '0',  '$privacy',  '', '$download', 'true');")){
	        	$insertid = mysql_insert_id();
	        	return $insertid;
	        }else{
	        	return false;
	        }
	 }
         
    function addFile($file, $element, $folder, $privacy, $user, $lang=NULL, $download=true){
        
        //upload file
        $target_path = basename( $file['tmp_name']);
        $filename = $file['name'];
        $thumbname = "$filename.thumb";
        $size = $file['size'];
        $time = time();
        
        $classFiles = new files();
        $type = $classFiles->getMime($filename);
        $dbClass = new db();
        $fileFolderData = $dbClass->select('folders', array('id', $folder));
        $FileElementSQL = mysql_query("SELECT title FROM elements WHERE id='$element'");
        $FileElementData = mysql_fetch_array($FileElementSQL);
        $folderClass = new folder($folder);
        $folderpath = universeBasePath.'/'.$folderClass->getPath();


        $thumbPath = universeBasePath.'/'.$folderpath."thumbs/";
        $imgName = basename($file['name']);
        $elementName = "$FileElementData[title]_";
        $imgName = "$elementName$imgName";



        move_uploaded_file($file['tmp_name'], $folderpath.$file['name']);
        rename($folderpath.$filename, $folderpath.$imgName);
        if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){
                    $thumbPath= "$thumbPath";
                    $path = "../../$folderpath";
                    $imageClass = new image();
                    if(is_dir("$thumbPath")){
                       $imageClass -> mkthumb("$imgName",300,300,$path,"$thumbPath");
                    } else{
                        mkdir("$thumbPath");
                        $imageClass -> mkthumb("$imgName",300,300,$path,"$thumbPath");
                    }
        }
		if(mysql_query("INSERT INTO `files` (`id` ,`folder` ,`title` ,`size` ,`timestamp` ,`filename` ,`language` ,`type` ,`owner` ,`votes` ,`score` ,`privacy` ,`var1` , `download`) VALUES (NULL ,  '$element',  '".mysql_real_escape_string($imgName)."',  '$size',  '$time',  '".mysql_real_escape_string($imgName)."',  '$lang',  '$type',  '$user',  '0',  '0',  '$privacy',  '', '$download');")){
        	
        jsAlert("The file has been uploaded :)");
        }else{
        	jsAlert("Opps, something went wrong.");
        }
        ?>
            <script>
                filesystem.tabs.updateTabContent('<?=substr($FileElementData['title'], 0, 10);?>' ,gui.loadPage('modules/filesystem/showElement.php?element=<?=$element;?>&reload=1'));
            </script>
        <?php
        $time = time();
        
        //add feed
        $fileId = mysql_insert_id();
        $feed = "has uploaded a file";
        
        $feedClass = new feed();
        $feedClass->create($user, $feed, "", "showThumb", $privacy, "file", $fileId);
        
    }
    
    function getPath($fileId){
       $file = new file($fileId);
               return $file->getPath();
    }
    
    
    function getMime($filename) {
    	//when we use the finfo class to much erros - safer way
    	//http://php.net/manual/de/function.mime-content-type.php - thx to svogal

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/php',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'mp4' => 'video/mp4',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
		
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
	 function tidyTempFiles(){
	 	mysql_query("DELETE FROM `files` WHERE temp='true' AND timestamp<'".(time()-86400)."'");
	 }
function getFileIcon($fileType, $full=false){
        //turns filetype into src of icon
        //used in showFileList(); showItemThum();
        switch($fileType){
            case "audio/mpeg":
                    $image = "mp3.png";
            break;
            case "audio/wav":
                    $image = "mp3.png";
            break;
            case "audio":
                    $image = "mp3.png";
            break;
            case "video/mp4":
                    $image = "movie.png";
            break;
            case "video":
                    $image = "movie.png";
            break;
        
            //documents
            case "UFF";
                    $image = "txt.png";
            break;
        
            case "text/plain":
                    $image = "txt.png";
                
            break;
            case "text/x-c++":
                    $image = "txt.png";
                
            break;
            case "application/pdf":
                    $image = "pdf.png";
            break;
        
            //excel/open office table
            case "application/vnd.ms-office":
                    $image = "list.png";
            break;
        
            case "application/zip":
                    $image = "zip.png";
            break;
        
            //images
            case "image/jpeg":
                    $image = "img.png";
            break;
            case "image/png":
                    $image = "img.png";
            break;
            case "image/tiff":
                    $image = "img.png";
            break;
            case "image/gif":
                    $image = "img.png";
            break;
            case "image":
                    $image = "img.png";
            break;
        
            //links
            case "youTube":
                    $image = "youTube.png";
            break;
            case "wiki":
                    $image = "wikipedia.png";
            break;
            case "RSS":
                    $image = "rss.png";
            break;
            default:
                    $image = "unknown.png";
        }

		if($full)
			$image = "<img src=\"gfx/icons/fileIcons/$image\">";
        
        return $image;
    }
         
    //put your code here
}

class file{
    
    public $id;
    
    function __construct($id=NULL){
        if($id != NULL){
            $this->id = $id;
        }
            
    }

    function getPath(){
        $fileId = $this->id;
        
        $documentSQL = mysql_query("SELECT id, folder, filename FROM files WHERE id='$fileId'");
        $documentData = mysql_fetch_array($documentSQL);
            $documentElementSQL = mysql_query("SELECT id, title, folder FROM elements WHERE id='$documentData[folder]'");
            $documentElementData = mysql_fetch_array($documentElementSQL);
			$folderClass = new folder($documentElementData['folder']);
			$path = $folderClass->getPath();
			$path .= $documentData['filename'];
			
            return $path;
    }
	 function validateTempFile($privacy){
                $fileId = $this->id;
	 	$path = $this->getPath();
		$oldpath = $path.'.temp';
		if(rename(universeBasePath.'/'.$oldpath, universeBasePath.'/'.$path)){
		 	if(mysql_query("UPDATE `files` SET `temp`='0', `status`='1', `privacy`='$privacy' WHERE id='".save($fileId)."'")){
		 		return true;
		 	}else{
		 		return false;
		 	}
		}
	 }
    
    
    function getFullFilePath($fileId){
        $documentSQL = mysql_query("SELECT folder, filename FROM  `files` WHERE id='".save($fileId)."'");
        $documentData = mysql_fetch_array($documentSQL);
            $documentElementSQL = mysql_query("SELECT folder FROM elements WHERE id='".save($documentData['folder'])."'");
            $documentElementData = mysql_fetch_array($documentElementSQL);
            $path = "upload/";
            $folderClass = new folder($documentElementData['folder']);
            $folderArray = $folderClass->loadFolderArray("path");
            $folderArray = array_reverse($folderArray['names'], true);
            foreach($folderArray as &$folder){
                $path .= "$folder/";
            }
            $dbClass = new db();
            $documentFolderData = $dbClass->select('folders', array('id', save($documentElementData['folder'])));
            $path = urldecode($path);
            $filePath = $path.$documentData['filename'];
            return $filePath;
    }

function getFileData(){
    $fileId = $this->id;
    $dbClass = new db();
    $documentFolderData = $dbClass->select('files', array('id', save($fileId)));
    return $fileData;
}

function getTitle(){
		$fileData = $this->getFileData();
		return $fileData['title'];
	}


    function getFileType(){
        $fileId = $this->id;
            $fileData = mysql_fetch_array(mysql_query("SELECT type FROM files WHERE id='".save($fileId)."'"));
            return $fileData['type'];
        }
    function delete(){
        $fileId = $this->id;
        $dbClass = new db();
        $fileData = $dbClass->select('files', array('id', $fileId));
        $fileElementSql = mysql_query("SELECT id, title, folder FROM elements WHERE id='$fileData[folder]'");
        $fileElementData = mysql_fetch_array($fileElementSql);

        $type = $fileData['type'];

        //for all standard files
        $title = $fileData['filename'];




        //file can only be deleted if uploader = deleter
        if(authorize($fileData['privacy'], "edit")){
            $folderClass = new folder($fileElementData['folder']);
            $folderPath = $folderClass->getPath();
            $fileClass = new file($fileId);
            $filePath = $fileClass->getFullFilePath($fileId);
            $thumbPath = $folderPath.'thumbs/'.$title;
            echo $filePath.'asd';
            if(unlink(universeBasePath.'/'.$filePath)){
                if(mysql_query("DELETE FROM files WHERE id='$fileId'")){

                   //delete comments
                   $commentClass = new comments();
                   $commentClass->deleteComments("file", $fileId);

                   $classFeed = new feed();
                   $classFeed->deleteFeeds("file", $fileId);

                   $classShortcut = new shortcut();
                   $classShortcut->deleteInternLinks("file", $fileId);

                   //delete thumbnails
                   if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){

                        $thumbPath = $folderPath.'thumbs/'.$title;
                       if(unlink(universeBasePath.'/'.$thumbPath)){
                           return true;
                       }else{
                           return false;
                       }

                   }else{
                       return true;
                   }
               }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
}

	
    
    
    
	 
         
    


