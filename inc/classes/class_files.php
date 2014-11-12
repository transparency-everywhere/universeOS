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
    function getFilePath($fileId){
       $file = new file($fileId);
               return $file->getFilePath();
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

    function getFilePath(){
        $fileId = $this->id;
        
        $documentSQL = mysql_query("SELECT id, folder, filename FROM files WHERE id='$fileId'");
        $documentData = mysql_fetch_array($documentSQL);
            $documentElementSQL = mysql_query("SELECT id, title, folder FROM elements WHERE id='$documentData[folder]'");
            $documentElementData = mysql_fetch_array($documentElementSQL);
			
			$path = getFolderPath($documentElementData['folder']);
			$path .= $documentData['filename'];
			
            return $path;
    }
	 function validateTempFile($privacy){
                $fileId = $this->id;
	 	$path = $this->getFilePath();
		$oldpath = $path.'.temp';
		if(rename($oldpath, $path)){
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
            $folderArray = loadFolderArray("path", $documentElementData['folder']);
            $folderArray = array_reverse($folderArray['names'], true);
            foreach($folderArray as &$folder){
                
                $path .= "$folder/";
            }
            
            
                $documentFolderSQL = mysql_query("SELECT * FROM folders WHERE id='".save($documentElementData['folder'])."'");
                $documentFolderData = mysql_fetch_array($documentFolderSQL);

                $path = urldecode($path);
                $filePath = $path.$documentData['filename'];
                return $filePath;
    }

function getFileData(){
    $fileId = $this->id;
		$fileData = mysql_fetch_array(mysql_query("SELECT * FROM files WHERE id='".save($fileId)."'"));
		return $fileData;
	}

function getTitle(){
		$fileData = $this->getFileData();
		return $fileData['title'];
	}


function getFileType($fileId){
    $fileId = $this->id;
        $fileData = mysql_fetch_array(mysql_query("SELECT type FROM files WHERE id='".save($fileId)."'"));
        return $fileData['type'];
    }
    
}

	
    
    
    
	 
         
    

    
function linkIdToFileType($fileId){
        
        $fileData = mysql_fetch_array(mysql_query("SELECT type FROM links WHERE id='".save($fileId)."'"));
        return $fileData['type'];
    }

