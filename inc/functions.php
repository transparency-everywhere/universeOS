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

//shows the settings button for folders, elements, files, playlists and posts.
function showItemSettings($type, $itemId){
        
      $contextMenu = new contextMenu($type, $itemId, '', '');
	  $output = $contextMenu->showItemSettings();
	  return $output;
    }
function showRightClickMenu($type, $itemId, $title, $info1=NULL){
     
     $contextMenu = new contextMenu($type, $itemId, $title, $info1);
	  $output = $contextMenu->showRightClick();
	  echo $output;
 }


include('classes/class_salt.php');

function createUser($username, $password, $authSalt, $keySalt, $privateKey, $publicKey){
    
    $username = save($_POST['username']);
    $sql = mysql_query("SELECT username FROM user WHERE username='$username'");
    $data = mysql_fetch_array($sql);
    
    if(empty($data['username'])){
        $time = time();
        mysql_query("INSERT INTO `user` (`password`, `cypher`, `username`, `email`, `regdate`, `lastactivity`) VALUES ('$password', 'sha512_2', '$username', '', '$time', '$time')");
        $userid = mysql_insert_id();
		
		//store salts
		createSalt('auth', $userid, 'user', $userid, $authSalt);
		createSalt('privateKey', $userid, 'user', $userid, $keySalt);
			
		//create signature
		$sig = new signatures();
		$sig->create('user', $userid, $privateKey, $publicKey);
		
        //create user folder(name=userid) in folder userFiles
        $userFolder = createFolder("2", $userid, $userid, "h");
        
        //create folder for userpics in user folder
        $pictureFolder = createFolder($userFolder, "userPictures", $userid, "h");
        
        //create thumb folders || NOT LISTED IN DB!
        $path3 = ".//upload//userFiles//$userid//userPictures//thumb";
        $path4 = ".//upload//userFiles//$userid//userPictures//thumb//25";
        $path5 = ".//upload//userFiles//$userid//userPictures//thumb//40";
        $path6 = ".//upload//userFiles//$userid//userPictures//thumb//300";
        mkdir($path3);  //Creates Thumbnail Folder
        mkdir($path4); //Creates Thumbnail Folder
        mkdir($path5); //Creates Thumbnail Folder
        mkdir($path6); //Creates Thumbnail Folder
        
        
        //create Element "myFiles" in userFolder
        $myFiles = createElement($userFolder, "myFiles", "myFiles", $userid, "h");
        
        //create Element "user pictures" to collect profile pictures
        $pictureElement = createElement($pictureFolder, "profile pictures", "image", $userid, "p");


        mysql_query("UPDATE user SET homefolder='$userFolder', myFiles='$myFiles', profilepictureelement='$pictureElement' WHERE userid='$userid'");

        return true;
    }
      
  }

function deleteUser($userid, $reason){
      $authorization = true;
      if($authorization){
          
          //delete all files
          $fileSQL = mysql_query("SELECT id FROM files WHERE owner='$userid'");
          while($fileData = mysql_fetch_array($fileSQL)){
              deleteFile($fileData['id']);
              
          }
          
          //delete all links
          $linkSQL = mysql_query("SELECT id FROM links WHERE author='$userid'");
          while($linkData = mysql_fetch_array($linkSQL)){
              deleteLink($linkData['userid']);
          }
          
          
          //elements
          $elementSQL = mysql_query("SELECT id FROM elements WHERE author='$userid'");
          while($elementData = mysql_fetch_array($elementSQL)){
              deleteElement($elementData['id']);
          }
          
          
          //folders
          $folderSQL = mysql_query("SELECT id FROM folders creator='$userid'");
          while($folderData = mysql_fetch_array($folderSQL)){
              deleteFolder($folderData['id']);
          }
          
          //comments
          
          
          //buddy
          mysql_query("DELETE FROM buddylist WHERE buddy='$userid' OR owner='$userid'");
          
          //delete user
          mysql_query("DELETE FROM user WHERE userid='$userid'");
         
          
          //log userid, username, reason
          
      }
  }

function showUserPicture($userid, $size, $subpath = NULL, $small = NULL /*defines if functions returns or echos and if script with bordercolor is loaded*/){
    $picSQL = mysql_query("SELECT userid, lastactivity, userPicture, priv_profilePicture FROM user WHERE userid='".mysql_real_escape_string($userid)."'");
    $picData = mysql_fetch_array($picSQL);
    $time = time();
    
    $difference = ($time - $picData['lastactivity']);
     if($difference < 90){
        $color = "#B1FFAD";
     }else{
        $color = "#FD5E48";
     }
     
    if(isset($subpath)) {
        if($subpath !== "total"){
        $path = "./../.";
        $subPath = 1;
        }
        
    }else{
        $subPath = NULL;
    }
      
        
        $style = '';
        //there are three different thumb sizes which are created when
        //the userpicture is uploaded, depending on the requested size
        //a different thumb needs to be choosen to minimize traffic
        if($size < "25"){
            $folderpath = "25";

        } else if($size < "40"){
            $folderpath = "40";

        } else if($size < "300"){
            $folderpath = "300";

        }
		$size.="px";
    
    if(empty($picData['userPicture'])){
        
        
    	$class = "standardUser";
    
    }else{
        
		$class = "";
		
        if($subpath !== "total"){
            
            $src = "$path./upload/userFiles/$userid/userPictures/thumb/$folderpath/".$picData['userPicture']."";
			if(empty($class)){
            	$style = "background-image: url('$src');";
			}
        }else{
            $src = "./upload/userFiles/$userid/userPictures/thumb/$folderpath/".$picData['userPicture']."";
			if(empty($class)){
            	$style = "background-image: url('$src');";
			}
        }
        
    }
       

        if($subpath !== "total"){
            
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus('$userid', '$color');\" onclick=\"showProfile('$userid');\" style=\"width: $size; height: $size; border-color: $color; $style\"></div>";
        }else{
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus('$userid', '$color');\" onclick=\"showProfile('$userid;');\" style=\"width: $size; height: $size; $style border-color: $color; $style\"></div>";
        }
      
      
      
      
        if($small){
        	if(!empty($picData['userPicture'])){
        		$style = " background-image: url(\\'$src\\');";
				if($small == 'unescaped'){
        			$style = " background-image: url(\\'$src\\');";
				}
			}
			
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus(\'jjj$userid\', \\'$color\\');\" onclick=\"showProfile(\\'$userid\\');\" style=\"$style width: $size; height: $size; border-color: $color;\"></div>";

            return $return;
        }else{
            echo $return;
        }
}
	

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

    
   function favTable($type){
       if($type == "folder"){
           $typeTable = "folders";
       }else if($type == "element"){
           $typeTable = "elements";
       }else if($type == "file"){
           $typeTable = "files";
       }
       
       echo $typeTable;
   }
   
   
   include('classes/class_universe.php');
   
   include('classes/class_db.php');
    
    include('classes/class_privacy.php');
    include('classes/class_playlists.php');
    
	
	
    
	

    
	function createFolder($superiorFolder, $title, $user, $privacy){
		
		if(strpos($title, '/') == false){
			$titleURL = urlencode($title);
			
			$title = mysql_real_escape_string($title);
		    
		    $foldersql = mysql_query("SELECT * FROM folders WHERE id='$superiorFolder'");
		    $folderData = mysql_fetch_array($foldersql);
		
		    $folderpath = getFolderPath($superiorFolder).urldecode("$titleURL");
			if (!file_exists("$folderpath")) {
		    mkdir($folderpath);
		    $time = time();
		    mysql_query("INSERT INTO `folders` (`folder`, `name`, `path`, `creator`, `timestamp`, `privacy`) VALUES ( '$superiorFolder', '$title', '$folderpath', '$user', '$time', '$privacy');");
		    $folderId = mysql_insert_id();
		    $feed = "has created a folder";
		    createFeed($user, $feed, "", "showThumb", $privacy, "folder", $folderId);
		    //return true;
		    
		    return $folderId;
			}else{
				jsAlert("The folder already exists.");
			}
		}else{
			jsAlert("The title contains forbidden characters.");
		}
    }
    
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
                    deleteElement($childrenElementData[id]);
                }
                $folderpath = getFolderPath($folderId);
                mysql_query("DELETE FROM folders WHERE id='$folderId'");
                system('/bin/rm -rf ' . escapeshellarg($folderpath));
                
                //delete comments, feeds and shortcuts
                deleteComments("folder", $folderId);
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
                           deleteComments("file", $fileId);
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

    
function openFile($fileId=NULL, $linkId=NULL, $type=NULL, $title=NULL, $typeInfo=NULL, $extraInfo1=NULL, $extraInfo2=NULL,  $extraInfo3=NULL, $extraInfo4=NULL, $extraInfo5=NULL, $subpath){
        
        
        
        
        //choose if function needs to handle real file
        //or a link
        
        //youtube, wiki, rss etc. actually needs a linkId
        //but in this case the handler for links still needs
        //to be called
        if(!empty($linkId) || $type == "wiki" || $type == "RSS" || $type == "youtTube")
        	$isLink = true;
		if(!empty($fileId)) 
			$isFile = true;
		
		
		
       	if($isFile){
            //get filedata
            $fileQuery = mysql_query("SELECT * FROM files WHERE id='$fileId'");
            $fileData = mysql_fetch_array($fileQuery);
			
			$privacy = $fileData['privacy'];
			$user = $fileData['owner'];
			
			$elementQuery = mysql_query("SELECT * FROM elements WHERE id='$fileData[folder]'");
			$elementData = mysql_fetch_array($elementQuery);
            
          	$title = $fileData['title'];
			$element = $fileData['folder'];
			$elementTitle = $elementData['title'];
			if($fileData['download']){
				$download = "<a href=\"./out/download/?fileId=$fileId\" target=\"submitter\" class=\"btn btn-mini\" title=\"download file\"><img src=\"$subpath"."./gfx/icons/download.png\" alt=\"download\" height=\"10\"></a>";
			}
            $filename = $fileData['filename'];
            $path = $subpath.getFilePath($fileId);
            
			
    		$score = showScore("file", $fileId);
            
            //check type if type is undefined get type from db
            if($type == NULL){
            $type = $fileData['type'];
            }
            
        }
        if($isLink){
        	//extra handler for extarnal links
			if(empty($linkId))
			
				//check if link is "external" or if data needs to be taken out of the db
				//if $type = wiki, linkId is empty but its still an external source
				if(is_int($linkId) || $type == "wiki")  
				$extarnal = true;
			
				if(!$extarnal){
		            //get linkdata
		            $linkQuery = mysql_query("SELECT * FROM links WHERE id='$linkId'");
		            $linkData = mysql_fetch_array($linkQuery);
					$privacy = $linkData['privacy'];
					$user = $linkData['author'];
			
		            
		            $title = $linkData['title'];
		            
    				$score = showScore("link", $linkId);
					
		            
		            //define type if type is undefined
		            if(empty($type)){
		            $type = $linkData['type'];
		            }
					if($type == "youTube"){
						//generate link out of youtubelink
						$vId = youTubeURLs($linkData[link]);
					}
				}
        }
		
		if($type == "youTube"){
			
			//GET YOUTUBE VIDEO ID
			
			if(!empty($vId)){
			$vId = "$typeInfo";
			}
			//if $vId is still empty the youtube videoId is passed
			//through $typeInfo
			else{
			$vId = "$typeInfo";
			}
			
			//get title from youtube server
			if(empty($title)){
				$title = youTubeIdToTitle($vId);
			}
			
			//optional options
			$playlist = $extraInfo1;
			$row = $extraInfo2;
			
			//add playlistdropdown to add video
			// to a playlist to the header
			if(proofLogin()){
			//get all the playlists
			$playlists = getUserPlaylistArray('', 'edit');
			//init form and select
			$options = "<form action=\"doit.php?action=addYouTubeItemToPlaylistVeryLongName&vId=$vId\" target=\"submitter\" method=\"post\"><select name=\"playlistId\">";
			foreach ($playlists['ids'] as $key => $id){
				
				
                    //add options to dropdown
                    $options .= "<option value=\"$id\">".$playlists['titles'][$key]."</option>";
				
			}
			//close select, form and and submit button
			$options .= "</select><input type=\"submit\" name=\"submit\" value=\"add\" class=\"btn btn-mini\" style=\"margin-top: -11px;\"></form>";
			}
			$controls = $options;
			
			//extraInfo1 is a playlist
			if(!empty($extraInfo1)){
				//id of fileWindow needs to have playlistId inside
				//so that playlistplayer.php can proof if playlist
				//is already opened
				$fileWindowId = "playListFrame_$extraInfo1";
				$iframeId = "playListiFrame_$extraInfo1";
				$titleClass .= "playlist_$extraInfo1";
				
				//title needs to contain span with playlistid, so it can
				//be updated from the iframe, in which the youtubevideo
				//is located(doit => showYoutube)
	            $title = "<span id=\"togglePlayListTitle_$extraInfo1\" class=\"readerPlayListTitle\">".addslashes($title)."</span>";
			
			
				$bar .= "<div id=\"togglePlayList_$extraInfo1\" class=\"readerPlayListToggle\"></div>";
				
				
				
			}
		}
		
		if(authorize($privacy, 'show', $user) OR $type == "youTube" OR $type == "wiki"){
	        if($type == "image/jpeg" || $type == "image/png" || $type == "image" ){
	        	
				$type = 'image';
				
	        	//add zoom buttons to header
	    		$bar = "<a href=\"javascript: zoomIn('$element');\" id=\"zoomIn\" class=\"btn btn-mini\" title=\"zoom in\"><img src=\"$subpath"."gfx/icons/zoomIn.png\" height=\"10\" border=\"0\"></a>&nbsp;<a id=\"zoomOut\" href=\"javascript: zoomOut('$element');\" class=\"btn btn-mini\" style=\"\" title=\"zoom out\"><img src=\"$subpath"."gfx/icons/zoomOut.png\" height=\"10\" border=\"0\"></a>";
	        }
			
			if($type == "audio/mpeg")
				$type = 'audio';
			
	        $icon = getFileIcon($type);
	        $icon = "<img src=\"$subpath"."gfx/icons/fileIcons/$icon\" height=\"20\">";
	        
	        $output .= "<header class=\"gray-gradient\">";
	        $output .= $icon;
	        $output .= "<span class=\"title\">$title $type</span>";
			$output .= "<span class=\"controls\">$controls</span>";
			$output .= "<span class=\"bar\">$bar</span>";
			$output .= "<span class=\"score\">$score</span>";
	        $output .= "<span class=\"download\">$download</span>";
	        $output .= "</header>";
	        $output .= "<div class=\"fileWindow\" id=\"$fileWindowId\">";
	        
	        switch($type){
	            //link types
	            case youTube:
				  
				      
					  
					  //DISPLAY VIDEO
					  //define content
				      $output .= '<div class="iframeFrame" style="background: #000000;">';
					  //show an iframe which loads the openyoutube...() function via the doit.php
				      $output .= "<iframe src=\"doit.php?action=showYoutube&id=$vId&playList=$playlist&row=$row\" id=\"$iframeId\" name=\"playListLoaderFrame\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"></iframe>";
				      $output .= '</div>';
					
					
					
	                break;
	            case wiki:
					
		       		$title = urlencode($title);
		            $wikiUrl = "http://en.wikipedia.org/w/index.php?title=$title&printable=yes";
					
					
	                $output .= "<div class=\"iframeFrame\">";
		            $output .= "<iframe frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"  src=\"$wikiUrl\"></iframe>";
		            $output .= "</div>";
	                break;
	            case RSS:
					
					
					if(!empty($linkData[link])){
			        	$url = $linkData[link];
						$title = $linkData[title];
					}else{
						$url = $typeInfo;
						$title = $extraInfo1;
					}
			        $output .= "<div class=\"rssReader windowContent\">";
			        $output .= getRssfeed("$url","$title","auto",10,3);
			        $output .= "</div>";
					
	                break;
	            
	            //real file types
	            //documents
				case UFF:
							if(authorize($fileData['privacy'], "edit", $fileData['owner'])){
							    $readOnly = "false";
							}else{
							    $readOnly = "true";
							}
							
							$title = $fileData['title'];
							$activeUsers = explode(";", $fileData['var1']);
							//this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js
							//dirty solution!!!
							$output .= "<iframe src=\"modules/reader/UFF/javascript.php?fileId=$fileId&readOnly=$readOnly\" style=\"display:none;\"></iframe>";
							$output .= "<div class=\"uffViewerNav\">";
								$output .= "<div style=\"margin: 10px;\">";
									$output .= "<ul>";
							            $output .= '<li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon-user"></i>&nbsp;<strong>Active Users</strong></li>';
							            //show active users
							            foreach($activeUsers AS &$activeUser){
							                if(!empty($activeUser)){
							                $output .= "<li onclick=\"openProfile($activeUser);\" style=\"cursor: pointer;\">";
							                //$output .= showUserPicture($activeUser, "11");
							                $output .=  "&nbsp;";
							                $output .=  useridToUsername($activeUser);
							                $output .= "</li>";
							                }
							            }
									$output .= "</ul>";
								$output .= "</div>";
							$output .= "</div>";
							//document frame
							$output .= "<div class=\"uffViewerMain\">";
								$output .= "<textarea class=\"uffViewer_$fileId WYSIWYGeditor\" id=\"editor1\">";
								$output .= "</textarea>";
							$output .= "</div>";
					break;
	            case 'text/plain':
	                
	                if($subpath == '../')
	                	$filePath = urldecode("$path");
					else
						$filePath = urldecode("../../$path");
	
	                $file = fopen($filePath, 'r');
	                $output .= nl2br(htmlentities(fread($file, filesize($filePath))));
	                fclose($file);
	                
	                break;
	            
	            case 'application/pdf':
	                $output .= "<div class=\"iframeFrame\">";
	                $output .= "<iframe src=\"./$path\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"></iframe>";
	                $output .= "</div>";
	                
	                break;
	            
	            
	            //pictures
	            case 'image':
					
					$output .= "<div id=\"ImageViewer_$element\" class=\"readerImageTab\">";
					$output .= "<center>";
					$output .= "<img src=\"$path\" width=\"100%\" id=\"viewedPicture_$element\">";
					$output .= "</center>";
					$output .= "</div>";
					
					$output .= '<div style="position: absolute; height: 120px; right:0px; bottom: 0px; left: 0px; overflow: auto; background: #000; color: #FFF;">';
	
						$output .= '<table style="width: 100%; height: 120px;" align="center" class="blackGradient">';
						
						$output .= '<tr>';
						
						
				        $documentSQL = mysql_query("SELECT id, title, folder, privacy, owner FROM files WHERE folder='$elementData[id]' AND type IN('image/png','image/jpeg','image')");
				        while($documentData = mysql_fetch_array($documentSQL)){
	        				if(authorize($documentData['privacy'], "show", $documentData['owner'])){
						        //$documentFolderSQL = mysql_query("SELECT path FROM folders WHERE id='$elementData[folder]'");
						        //$documentFolderData = mysql_fetch_array($documentFolderSQL);
						        if($elementData['title'] == "profile pictures"){
						        	$thumbPath = $subpath.getFolderPath($elementData['folder']);    
						        	$thumbPath = "$thumbPath/thumb/300/";
						        }else{
						        	$thumbPath = $subpath.getFolderPath($elementData['folder'])."thumbs/";
						        }
								
								
						        $output .= "<td onmouseup=\"showMenu('image$documentData[id]')\" oncontextmenu=\"showMenu('image$documentData[id]'); return false;\"><div id=\"viewerClick$documentData[id]\"><a href=\"#\" onclick=\"addAjaxContentToTab('Open ".substr("$elementTitle", 0, 10)."','./modules/reader/openFile.php?type=image&fileId=$documentData[id]');return false\"><img src=\"$thumbPath$documentData[title]\" height=\"100px\"></a></div></td>";   
				       		}
				        }
						$output .= "</tr>";
						$output .= "</table>";
						$ouput .="</div>";
					
					
					
					
	                break;
	            
	            case 'video':
					$output .= "<video src=\"$path\" controls>";
	                break;
	            
	            case 'image/tiff':
	                break;
	            
	            //audio
	            case 'audio':
					$output .= "<audio controls>
								  <source src=\"$path\" type=\"audio/mpeg\">
								</audio>";
	                break;
	            
	        }
	        $output .= "</div>";
	    	}
        
        return $output;
    }


function showFileBrowser($folder, $folderQuery=NULL, $elementQuery=NULL, $rightClick=true, $subpath=NULL){
        echo'<table cellspacing="0" class="filetable">';
        
        //if folder is empty => execute folder- and elementQuery
        if(empty($folder)){
            
            if(isset($folderQuery)){
                $query = $folderQuery;
            }
            
            if(isset($folderQuery)){
                $query2 = $elementQuery;
            }
            
            
        }else{
            
			//get userfolder
			$userData = getUserData();
			$userfolder = $userData['homefolder'];
			
			
			//special folder handlers
			
			//userFolder
			if($folder == "2"){
				$folder = $userfolder;
				$parentFolderData['folder'] = 1;
			}
			
			if($folder == $userfolder){
				$parentFolderData['folder'] = 1;
			}
			
			
            $query = "WHERE folder='$folder' ORDER BY name ASC";
            $query2 = "WHERE folder='$folder' ORDER BY name ASC";
            $shortCutQuery = "WHERE parentType='folder' AND parentId='$folder'";
        }


        if(!empty($query)){
        	if(!empty($folder) && ($folder !== "1")){
        		if($parentFolderData['folder'] !== 1)
        			$parentFolderData = mysql_fetch_array(mysql_query("SELECT folder FROM folders WHERE id='".mysql_real_escape_string($folder)."'"));
        		?>
	            <tr class="strippedRow" height="30">
	                <td width="30">&nbsp;<img src="<?=$subpath;?>gfx/icons/filesystem/folder.png" height="22"></td>
	                <td><a href="<?=$subpath;?>out/?folder=<?=$parentFolderData['folder'];?>" onclick="openFolder('<?=$parentFolderData['folder'];?>'); return false;">...</a></td>
	                <td width="50px">
	                </td>
	                <td width="50px"></td>
	            </tr>
        		<?php
        	}
        $filefsql = mysql_query("SELECT * FROM folders $query");
        while($filefdata = mysql_fetch_array($filefsql)) {
        if(authorize($filefdata['privacy'], "show", $filefdata['creator'])){
        	
        		$name = $filefdata['name'];
        	//special folder handlers
        	if($folder == 3){
        		$name = getGroupName($filefdata['name']).'´s Files';
			}
			
        ?>
            <tr class="strippedRow" oncontextmenu="showMenu('folder<?=$filefdata['id'];?>'); return false;" height="30">
                <td width="30"><?php
            	if($rightClick){
            	showRightClickMenu("folder", $filefdata['id'], $filefdata['name'], $filefdata['creator']);
            	}?>&nbsp;<img src="<?=$subpath;?>gfx/icons/filesystem/folder.png" height="22"></td>
                <td><a href="<?=$subpath;?>out/?folder=<?=$filefdata[id];?>" onclick="openFolder('<?=$filefdata['id'];?>'); return false;"><?=$name;?></a></td>
                <td width="50px">
                	<?php
                	if($rightClick){
                	echo showItemSettings('folder', $filefdata['id']);
					}
                	?>
                </td>
                <td width="50px"><?=showScore("folder", $filefdata['id']);?></td>
            </tr>
            <?php
            }}}
            $filedsql = mysql_query("SELECT * FROM elements $query2");
            while($fileddata = mysql_fetch_array($filedsql)) {

            $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$folder'");
            $fileFolderData = mysql_fetch_array($filefolderSQL);
            $title = $fileddata['title'];
            $title10 = substr("$title", 0, 10);
            $title15 = substr("$title", 0, 25);

            if(authorize($fileddata['privacy'], "show", $fileddata['author'])){
            echo "<tr class=\"strippedRow\" oncontextmenu=\"showMenu('element".$fileddata['id']."'); return false;\" height=\"30\">";
	            echo "<td width=\"30\">&nbsp;<img src=\"$subpath"."gfx/icons/filesystem/element.png\" height=\"22\"></td>";
	            echo "<td><a href=\"$subpath"."out/?element=".$fileddata['id']."\" onclick=\"openElement('".$fileddata['id']."', '".addslashes($title10)."'); return false;\">$title15</a></td>";
	            echo "<td width=\"80px\">";
	                	if($rightClick){
	                	echo showItemSettings('element', $fileddata['id']);
						}
	            echo "</td>";
	            echo "<td width=\"50px\">".showScore("element", $fileddata['id'])."</td>";
            echo "</tr>";
            if($rightClick){
            echo showRightClickMenu("element", $fileddata['id'], $title10, $fileddata['author']);
            }}}
            
            $shortCutSql = mysql_query("SELECT * FROM internLinks $shortCutQuery");
            while($shortCutData = mysql_fetch_array($shortCutSql)){
                if($shortCutData['type'] == "folder"){
                    
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy FROM folders WHERE id='".$shortCutData['typeId']."'"));
                    
                    $title = $shortCutItemData['name'];
                    $image = "folder.png";
                    $link = "openFolder('".$shortCutData['typeId']."'); return false;";
                    
                }else if($shortCutData['type'] == "element"){
                    
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy FROM elements WHERE id='".save($shortCutData[typeId])."'"));
                    
                    $title = $shortCutItemData['title'];
                    $image = "element.png";
                    $link = "openElement('".$shortCutData['typeId']."', '".addslashes(substr($shortCutItemData['title'],0,10))."'); return false;";
                }
                
                echo'<tr class="strippedRow">';
                    echo"<td>";
                        echo"&nbsp;<img src=\"$subpath"."/gfx/icons/filesystem/$image\" height=\"22\"><i class=\"shortcutMark\"> </i>";
                    echo"</td>";
                    echo"<td>";
                        echo"<a href=\"$subpath"."out/?$shortCutData[type]=$shortCutData[typeId]\" onclick=\"$link\">$title</a>";
                    echo"</td>";
                    echo"<td>";
                    echo showItemSettings("internLink", $shortCutData['id']);
                    echo"</td>";
                    
                echo"</tr>";
                
                
                
            }
            
            
            echo"</table>";
        
    }
    
	
function showMiniFileBrowser($folder=NULL, $element=NULL, $level, $showGrid=true, $select=NULL){
		
		//$level is used to give the list a regular margin
		//each time a new list is loaded inside the old one
		if(empty($level)){
			$level = 0;
		}
		$margin = $level*10;
		if(!empty($margin)){
			$style = "style=\"padding-left: $margin"."px;\"";
		}
		$level++;
		
		//define which buttons are shown
		$showFolderButton = true;
		$showElementButton = true;
		$showFilebutton = true;
		
		
		if($select == "folder"){
			$showElementButton = false;
			$showFilebutton = false;
		}
		if($select == "element"){
			$showFolderButton = false;
			$showFilebutton = false;
		}
		if($select == "file"){
			$showElementButton = false;
			$showFilebutton = false;
		}
		
        	
		if($showGrid){
        echo'<ul class="miniFileBrowser">';
		echo'<li class="choosenItem"></li>';
		echo'<li class="change" onclick="$(\'.miniFileBrowser .strippedRow\').slideDown();">change</li>';
		}
		
        //if folder is empty => load file list
        if($folder !== NULL && !empty($folder)){
            
            $query = "WHERE folder='$folder' ORDER BY name ASC";
            $query2 = "WHERE folder='$folder' ORDER BY name ASC";

	        	
	        $filefsql = mysql_query("SELECT * FROM folders $query");
	        while($filefdata = mysql_fetch_array($filefsql)) {
	        	
		        if(authorize($filefdata['privacy'], "show", $filefdata['creator'])){
				$action['folders'] = "$('.folder$filefdata[id]LoadingFrame').loadOuter('doit.php?action=loadMiniBrowser&folder=$filefdata[id]&level=$level&select=$select');return false;";
		        $trigger['folders'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/filesystem/folder.png\' alt=\'folder\' height=\'32px\'>&nbsp;$filefdata[name]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'folder\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$filefdata[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
				
					
		        ?>
	            <li class="strippedRow" <?=$style;?>>
	                <span>&nbsp;<img src="./gfx/icons/filesystem/folder.png" height="14"></span>
	                <span><a href="#" onclick="<?=$action['folders'];?>"><?=$filefdata['name'];?>/</a></span>
	            <?
	            if($showFolderButton){
	            ?>
	                <span class="trigger"><a href="#" onclick="<?=$trigger['folders'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
				<? } ?>
	            </li>
	            <!-- frame in which the folder data is loaded, if loadFolderDataIntoMiniBrowser() is called -->
	            <li class="folder<?=$filefdata[id];?>LoadingFrame" style="display: none;"></li>
	            <!-- keep strippedrow working-->
	            <li class="strippedRow" style="display: none;"></li>
	            <?php
	            }
			}
			
	        $filedsql = mysql_query("SELECT * FROM elements $query2");
	        while($fileddata = mysql_fetch_array($filedsql)) {
	
	        $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$folder'");
	        $fileFolderData = mysql_fetch_array($filefolderSQL);
	        $title = $fileddata['title'];
	        $title10 = substr("$title", 0, 10);
	        $title15 = substr("$title", 0, 25);
	
		        if(authorize($fileddata[privacy], "show", $fileddata[author])){
		        	
				
				$action['elements'] = "$('.element$fileddata[id]LoadingFrame').loadOuter('doit.php?action=loadMiniBrowser&element=$fileddata[id]&level=$level&select=$select');return false;";
		        $trigger['elements'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/filesystem/element.png\' alt=\'folder\' height=\'32px\'>&nbsp;$title<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'element\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileddata[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
						
		        ?>
		            <li class="strippedRow" <?=$style;?>>
		                <span>&nbsp;<img src="./gfx/icons/filesystem/element.png" height="14"></span>
		                <span><a href="#" onclick="<?=$action['elements'];?>"><?=$title15;?></a></span>
		        		<?
	           			 if($showElementButton){
	            		?>
	                    <span class="trigger"><a href="#" onclick="<?=$trigger['elements'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
	                    <? } ?>
		            </li>
		            <!-- frame in which the element data is loaded, if loadElementDataIntoMiniBrowser() is called -->
		            <li class="element<?=$fileddata[id];?>LoadingFrame" style="display: none;"></li>
		            <!-- keep strippedrow working-->
		            <li class="strippedRow" style="display: none;"></li>
		            <?php
	            }
			}
		}else if(isset($element)){
			
			
			
	        //shows list of files which are in the element $element or which meets criteria of $fileQuery
	        //if git=1 => only basic information without itemsettings etc.
	            $query = "folder='".mysql_real_escape_string($element)."'";
	        
	        
	            $fileListSQL = mysql_query("SELECT * FROM files WHERE $query");
	            while($fileListData = mysql_fetch_array($fileListSQL)) {
	                
	                if(authorize($fileListData['privacy'], "show", $fileListData['owner'])){
	                	
	                	
	                $title10 = substr($fileListData['title'], 0, 10);
	                $image = getFileIcon($fileListData['type']);
					
					$action['files'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$fileListData[title]<input type=\'hidden\' class=\'choosenType\' name=\'type\' value=\'file\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					$trigger['files'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$fileListData[title]<input type=\'hidden\' class=\'choosenType\' name=\'type\' value=\'file\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					
					
					
	                    ?>
	                    <li class="strippedRow" <?=$style;?>>
	                        <span>&nbsp;<img src="./gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="14px"></span>
	                        <span><a href="#" onclick="<?=$action['files'];?>"><?=substr($fileListData['title'],0,30);?></a></span>
	                        
			        		<?
		           			 if($showFileButton){
		            		?>
	                        <span class="trigger"><a href="#" onclick="<?=$trigger['files'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
	                        <? } ?>
	                    </li>
	                    
	                    <?php
	
	            }}
	            $linkListSQL = mysql_query("SELECT * FROM links WHERE $query");
	            while($linkListData = mysql_fetch_array($linkListSQL)) {
	            	
					
					
	                $title10 = substr("$linkListData[title]", 0, 10);
					
					$action['links'] = "alert('lol'); return false";
					
	                $image = getFileIcon($linkListData['type']);
	                
					$action['links'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$linkListData[type]\' height=\'32px\'>&nbsp;$linkListData[title]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'link\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$linkListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					$trigger['links'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$linkListData[title]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'link\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$linkListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					
					
	                    
	                ?>
	                <li class="strippedRow" <?=$style;?>>
	                    <span>&nbsp;<img src="./gfx/icons/fileIcons/<?=$image;?>" alt="<?=$linkListData['type'];?>" height="14px"></span>
	                    <span><a href="#" onclick="<?=$action['links'];?>"><?=substr($linkListData['title'],0,30);?></a></span>
			        	<?
		           			 if($showFileButton){
		            	?>
	                    <span class="trigger"><a href="#" onclick="<?=$trigger['files'];?>" class="btn btn-mini"><i class="icon-ok icon-white"></i></a></span>
	                    <? } ?>
	                </li>
	                <?php
	                } 
			
			
			
			
			
			
			
			
			
			
		}
		if($showGrid){
        echo"</ul>";
		}
		
		
		
		
		
	}

function protectFilesystemItem($type, $typeId){
		if(hasRight('protectFileSystemItems')){
			$type = save($type);
			$typeId = save($typeId);
			
			switch($type){
				case 'folder':
					
					$folderSQL = mysql_query("SELECT `privacy` FROM `folders` WHERE id='$typeId'");
					$folderData = mysql_fetch_array($folderSQL);
					
					$privacy = $folderData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
					$privacy .= ";PROTECTED";
					}
					
					mysql_query("UPDATE `folders` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case 'element':
					
					$elementSQL = mysql_query("SELECT privacy FROM elements WHERE id='$typeId'");
					$elementData = mysql_fetch_array($elementSQL);
					$privacy = $elementData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
					$privacy .= ";PROTECTED";
					}
					
					
					mysql_query("UPDATE `elements` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case 'file':
					$fileSQL = mysql_query("SELECT privacy FROM files WHERE id='$typeId'");
					$fileData = mysql_fetch_array($fileSQL);
					$privacy = $fileData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
					$privacy .= ";PROTECTED";
					}
					
					mysql_query("UPDATE `files` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
				case 'link':
					$linkSQL = mysql_query("SELECT privacy FROM links WHERE id='$typeId'");
					$linkData = mysql_fetch_array($linkSQL);
					
					
					$privacy = $linkData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
					$privacy .= ";PROTECTED";
					}
					
					
					mysql_query("UPDATE `links` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
			}
		}else{
			jsAlert("You dont have the rights to protect an Item.");
		}
	}
function removeProtectionFromFilesystemItem($type, $typeId){
		
		if(hasRight('editProtectedFilesystemItem')){
			$type = save($type);
			$typeId = save($typeId);
			
			switch($type){
				case 'folder':
					
					$folderSQL = mysql_query("SELECT `privacy` FROM `folders` WHERE id='$typeId'");
					$folderData = mysql_fetch_array($folderSQL);
					
					$privacy = $folderData['privacy'];
					$privacy = str_replace(";PROTECTED", "", $privacy);
					
					
					mysql_query("UPDATE `folders` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case 'element':
					
					$elementSQL = mysql_query("SELECT privacy FROM elements WHERE id='$typeId'");
					$elementData = mysql_fetch_array($elementSQL);
					
					$privacy = $elementData['privacy'];
					$privacy = str_replace(";PROTECTED", "", $privacy);
					
					
					mysql_query("UPDATE `elements` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case 'file':
					$fileSQL = mysql_query("SELECT privacy FROM files WHERE id='$typeId'");
					$fileData = mysql_fetch_array($fileSQL);
					$privacy = $fileData['privacy'];
					$privacy = str_replace(";PROTECTED", "", $privacy);
					
					
					mysql_query("UPDATE `files` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
				case 'link':
					$linkSQL = mysql_query("SELECT privacy FROM links WHERE id='$typeId'");
					$linkData = mysql_fetch_array($linkSQL);
					
					
					$privacy = $linkData['privacy'];
					$privacy = str_replace(";PROTECTED", "", $privacy);
					
					
					mysql_query("UPDATE `links` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
			}
		}else{
			jsAlert("You do not have the rights to edit protected files.");
		}
	}

function makeFilesystemItemUndeletable($type, $typeId){
		
		if(hasRight('undeletableFilesystemItems')){
			$type = save($type);
			$typeId = save($typeId);
			switch($type){
				case folder:
					
					$folderSQL = mysql_query("SELECT `privacy` FROM `folders` WHERE id='$typeId'");
					$folderData = mysql_fetch_array($folderSQL);
					
					$privacy = $folderData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
						$privacy .= ";UNDELETABLE";
					}
					
					
					mysql_query("UPDATE `folders` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case element:
					
					$elementSQL = mysql_query("SELECT privacy FROM elements WHERE id='$typeId'");
					$elementData = mysql_fetch_array($elementSQL);
					$privacy = $elementData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
						$privacy .= ";UNDELETABLE";
					}
					
					
					mysql_query("UPDATE `elements` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					
					
					break;
				case file:
					$fileSQL = mysql_query("SELECT privacy FROM files WHERE id='$typeId'");
					$fileData = mysql_fetch_array($fileSQL);
					$privacy = $fileData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
						$privacy .= ";UNDELETABLE";
					}
					
					
					mysql_query("UPDATE `files` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
				case link:
					$linkSQL = mysql_query("SELECT privacy FROM links WHERE id='$typeId'");
					$linkData = mysql_fetch_array($linkSQL);
					
					
					$privacy = $linkData['privacy'];
					if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
						$privacy .= ";UNDELETABLE";
					}
					
					
					mysql_query("UPDATE `links` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
					break;
			}
		}else{
			jsAlert("You do not have the right to make Items undeletable.");
		}
	}
function makeFilesystemItemDeletable($type, $typeId){
		if(hasRight("editUndeletableFilesystemItems")){
		$type = save($type);
		$typeId = save($typeId);
		
		switch($type){
			case 'folder':
				
				$folderSQL = mysql_query("SELECT `privacy` FROM `folders` WHERE id='$typeId'");
				$folderData = mysql_fetch_array($folderSQL);
				
				$privacy = $folderData[privacy];
				$privacy = str_replace(";UNDELETABLE", "", $privacy);
				
				
				mysql_query("UPDATE `folders` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
				
				
				break;
			case 'element':
				
				$elementSQL = mysql_query("SELECT privacy FROM elements WHERE id='$typeId'");
				$elementData = mysql_fetch_array($elementSQL);
				
				$privacy = $elementData['privacy'];
				$privacy = str_replace(";UNDELETABLE", "", $privacy);
				
				
				mysql_query("UPDATE `elements` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
				
				
				break;
			case 'file':
				$fileSQL = mysql_query("SELECT privacy FROM files WHERE id='$typeId'");
				$fileData = mysql_fetch_array($fileSQL);
				$privacy = $fileData['privacy'];
				$privacy = str_replace(";UNDELETABLE", "", $privacy);
				
				
				mysql_query("UPDATE `files` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
				break;
			case 'link':
				$linkSQL = mysql_query("SELECT privacy FROM links WHERE id='$typeId'");
				$linkData = mysql_fetch_array($linkSQL);
				
				
				$privacy = $linkData['privacy'];
				$privacy = str_replace(";UNDELETABLE", "", $privacy);
				
				
				mysql_query("UPDATE `links` SET `privacy`='$privacy' WHERE  `id`='$typeId'");
				break;
		}
		}else{
			jsAlert("You dont have the right to edit undeletable Items.");
		}
	}
	
//shows a picture of element or folder if available
function showThumb($type, $itemId){
        switch($type){
            case 'folder':
                $elementSQL = mysql_query("SELECT id FROM elements WHERE folder='$itemId' ORDER BY RAND() LIMIT 0,1");
                $elementData = mysql_fetch_array($elementSQL);
                
                $path = showThumb("element", $elementData['id']);
                
                
            break;
            case 'element':
                
                $elementSQL = mysql_query("SELECT title, privacy FROM elements WHERE id='$itemId'");
                $elementData = mysql_fetch_array($elementSQL);
                if(authorize("$elementData[privacy]", "show")){
                    
                    //select random picture and show thumb of it
                    $fileSQL = mysql_query("SELECT * FROM files WHERE type IN ('image/jpeg', 'image/png') AND title LIKE '%thumb%' AND folder='$itemId' ORDER BY RAND() LIMIT 0,1");
                    $fileData = mysql_fetch_array($fileSQL);
                    if($fileData){
                        $path = "upload".getFilePath($fileData['id']);
                        $path = "$path/$fileData[title]";
                    }
                }
            break;
        }
        return "$path";
    }
    
function showChatThumb($input){
        
        $itemType = $input[1];
        $itemId = $input[2];
        
        $return = showItemThumb($itemType, $itemId);
        
        return $return;
}

//shows a box with item title, item link and settings
function showItemThumb($itemType, $itemId){
        $itemId = save($itemId);
        
        switch($itemType){
            case 'folder':
            
                $folderSQL = mysql_query("SELECT * FROM folders WHERE id='$itemId'");
                $folderData = mysql_fetch_array($folderSQL);

                $title = $folderData['name'];
                $shortTitle = $folderData['name'];
				
				//group folders
                if($folderData['folder'] == 3){
                	$title = getGroupName($folderData['name']).'´s Files';
					$shortTitle = $title;
                }
                

                //define link
                $link = "openFolder('$itemId')";

                //define icon
                $img = "filesystem/folder.png";

                //define info 1
                $info[0] = "path";
                $info[1] = $folderData['path'];
        
            break;
            case 'element':
                $elementSQL = mysql_query("SELECT title, type FROM elements WHERE id='$itemId'");
                $elementData = mysql_fetch_array($elementSQL);

                $title = $elementData['title'];
                $shortTitle = $elementData['title'];

                //define link
                $link = "openElement('$itemId')";

                //define icon
                $img = "filesystem/element.png";

                //define info 1
                $info[0] = "type";
                $info[1] = $elementData['type'];
            break;
            case 'file':
            
                $fileSQL = mysql_query("SELECT title, type, folder, size, filename FROM files WHERE id='$itemId'");
                $fileData = mysql_fetch_array($fileSQL);


                $title = $fileData['title'];
                $shortTitle = $fileData['title'];
                //shorten filename
                if(strlen($title) > 15){
                    $shortTitle = substr("$title", 0, 8)."(...)".substr("$title", -4);
                }

                //define link
                $link = "openFile('$fileData[type]', '$itemId', '$shortTitle')";

                //define fileIcon
                
                //if image no info is shown. the complete first row is used to preview image
                if($fileData['type'] == "image/jpg" || $fileData['type'] == "image/jpeg" || $fileData['type'] == "image/png"){
                	$elementData = mysql_fetch_array(mysql_query("SELECT folder FROM elements WHERE id='$fileData[folder]'"));
                	$img = "../../".getFolderPath($elementData['folder'])."thumbs/".$fileData['filename'];
                	
                	//the column which normaly includes the icon needs to fill out the full width
                	$imgColumnStyle = "colspan=\"2\"";
                }else{
                $img = "fileIcons/";
                $img .= getFileIcon($fileData['type']);
                //define info 1
                $info[0] = "size";
                $info[1] = round($fileData['size']/(1024*1024), 2)." MB";
				}

            break;
            case 'link':
                $linkSQL = mysql_query("SELECT * FROM links WHERE id='$itemId'");
                $linkData = mysql_fetch_array($linkSQL);

                $title = $linkData['title'];
                $shortTitle = $linkData['title'];

                //define link
                $link = "openFile('$linkData[type]', '$linkData[typeId]', '$shortTitle')";

                //define linkIcon
                $img = "fileIcons/";
                $img .= getFileIcon($linkData[type]);

                //define info 1
                $info[0] = "type";
                $info[1] = $linkData['type'];
            break;
            case 'group':
            break;
            case 'playlist':
            break;
        }
        
        if(strlen($title) > 15){
            $shortTitle = substr("$title", 0, 11)."(...)";
        }
        
        
        $return = "<div class=\"itemThumb\">
            <table width=\"100%\" cellspacing=\"0\">
                <tr style=\"height: 30px;\" bgcolor=\"#F2F2F2\">
                    <td colspan=\"4\">&nbsp;<a href=\"#\" onclick=\"$link;return false;\" title=\"$title\"><strong>$shortTitle</strong></a></td>
                </tr>";
		
		//add spacer
		if(!empty($info[0]) || !empty($info[1])){
        $return .= "<tr style=\"height: 10px\">
                    <td></td>
                </tr>";
		}
         //add icon/thumbnail
         $return .= "<tr>
                    <td style=\"min-width: 34px;\" $imgColumnStyle>
                    	<img src=\"./gfx/icons/$img\"/>
                    </td>";
					
		//add information
		if(!empty($info[0]) || !empty($info[1])){
		$return .=  "<td>
                        <table class=\"eightPt\">
                            <tr>
                                <td style=\"text-align: right\">$info[0]:&nbsp;</td>
                                <td class=\"ellipsis\"><span class=\"ellipsis\">$info[1]</span></td>
                            </tr>
                        </table>
                    </td>";}
		$return .=  "</tr>
                <tr height=\"22px\">
                    <td bgcolor=\"#F2F2F2\">".showItemSettings($itemType, $itemId)."</td>
                    <td bgcolor=\"#F2F2F2\" align=\"right\">
                        <a href=\"#\" class=\"btn btn-mini disabled\" style=\"float: right; margin-right: 30px; color: #606060;\"><i class=\"icon-comment\"></i>&nbsp;(".countComment($itemType, $itemId).")</a>
                    </td>
                </tr>
            </table>
        </div>";
        return $return;
    }

function addChecksumToUffCookie($fileId, $checksum){
                //for each opened UFF the file id is added to the $_SESSION[openUffs]
                //and a $_SESSION with the checksum will be created, which shows the 
                //reload.php if a reload of the document is nessacary
                
                $userid = getUser();
                
                if(!empty($_SESSION['openUffs'])){
                    
                    //parse SESSION
                    $sessionArray = explode(";", $_SESSION['openUffs']);
                    
                    //check if there is a cookie set for the fileId
                    if (!in_array("$fileId", $sessionArray)) {
                        //set cookie
                        $_SESSION['openUffs'] = "$fileId;".$_SESSION['openUffs'];
                    }
                    
                    //check if checksum needs to be updated
                    if($_SESSION["UFFsum_$fileId"] != $checksum){
                        //update checksum for fileId
                        $_SESSION["UFFsum_$fileId"] = $checksum;
                    }
                }else{
                    $_SESSION['openUffs'] = "$fileId";
                    $_SESSION["UFFsum_$fileId"] = $checksum;
                }
                //add user to active users list
                
                
                $fileData = mysql_query("SELECT var1 FROM files WHERE id='$fileId'");
                $fileData = mysql_fetch_array($fileData);
                
                //var1 with UFFs is used to 
                $activeUserArray = explode(";", $fileData['var1']);
                //check if user is allready in list
                if (!in_array("$userid", $activeUserArray)) {
                    //add user to array
                    $activeUserArray[] = $userid;
                    
                    //parse array
                    $activeUserArray = implode(";", $activeUserArray);
                    
                    //update db
                    mysql_query("UPDATE files SET var1='$activeUserArray' WHERE id='$fileId'");
                }
    }

function removeUFFcookie($fileId){
                //removes checksum and caller from $_SESSION so that the 
                //reload.php dont handels and empty request
                
                	$userid = getUser();
                    
                    if(empty($fileId)){
                        unset($_SESSION['openUffs']);
                    }
                    //parse SESSION
                    $sessionArray = explode(";", $_SESSION['openUffs']);
                    
                    //check if there is a cookie set for the fileId
                    if (in_array("$fileId", $sessionArray)) {
                        //delete cookie
                        foreach (array_keys($sessionArray, $fileId) as $key) {
                            unset($sessionArray[$key]);
                        }
                        $_SESSION['openUffs'] = implode(";", $sessionArray);
                        
                        
                        
                        //add user to active users list


                        $fileData = mysql_query("SELECT * FROM files WHERE id='$fileId'");
                        $fileData = mysql_fetch_array($fileData);

                        //var1 with UFFs is used to 
                        $activeUserArray = explode(";", $fileData['var1']);
                        //get user out of array
                        foreach($activeUserArray AS &$user){
                            if($user != $userid){
                                $newArray[] = $user;
                            }
                        }
                        //parse array
                        $newArray = implode(";", $newArray);
                        //update db
                        mysql_query("UPDATE files SET var1='$newArray' WHERE id='$fileId'");
                        
                    }
    }

//loads content from UFF
function showUffFile($fileId, $subPath){
                
                if(empty($subPath)){
                $filePath = "../../".getFullFilePath($fileId);
                }else{
                $filePath = getFullFilePath($fileId);
                }

                $file = fopen($filePath, 'r');
                $return = fread($file, filesize($filePath));
                fclose($file);
                $checksum = md5_file($filePath);
                
                addChecksumToUffCookie($fileId, $checksum);
                return $return;
    }

//writes Data into an UFF
function writeUffFile($fileId, $input, $subPath){
                if(empty($subPath)){
                $filePath = "../../".getFullFilePath($fileId);
                }else{
                $filePath = getFullFilePath($fileId);
                }

                $file = fopen($filePath, 'w');

                fwrite($file, $input);
                fclose($file);

                $checksum = md5_file($filePath);
                addChecksumToUffCookie($fileId, $checksum);
                return true;
    }
	
	
		
class playlist{
	public $playlist;
	
	function __construct($fileId=0){
		if($playlistId != 0){
			$fileData = 'array';
			//get file source
			
			//read out json
			
			//parse json to playlist
			$this->playlist = 'array';
		}
	}
	
	function create($items, $element){
		
		//parse items to json
		
		//create file in $element
		
		
	}
	function addItem($item){
		
	}
	function moveItemUp($item){
		
	}
	function moveItemDown($item){
		
	}
	function removeItem($item){
		
	}
	
	function updatePlaylists($user){
		$oldPlaylistsSQL = mysql_query("SELECT * FROM `playlist` WHERE use");
	}
}
		
class contextMenu{
	public $type;
	public $itemId;
	public $title;
	public $info1;
	
	
	
	function __construct($type, $itemId, $title, $info1) {
      	$this->type = $type;
      	$this->itemId = $itemId;
		if(!empty($title)){
      		$this->title = $title;
		}
		if(!empty($info1)){
      		$this->info1 = $info1;
		}
	}
	function getOptions(){
		$itemId = $this->itemId;
		
				//init vars
	            $open[] = '';
	            $fav[] = '';
	            $privacy[] = '';
	            $playlist[] = '';
				$edit[] = '';
				$delete[] = '';
				$protect[] = '';
				$undeletable[] = '';
				
		switch($this->type){
			case 'feed':
	        	$feedCheck = mysql_query("SELECT author, privacy FROM feed WHERE id='$itemId'");
	            $feedData = mysql_fetch_array($feedCheck);
	            if(authorize('p', "edit", $feedData['author'])){
	            	$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=feed&itemId=$itemId')";
					

				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=feed&itemId=$itemId";
					$delete['target'] = 'submitter'; 
	            }
				
				$options[] = $privacy;
				$options[] = $delete;
 				break;
			case 'comment':
	        	$commentCheck = mysql_query("SELECT author, type, typeid, privacy FROM comments WHERE id='$itemId'");
	            $commentData = mysql_fetch_array($commentCheck);
	            
	            //allow profile owner to delete comments that other users made in his profile
	            if($commentData['type'] == "profile" && $commentData['typeid'] == getUser()){
	            	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=comment&itemId=$itemId";
					$delete['target'] = 'submitter'; 
				}
	            if(authorize('p', "edit", $commentData['author'])){
	                $privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=comment&itemId=$itemId')";
					

				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=comment&itemId=$itemId";
					$delete['target'] = 'submitter'; 
	            }
				
				$options[] = $delete;
				$options[] = $privacy;
				break;
			case 'internLink':
				$checkInternLinkData = mysql_fetch_array(mysql_query("SELECT * FROM internLinks WHERE id='$itemId'"));
            
                if($checkInternLinkData['type'] == "folder"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy, creator FROM folders WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "element"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, creator FROM elements WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "file"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type, owner FROM files WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['owner'];
                }else if($checkInternLinkData['type'] == "link"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type, author FROM links WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['author'];
                }
                
                if(authorize($shortCutItemData['privacy'], "edit", $user)){
				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=internLink&itemId=$itemId";
					$delete['target'] = 'submitter'; 
                }
                $options[] = $delete;
				break;
			case "folder":
				
				
		    	$checkFolderSql = mysql_query("SELECT `privacy`, `creator` FROM `folders` WHERE id='$itemId'");
		        $checkFolderData = mysql_fetch_array($checkFolderSql);
				  
				$open['title'] = 'open';
				$open['href'] = '#';
				$open['onclick'] = "openFolder('$itemId')";
				
				if(proofLogin()){
					
					$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=folder&item=$itemId";
					$fav['target'] = 'submitter';
					
				}
				
				if(authorize($checkFolderData['privacy'], "edit", $checkFolderData['creator'])){
				
					$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=folder&itemId=$itemId')";
					
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=folder&itemId=$itemId')";
					
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=folder&itemId=$itemId";
					$delete['target'] = 'submitter';
					
				}
				
				
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkFolderData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=folder&itemId=$itemId')";

					}else{
						
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=folder&itemId=$itemId')";
						
					}
				}
	
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkFolderData['privacy'])){
						
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=folder&itemId=$itemId');";
					
					}else{
						
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=folder&itemId=$itemId');";
					
					}
					
				}
				
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $fav;
				$options[] = $protect;
				$options[] = $undeletable;
				
				
		      break;
		   	case "element":
			  
		    	$checkElementSql = mysql_query("SELECT privacy, author FROM elements WHERE id='$itemId'");
		      	$checkElementData = mysql_fetch_array($checkElementSql);
			 
			 
			 	$open['title'] = 'Open';
			 	$open['href'] = '#';
			 	$open['onclick'] = "openElement('$itemId', '$title');";
			 
		      	if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=element&itemId=$itemId')";
		         
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=element&itemId=$itemId')";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=element&itemId=$itemId";
					$delete['target'] = 'submitter';
		      	}
			 
			 	if(proofLogin()){
			 		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=element&item=$itemId";
					$fav['target'] = "submitter";
			 	}
			 
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkElementData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=element&itemId=$itemId')";

					}else{
					
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=element&itemId=$itemId')";
					
					}
				}
	
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkElementData['privacy'])){
					
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=element&itemId=$itemId');";
				
					}else{
					
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=element&itemId=$itemId');";
					}
				
				}
			
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $fav;
				$options[] = $protect;
				$options[] = $undeletable;
			
				break;
		   	case "file":
		    	$checkFileSql = mysql_query("SELECT privacy, owner FROM files WHERE id='$itemId'");
		       	$checkFileData = mysql_fetch_array($checkFileSql);
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('$info1', '$itemId', '$title');";
			  
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=file&item=$itemId";
					$fav['target'] = 'submitter';
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "javascript: popper('doit.php?action=addFileToPlaylist&file=$itemId');";
			  	}
			  
		       	if(authorize($checkFileData['privacy'], "edit", $checkFileData['owner'])){
		       		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=file&itemId=$itemId')";
		         
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=file&itemId=$itemId";
					$delete['target'] = 'submitter';
		       	}
			 
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkFileData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=file&itemId=$itemId')";
	
					}else{
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=file&itemId=$itemId')";
						
					}
				}
		
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkFileData['privacy'])){
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=file&itemId=$itemId');";
					
					}else{
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=file&itemId=$itemId');";
					
					}
					
				}
			  	
			
			  	$report['title'] = 'Report';
			    $report['href'] = '#';
			  	$report['onclick'] = "javascript: popper('doit.php?action=reportFile&fileId=$itemId')";
			  
			  	$download['title'] = 'download';
			  	$download['href'] = "./out/download/?fileId=$itemId";
			  	$download['target'] = 'submitter';
			
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $fav;
				$options[] = $playlist;
				$options[] = $download;
				$options[] = $delete;
				$options[] = $protect;
				$options[] = $undeletable;
				$options[] = $report;
			
		   		break;
		   	case "image":
		    	$checkFileSql = mysql_query("SELECT privacy, owner FROM files WHERE id='$itemId'");
		       	$checkFileData = mysql_fetch_array($checkFileSql);
		       
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('image', '$itemId', '$title');";
			  
			  
			  	$download['title'] = 'Download';
			  	$download['href'] = "./out/download/?fileId=$itemId";
			  	$download['target'] = 'submitter';
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=file&item=$itemId";
					$fav['target'] = 'submitter';
				
					$background['title'] = 'Set as Background';
					$background['href'] = "doit.php?action=changeBackgroundImage&type=file&id=$itemId";
					$background['target'] = 'submitter';
			  	}
				
			  	if(authorize($checkFileData['privacy'], "edit", $checkFileData['owner'])){
			  	 	$delete['title'] = 'Delete';
				 	$delete['href'] = "doit.php?action=deleteItem&type=file&itemId=$itemId";
				 	$delete['target'] = 'submitter';
			  	}
				$options[] = $open;
				$options[] = $download;
				$options[] = $fav;
				$options[] = $background;
				$options[] = $delete;
		   		break;
		   	case "link":
		    	$checkLinkSql = mysql_query("SELECT privacy, author FROM links WHERE id='$itemId'");
	           	$checkLinkData = mysql_fetch_array($checkLinkSql);
	           
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('$info1', '$itemId', '$title');";
			  
			  	if(proofLogin()){
			  	
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=link&item=$itemId";
					$fav['target'] = 'submitter';
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "popper('doit.php?action=addFileToPlaylist&link=$itemId');";
			  	}
			  
		      	if(authorize($checkLinkData['privacy'], "edit", $checkLinkData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=link&itemId=$itemId')";
		         
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=link&itemId=$itemId')";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=link&itemId=$itemId";
					$delete['target'] = 'submitter';
		      	}
			 
			  
			  
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkLinkData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=link&itemId=$itemId')";
	
					}else{
						
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=link&itemId=$itemId')";
						
					}
				}
		
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkLinkData['privacy'])){
						
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=link&itemId=$itemId');";
					
					}else{
						
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=link&itemId=$itemId');";
					
					}
					
				}
			  
			  	
				$options[] = $open;
				$options[] = $fav;
				$options[] = $playlist;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $protect;
				$options[] = $undeletable;
			  
		   		break;
			
		}
		
			  
			  return $options;
	}
	public function showRightClick(){
		
      	 $type = $this->type;
      	 $itemId = $this->itemId;
		
		$options = $this->getOptions();
		
		if(count($options) > 0){
			$list = '';
			foreach($options AS $option){
				if(!empty($option['title'])){
					
						$onclick = '';
				if(!empty($option['href'])){
					$href = 'href="'.$option['href'].'"';
					
				}
				if(!empty($option['onclick'])){
					if($href == 'href="#"'){
						$onclick = 'onclick="'.$option['onclick'].'"';
					}
				}
				if(!empty($option['target'])){
					if($href != 'href="#"')
						$target = 'target="'.$option['target'].'"';
					
				}
				$list .= "<li><a $href $onclick $target>".$option['title'].'</a></li>';  
					
				}
			}
		}
	  
		  $output = "<span id=\"rightClick$type$itemId\" class=\"rightclick\" style=\"display: none;\">";
		  	$output .= "<ul>";
	        	$output .= $list;
		  	$output .= "</ul>";
		  $output .= "</span>";
		  
		  return $output;
	}
	public function showItemSettings(){
		$options = $this->getOptions();
		if(count($options) > 0){
			$list = '';
			foreach($options AS $option){
				if(!empty($option['title'])){
					
						$onclick = '';
				if(!empty($option['href'])){
					$href = 'href="'.$option['href'].'"';
					
				}
				if(!empty($option['onclick'])){
					if($href == 'href="#"'){
						$onclick = 'onclick="'.$option['onclick'].'"';
					}
				}
				if(!empty($option['target'])){
					if($href != 'href="#"')
						$target = 'target="'.$option['target'].'"';
					
				}
				$list .= "<li><a $href $onclick $target>".$option['title'].'a</a></li>';  
					
				}
			}
		}
		
				if(!empty($list)){
					
			        $return = "
			        <a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"btn btn-mini\"><i class=\"icon-cog\"></i></a>
			        <div class=\"itemSettingsWindow\">
			            <ul>
			                $list
			            </ul>
			        </div>";
				}
				if(!empty($return)){
					return $return;
				}
	}
}

class dashBoard{
	public $userid;
	public $userdata;
	
	function __construct() {

		$user = getUser();
      	$this->userid = $user;
		$this->userdata = mysql_fetch_array(mysql_query("SELECT username FROM user WHERE userid='$user'"));

	}
	
	function showDashBox($title, $content, $footer=NULL, $id=NULL, $grid=true){
		if($grid){
			$output .= "<div class=\"dashBox\" id=\"$id"."Box\">";
		}
		
			$output .= "<a class=\"dashClose\"></a>";
			$output .= "<header>$title</header>";
		
			$output .= "<div class=\"content\">$content</div>";
			
			if(!empty($footer)){
			$output .= "<footer>$footer</footer>";
			}
			
		if($grid){
			$output .= "</div>";
		}
		return $output;
	}
	
	function showWelcomeBox($grid=true){
			
		$userData = $this->userdata;
		
		$title = "Welcome";
		$content = str_replace('\\', '' , showUserPicture($this->userid,13,false,true))." Hey <a href=\"#\" onclick=\"showProfile('$this->userid')\">$userData[username]</a>,<br>good to see you!";
		$content .= "<div>";
		$content .= "<div class=\"listContainer\">";
		$content .= "<ul class=\"list messageList\" id=\"dockMenuSystemAlerts\"></ul>";
		$content .= "<header>System</header>";
		$content .= "</div>";
		$content .= "<div class=\"listContainer\">";
		$content .= "<ul class=\"list messageList\" id=\"dockMenuBuddyAlerts\"></ul>";
		$content .= "<header>Buddies</header>";
		$content .= "</div>";
		$content .= "</div>";

		$output = $this->showDashBox($title, $content,"", "buddy", $grid);
		return $output;
	}
	
	function showAppBox($grid=true){
		
		$title = "Your Apps";
		
		$content = "<ul class=\"appList\">";
	    	$content .= "<li onclick=\"toggleApplication('feed')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/feed.png\" border=\"0\" height=\"16\">Feed</li>";
	    	$content .= "<li onclick=\"calendar.show();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/feed.png\" border=\"0\" height=\"16\">Calendar</li>";
			$content .= "<li onclick=\"toggleApplication('filesystem')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/filesystem.png\" border=\"0\" height=\"16\">Filesystem</li>";
	 		$content .= "<li onclick=\"javascript: toggleApplication('reader')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/viewer.png\" border=\"0\" height=\"16\">Reader</li>";
	   		$content .= "<li onclick=\"javascript: toggleApplication('buddylist')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Buddylist</li>";
	    	$content .= "<li onclick=\"javascript: toggleApplication('chat')\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/buddylist.png\" border=\"0\" height=\"16\">Chat</li>";
	    	$content .= "<li onclick=\"javascript: standardModules.showSettings();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/settings.png\" border=\"0\" height=\"16\">Settings</li>";
	    $content .= "</ul>";
		
		$output = $this->showDashBox($title, $content," ", "app", $grid);
		
		return $output;
		
	}
	function showGroupBox($grid=true){
		
		//groups
		$groups = getGroups();
		
		$title = "Your Groups";
		
		if(count($groups) == 0){
			
				$output .='<p style="font-size:15pt;padding: 5px; padding-top: 12px;">';
				$output .="You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .="</p>";
		}else{
			
			$output .= "<ul class=\"\">";
				foreach($groups AS $group){
					$output .="<li>";
						$output .="<span class=\"marginRight\">";
							$output .="<img src=\"./gfx/icons/group.png\" height=\"14\">";
						$output .="</span>";
						$output .="<span>";
							$output .="<a href=\"#\" onclick=\"showGroup('$group');\">";
							$output .= getGroupName($group);
							$output .="</a>";
						$output .="</span>";
					$output .="</li>";
					$i++;
				}
				
			$output .= "</ul>";
		}
		
		$footer = "<a href=\"#addGroup\" onclick=\"popper('doit.php?action=addGroup')\" title=\"Create a new Group\"><i class=\"icon icon-plus\"></i></a>";
		
		$output = $this->showDashBox($title, $output, $footer, "group", $grid);
		
		return $output;
		
	}
	function showPlaylistBox($grid=true){
		
			//playlists
			$playlists = getPlaylists();
			
			$title = "Your Playlists";
			if(count($playlists) == 0){
			
				$output .= '<p style="padding: 5px; padding-top: 12px;">';
				$output .= 'You don\'t have any playlists so far.';
				$output .= '</p>';
			
			}else{
				
				$output .= "<ul>";
					foreach($playlists AS $playlist){
						$output .= "<li>";
							$output .= "<span class=\"marginRight\">";
								$output .= "<img src=\"./gfx/icons/playlist.png\" height=\"14\">";
							$output .= "</span>";
							$output .= "<span>";
								$output .= "<a href=\"#\" onclick=\"showPlaylist('$playlist');\">";
								$output .=  getPlaylistTitle($playlist);
								$output .= "</a>";
							$output .= "</span>";
						$output .= "</li>";
					}
				$output .= "</ul>";
				
			}
			
		$footer = "<a href=\"#addPlaylist\" onclick=\"popper('doit.php?action=addPlaylist')\" title=\"Create a new Playlist\"><i class=\"icon icon-plus\"></i></a>";
			
		
		$output = $this->showDashBox($title, $output, $footer, "playlist", $grid);
		$footer = "";
		
		return $output;
		
	}
	function showMessageBox($grid=true){
			//unseenMessages
			$lastMessages = getLastMessages();
		
			$title = "Your Messages";
			
			$output .= "<ul id=\"messageList\">";
			$i = 0;
			foreach($lastMessages AS $message){
				
				$class = "";
				if($message['seen'] == "0"){
					$class = "unseen";
				}
				
				$output .= "<li class=\"$class\" style=\"clear:both;\">";
					$output .=  "<span>";
					$output .=  stripslashes(showUserPicture($message['sender'],13,'', true));
					$output .=  $message['senderUsername'].':';
					$output .=  "</span>";
					$output .=  "<span>";
					$output .=  "<a href=\"#\" onclick=\"openChatDialoge('$message[senderUsername]');\">";
					$output .=  substr($message['text'], 0, 15);
					$output .=  "</a>";
					$output .=  "</span>";
				$output .= "</li>";
				$i++;
			}
			if($i == 0){
				$output .= "<li>";
				$output .= "You don't have any messages so far. Search for friends, add them to your buddylist and open a chat dialoge to write a message.";
				$output .= "</li>";
			}
			
			$output .= "</ul>";
		
			$output = $this->showDashBox($title, $output,"", "message", $grid);
			
			return $output;
		
	}

	function showFavBox($grid=true){
		
			$title = "Your Favorites";
			
			$output .= "<div>";
				$output .= "<table width=\"100%\">";
					$output .= showFav();
				$output .= "</table>";
			$output .= "</div>";
		
			$output = $this->showDashBox($title, $output," ", "fav", $grid);
			
			return $output;
	}

	function showTaskBox($grid=true){
		$title = "Future Tasks";
		
		
		$tasks = new tasks();
		
		$taskArray = $tasks->get(getUser(), time()-86400, time()+(7*86400));
		$output = '<ul>';
		foreach($taskArray AS $task){
			$editable = authorize($task['privacy'], 'edit', $task['user']);
			$output .= '<li onclick="tasks.show('.$task['id'].','.$editable.');">'.date('d.m.', $task['timestamp']).' - '.$task['title'].'</li>';
		}
		$output .= '</ul>';
		
		
		$footer = "<a href=\"#addTask\" onclick=\"tasks.addForm();\" title=\"Create a new Task\"><i class=\"icon icon-plus\"></i></a>";
			
		
		
		return $this->showDashBox($title, $output,$footer, "task", $grid);
	}
	
	
}

class signatures{
	
	function create($type, $itemId, $privateKey, $publicKey){
		mysql_query("DELETE FROM `signatures` WHERE type='".save($type)."' AND itemId='".save($itemId)."'");
		mysql_query("INSERT INTO `signatures` (`type`, `itemId`, `privateKey`, `publicKey`, `timestamp`) VALUES ('$type', '$itemId', '$privateKey', '$publicKey', '".time()."')");
	}
	
	function get($type, $itemId){
		$data = mysql_fetch_array(mysql_query("SELECT * FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'"));
		return $data;
	}
	
	function updatePrivateKey($type, $itemId, $privateKey){
		if(!empty($privateKey)){
			mysql_query("UPDATE `signatures` SET privateKey='".save($privateKey)."' WHERE `type`='".save($type)."' AND `itemId`='".save($itemId)."'");
		}
	}
	
	function delete($type, $itemId){
		mysql_query("DELETE FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'");
	}
}

class sessionHashes{
	private $validity; //time in seconds untill hash expires
	
	
	function create($userid){
		
		//create unique identifyer and hash it with rand salt (the user agent needs to be encrypted!)
		$uniqueSystemIdentifyer = hash('sha512', $_SERVER['HTTP_USER_AGENT']);
		//add salt
		$randomSalt = hash('sha512', $uniqueSystemIdentifyer+substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
		
		mysql_query("DELETE FROM `sessionHashes` WHERE timestamp>'".(time()-$this->validity)."' AND userid='".save($userid)."' AND uniqueSystemIdentifyer='".save($uniqueSystemIdentifyer)."'");
		//save id, identifier and salt
		mysql_query("INSERT INTO `signatures` (``, `itemId`, `privateKey`, `publicKey`, `timestamp`) VALUES ('$type', '$itemId', '$privateKey', '$publicKey', '".time()."')");
		return mysql_insert_id();
	}
	
	function get($id){
		$data = mysql_fetch_array(mysql_query("SELECT * FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'"));
		return $data;
	}
	function delete($type, $itemId){
		mysql_query("DELETE FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'");
	}
}

class sec{
	
	function passwordCypher($val){
		return hash('sha515', md5($val));
	}
	
	function validateUserSignature($userid, $signature){
		$userData = getUserData($password);
		if($signature == $userData['password'])
			return true;
		else
			return false;
	}
}



class tasks{
	public function create($user, $timestamp, $status, $title, $description, $privacy){
		$values['user'] = $user;
		$values['timestamp'] = $timestamp;
		$values['status'] = $status;
		$values['title'] = $title;
		$values['description'] = $description;
		$values['privacy'] = $privacy;
		
		$db = new db();
		$db->insert('tasks', $values);
	}
	public function update($id, $user, $timestamp, $status, $title, $description, $privacy){
		
		$db = new db();
		$db->update('tasks', array('user'=>$user, 'timestamp'=>$timestamp, 'status'=>$status, 'title'=>$title, 'description'=>$description,  'privacy'=>$privacy), array('id',$id));
	
	}
	public function changeStatus($id, $status){
		$eventData=$this->getData($id);
		if(authorize($eventData['privacy'], 'edit', $eventData['user'])){
			
			$db = new db();
			$db->update('tasks', array('status'=>$status), array('id',$id));
		
		}
	}
	public function getData($id){
		
		
		$query = "WHERE `id`='".save($id)."'";
		$data = mysql_fetch_array(mysql_query("SELECT * FROM `tasks` $query"));
		return $data;
	}
	public function get($user=NULL, $startStamp, $stopStamp, $privacy=NULL){
		
		if($privacy != NULL){
			$privacy = explode(';', $privacy);
			$privacy = implode('|', $privacy);
			$privacyQuery = "AND privacy REGEXP '$privacy'";
		}
		
		$sql = mysql_query("SELECT * FROM `tasks` WHERE `timestamp`>'".save($startStamp)."' AND `timestamp`<'".save($stopStamp)."' AND `user`='".save($user)."' $privacyQuery");
		while($data = mysql_fetch_array($sql)){
			$data['editable'] = authorize($data['privacy'], 'edit', $data['user']);
			$arr[] = $data;
		}
		return $arr;
	}
}

class events{
	
	public function create($user, $startStamp, $stopStamp, $title, $place, $privacy, $users, $originalEventId=0){
		$invitedUsers = $users;
		mysql_query("INSERT INTO `events` (`user`, `startStamp`, `stopStamp`, `title`, `place`, `privacy`, `invitedUsers`, `originalEventId`) VALUES ('".save($user)."', '".save($startStamp)."', '".save($stopStamp)."', '".save($title)."', '".save($place)."', '$privacy', '".save($invitedUsers)."', '".save($originalEventId)."');");
		
		//add personalEvents for each user in array $users
		if(!empty($users)){
			$users = explode(',', $users);
			$personalEvents = new personalEvents();
			foreach($users AS $user){
				if($user != 0)
	         		$personalEvents->create($user,$_SESSION['userid'],'event','invitation',mysql_insert_id());
			}
		}
	}
	
	public function joinEvent($originalEventId, $user, $addToVisitors=true){
				
		$originalEventData = $this->getData($originalEventId);
		
		//create new event
		$this->create($user, $originalEventData['startStamp'], $originalEventData['stopStamp'], $originalEventData['title'], $originalEventData['place'], 'h', '', $originalEventId);

		//update original event
		if($addToVisitors){
				
				//add user to inviteduser string
				$invitedUsers = $user.','.$originalEventData['invitedUsers'];
			
				//update db
				$db = new db();
				$db->update('events', array('invitedUsers'=>$invitedUsers), array('id', $originalEventId));
	
		}
	}
	
	public function update($eventId, $startStamp, $stopStamp, $title, $place, $privacy){
		
		$db = new db();
		$db->update('events', array('title'=>$title, 'place'=>$place, 'privacy'=>$privacy, 'startStamp'=>$startStamp,  'stopStamp'=>$stopStamp), array('id', $eventId));
	}
	
	public function get($user=NULL, $startStamp, $stopStamp, $privacy=NULL){
		
		if($privacy != NULL){
			$privacy = explode(';', $privacy);
			$privacy = implode('|', $privacy);
			$privacyQuery = "AND privacy REGEXP '$privacy'";
		}
		
		$sql = mysql_query("SELECT * FROM `events` WHERE `startStamp`>'".save($startStamp)."' AND `stopStamp`<'".save($stopStamp)."' AND `user`='".save($user)."' $privacyQuery");
		while($data = mysql_fetch_array($sql)){
			$data['editable'] = authorize($data['privacy'], 'edit', $data['user']);
			$arr[] = $data;
		}
		return $arr;
	}
	
	public function getData($eventId){
		$sql = mysql_query("SELECT * FROM `events` WHERE id='".save($eventId)."'");
		$data = mysql_fetch_array($sql);
		
		return $data;
	}

}
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