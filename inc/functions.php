<?php

include('classes/class_user.php');
  $userid = getUser();
  $time = time();

  class debug{
      function write($param){
          
      }
  }
  
  // Thumbnails: [url]www.codeschnipsel.net[/url]
function mkthumb($img_src,$img_width, $img_height, $folder_scr,  $des_src){
    // Größe und Typ ermitteln
    list($src_width, $src_height, $src_typ) = getimagesize($folder_scr."/".$img_src);

    // neue Größe bestimmen
    if($src_width >= $src_height)
    {
      $new_image_width = $img_width;
      $new_image_height = $src_height * $img_width / $src_width;
    }
    if($src_width < $src_height)
    {
      $new_image_height = $img_width;
      $new_image_width = $src_width * $img_height / $src_height;
    }

    if($src_typ == 1)     // GIF
    {
      $image = imagecreatefromgif($folder_scr."/".$img_src);
      $new_image = imagecreate($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagegif($new_image, $des_src."/".$img_src, 100);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    elseif($src_typ == 2) // JPG
    {
      $image = imagecreatefromjpeg($folder_scr."/".$img_src);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagejpeg($new_image, $des_src."/".$img_src, 100);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    elseif($src_typ == 3) // PNG
    {
      $image = imagecreatefrompng($folder_scr."/".$img_src);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagepng($new_image, $des_src."/".$img_src);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    else
    {
      return false;
    }
  }
/**
  * Validates if SESSION of last generated Captcha is equal to the submitted value
  *
  * @param string $value      Contains a user-provided query.
  *
  * @return bool Contains the returned rows from the query.
  */
function validateCapatcha($value){
     
     $sessionValue = $_SESSION['lastCaptcha'];
	 
        //define crypt
        $value = sha1($value);
        $value = sha1("$value deine mutter lutscht riesengrosse schwaenze");
        
	if($sessionValue === sha1($value)){
            return true;
        }
	
 }
 
 
include('classes/class_xml.php');

include('classes/class_rss.php');

include('classes/class_salt.php');

  
	

include('classes/class_gui.php');
include('classes/class_messages.php');

   
//fav
//fav
//fav
include('classes/class_fav.php');

//personal Events
//personal Events
//personal Events
include('classes/class_personalEvents.php');
    
//comments
//comments
//comments	
include('classes/class_comments.php');

//groups
//groups
//groups

include('classes/class_groups.php');

//basic universe stuff
     
include('classes/class_item.php');

    



//buddylist
//buddylist
//buddylist

	
include('classes/class_buddylist.php');


//feed
//feed
//feed
    include('classes/class_feed.php');

    
   
   
   include('classes/class_universe.php');
   
   include('classes/class_db.php');
    
    include('classes/class_privacy.php');
    include('classes/class_playlists.php');
    
    
function deleteFolder($folderId){
        
                $foldersql = mysql_query("SELECT * FROM folders WHERE id='$folderId'");
                $folderData = mysql_fetch_array($foldersql);
                
                
                //select and delete folders which are children of this folder
                $childrenFolderSQL = mysql_query("SELECT id FROM folders WHERE folder='$folderId'");
                while($childrenFolderData = mysql_fetch_array($childrenFolderSQL)){
                    deleteFolder($childrenFolderData[id]);
                }
                //select and delete element which are children of this folder
                $childrenElementSQL = mysql_query("SELECT id FROM elements WHERE folder='$folderId'");
                while($childrenElementData = mysql_fetch_array($childrenElementSQL)){
                    $element = new element($childrenElementData['id']);
                    $element->delete();
                }
                $folderpath = getFolderPath($folderId);
                mysql_query("DELETE FROM folders WHERE id='$folderId'");
                system('/bin/rm -rf ' . escapeshellarg($folderpath));
                
                //delete comments, feeds and shortcuts
                $commentClass = new comments();
                $commentClass->deleteComments("folder", $folderId);
                deleteFeeds("folder", $folderId);
                deleteInternLinks("folder", $folderId);
				
				jsAlert("The folder has been deleted.");
				return true;
        
    }


function uploadTempfile($file, $element, $folder, $privacy, $user, $lang=NULL, $download=true){
	 	
	 	//upload file
        $target_path = basename( $file['tmp_name']);
        $filename = $file['name'];
        $thumbname = "$filename.thumb";
        $size = $file['size'];
        $time = time();
		
	    $type = getMime($filename);
        
		
        $FileElementSQL = mysql_query("SELECT title, folder FROM elements WHERE id='$element'");
        $FileElementData = mysql_fetch_array($FileElementSQL);
		
		if(empty($folder)){
			$folder = $FileElementData['folder'];
		}
		
        $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='".$FileElementData['folder']."'");
        $fileFolderData = mysql_fetch_array($filefolderSQL);

            $folderpath = getFolderPath($folder);
			
			
            $thumbPath = $folderpath."thumbs/";
            $imgName = basename($FileElementData['title']."_".$file['name']);
            $elementName = rawurlencode($FileElementData['title'].'_');
            $finalName = $FileElementData['title']."_".$file['name'];
 
			
			//move uploaded file to choosen folder and add .temp 
            move_uploaded_file($file['tmp_name'], "./".$folderpath.$file['name']);
            rename( "./".$folderpath.$file['name'], "./".$folderpath.$finalName);
			
			//if type is image => create thumbnail before .temp suffix is added
	        if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){
	                    $thumbPath= "$thumbPath";
	                    $path = "$folderpath";
	                    if(is_dir("$thumbPath")){
	                        mkthumb("$finalName",300,300,$path,"$thumbPath");
	                    } else{
	                        mkdir("$thumbPath");
	                        mkthumb("$finalName",300,300,$path,"$thumbPath");
	                    }
	        }
            rename( "./".$folderpath.$finalName, "./".$folderpath.$finalName.".temp");
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
		
	    $type = getMime($filename);
        
        $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$folder'");
            $fileFolderData = mysql_fetch_array($filefolderSQL);
            $FileElementSQL = mysql_query("SELECT title FROM elements WHERE id='$element'");
            $FileElementData = mysql_fetch_array($FileElementSQL);

            $folderpath = getFolderPath($folder);
			
			
            $thumbPath = "../../".$folderpath."thumbs/";
            $imgName = basename($file['name']);
            $elementName = "$FileElementData[title]_";
            $imgName = "$elementName$imgName";
 


            move_uploaded_file($file['tmp_name'], "../../".$folderpath.$file['name']);
            rename( "../../".$folderpath.$filename, "../../".$folderpath.$imgName);
        if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){
                    $thumbPath= "$thumbPath";
                    $path = "../../$folderpath";
                    if(is_dir("$thumbPath")){
                        mkthumb("$imgName",300,300,$path,"$thumbPath");
                    } else{
                        mkdir("$thumbPath");
                        mkthumb("$imgName",300,300,$path,"$thumbPath");
                    }
        }
		if(mysql_query("INSERT INTO `files` (`id` ,`folder` ,`title` ,`size` ,`timestamp` ,`filename` ,`language` ,`type` ,`owner` ,`votes` ,`score` ,`privacy` ,`var1` , `download`) VALUES (NULL ,  '$element',  '".mysql_real_escape_string($imgName)."',  '$size',  '$time',  '".mysql_real_escape_string($imgName)."',  '$lang',  '$type',  '$user',  '0',  '0',  '$privacy',  '', '$download');")){
        	
        jsAlert("The file has been uploaded :)");
        }else{
        	jsAlert("Opps, something went wrong.");
        }
        ?>
            <script>
                parent.addAjaxContentToTab('<?=substr($FileElementData['title'], 0, 10);?>', 'modules/filesystem/showElement.php?element=<?=$element;?>&reload=1');
            </script>
        <?php
        $time = time();
        
        //add feed
        $fileId = mysql_insert_id();
        $feed = "has uploaded a file";
        createFeed($user, $feed, "", "showThumb", $privacy, "file", $fileId);
        
    }
    
    
    
function deleteFile($fileId){
                $fileSql = mysql_query("SELECT * FROM files WHERE id='$fileId'");
                $fileData = mysql_fetch_array($fileSql);
                    $fileElementSql = mysql_query("SELECT id, title, folder FROM elements WHERE id='$fileData[folder]'");
                    $fileElementData = mysql_fetch_array($fileElementSql);
                    
                    $type = $fileData['type'];
                    
                    //for all standard files
                    $title = $fileData['filename'];
                    
                    
                   
                    
                //file can only be deleted if uploader = deleter
               	if(authorize($fileData['privacy'], "edit")){
                    $folderPath = getFolderPath($fileElementData['folder']);
                    $filePath = getFullFilePath($fileId);
                    $thumbPath = "$folderPath"."thumbs/$title";
                    if(unlink("$filePath")){
                    	if(mysql_query("DELETE FROM files WHERE id='$fileId'")){
                           
                           //delete comments
                           $commentClass = new comments();
                           $commentClass->deleteComments("file", $fileId);
                           deleteFeeds("file", $fileId);
                           deleteInternLinks("file", $fileId);
                           
                           //delete thumbnails
                           if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png"){
                               
                               if(unlink("$thumbPath")){
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
    
include('classes/class_files.php');

include('classes/class_youtube.php');
    
//links
//links
//links
include('classes/class_links.php');
	
	
//shortcuts
//shortcuts
//shortcuts

include('classes/class_internLink.php');

//folders
include('classes/class_folder.php');

//elements
include('classes/class_element.php');

include('classes/class_fileSystem.php');


//loads content from UFF
	
include('classes/class_UFF.php');

include('classes/class_contextMenu.php');

include('classes/class_dashboard.php');

include('classes/class_signatures.php');
include('classes/class_sessionHashes.php');

include('classes/class_sec.php');

include('classes/class_events.php');
include('classes/class_tasks.php');




class api{
	public function useridToUsername($request){
		if(is_numeric($request))
			//only a single request
			return useridToUsername($request);
		else {
			//array of requests
			$userids = json_decode($request,true);
			
			foreach($userids as $userid){
				$ret[$userid] = useridToUsername($userid);
			}
			
			return json_encode($ret);
			
		}
	}
	public function useridToRealname($request){
		if(is_numeric($request))
			//only a single request
			return useridToRealname($request);
		else {
			//array of requests
			$userids = json_decode($request,true);
			
			foreach($userids as $userid){
				$ret[$userid] = useridToRealname($userid).' ';
			}
			
			return json_encode($ret);
			
		}
	}
	public function searchUserByString($string, $limit){
		$q = save($string);
		$k = save($limit);
		$userSuggestSQL = mysql_query("SELECT userid, username, realname FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT 0,10");
		while ($suggestData = mysql_fetch_array($userSuggestSQL)) {
			
			
			if(!isset($return[$userid])){		//only return every user once
				
				$userid = $suggestData['userid'];
				$array[] = $suggestData['username'];
				$array[] = $suggestData['realname'];
				
				$return[$userid] = $array;		//add user data tu return array
				
			}
		}
		
		return $return;
	}
	
	//returns base64 string with userdata
	public function getUserPicture($request){
		//single userid
		if(is_numeric($request)){
			$userids[] = save($request);
			$numeric = true;
		}else {
			$numeric = false;
			//array of requests
			$userids = json_decode($request,true);
		}
			foreach($userids AS $userid){
				
				$userData = getUserData($userid);
			
				//check if user is standard user
				if(empty($userData['userPicture'])){
					$src = 'gfx/standardusersm.png';
				}else{
					$src = 'upload/userFiles/'.$userid.'/userPictures/thumb/40/'.$userData['userPicture'];
				}
				$mime = mime_content_type($src);
				
			    $file = fopen($src, 'r');
			    $output = base64_encode(fread($file, filesize($src)));
				$return[$userid] = 'data:'.$mime.';base64,'.$output;
				
			}
			
			if($numeric){
				return $return[$request];
			}else{
				return json_encode($return);
			}
			
		
	
	}
	public function getLastActivity($request){
		
		//single userid
		if(is_numeric($request)){
			$userids[] = save($request);
			$numeric = true;
		}else {
			//array of requests
			$userids = json_decode($request,true);
		}
			foreach($userids AS $userid){
				
				$userid = save($userid);
		        $data = mysql_fetch_array(mysql_query("SELECT lastactivity FROM user WHERE userid='$userid'"));
				$diff = time() - $data['lastactivity'];
				if($diff < 90){
					$return[$userid] =  1;
				}else{
					$return[$userid] =  0;
				}
				
			}
			
			if($numeric){
				return $return[$request];
			}else{
				return json_encode($return);
			}
			
		
	}
}
?>