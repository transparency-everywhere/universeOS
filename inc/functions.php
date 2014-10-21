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

include('classes/class_fileSystem.php');

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
	
include('classes/class_UFF.php');

include('classes/class_contextMenu.php');
		

include('classes/class_dashboard.php');

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