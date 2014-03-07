<?php
  $userid = getUser();
  $time = time();

  // Thumbnails: [url]www.codeschnipsel.net[/url]
  function mkthumb($img_src,     // Dateiname
                   $img_width,       // max. Größe in x-Richtung
                   $img_height,       // max. Größe in y-Richtung
                   $folder_scr,  // Ordner der normalen Bilder
                   $des_src)    // Ordner der Thumbs
  {
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
  
 function showYoutubeVideo($id, $subPath=NULL){
    ?>
	<script type="text/javascript" src="inc/swfObj/swfobject.js"></script>    
	<div id="ytapiplayer">
		You need Flash player 8+ and JavaScript enabled to view this video.
	</div>
	<script type="text/javascript">

    var params = { allowScriptAccess: "always" };
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/v/ASPDeS3_54U?enablejsapi=1&playerapiid=ytplayer&version=3",
                       "ytapiplayer", "425", "356", "8", null, null, params, atts);

	</script>

	<?php
 }
 
 /**
  * Opens an URL with curl and outputs xml
  *
  * @param string $url      Contains URL
  *
  * @return array Contains the returned xm.
  */
 function curler($url){
 	
        //umgehen des HTTP Verbots
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "universeOS");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        //Result in DomDocument Laden
        $dom = new DOMDocument;
        $dom->loadXML($result);
        //Array der XML Datei auslesen.
        $xml = simplexml_import_dom($dom);
		return $xml;
 }
 
 function getRssfeed($rssfeed, $cssclass="", $encode="auto", $anzahl=10, $mode=0) {
     $data = @file($rssfeed);
     $data = implode ("", $data);
         preg_match_all("/<item.*>(.+)<\/item>/Uism", $data, $items);
     
        if($encode == "auto")
                                {
                                    preg_match("/<?xml.*encoding=\"(.+)\".*?>/Uism", $data, $encodingarray);
                                    $encoding = $encodingarray[1];
                                }
        else
            {
            $encoding = $encode;
            
            }
            
        
       $output .= "<div class=\"rssfeed_".$cssclass."\">\n";
		// Titel und Link zum Channel 
		if($mode == 1 || $mode == 3)
		{
			$data = preg_replace("/<item>(.+)<\/item>/Uism", '', $data);
			preg_match("/<title>(.+)<\/title>/Uism", $data, $channeltitle);
			preg_match("/<link>(.+)<\/link>/Uism", $data, $channellink);
			preg_match("/<url>(.+)<\/url>/Uism", $data, $channelimage);
		
			$channeltitle = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channeltitle);
			$channellink = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channellink);
			$channellink = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channelimage);
		
			$output .= "<center class=\"grayBar\"><img src=\"./gfx/icons/rss.png\" style=\"float: left;\"><a href=\"".$channellink[1]."\" target=\"blank\" title=\"";
		        $output .= "<image src=\"".$channelimage[1]->src."\" title=\"";
			if($encode != "no")
			{$output .= htmlentities($channeltitle[1],ENT_QUOTES,$encoding);}
			else
			{$output .= $channeltitle[1];}
			$output .= "\">";
			if($encode != "no")
			{$output .= htmlentities($channeltitle[1],ENT_QUOTES,$encoding);}
			else
			{$output .= $channeltitle[1];}
			$output .= "</a></center>\n";
		}     
		     
		// Titel, Link und Beschreibung der Items
		foreach ($items[1] as $item) {
			preg_match("/<title>(.+)<\/title>/Uism", $item, $title);
			preg_match("/<link>(.+)<\/link>/Uism", $item, $link);
			preg_match("/<description>(.*)<\/description>/Uism", $item, $description);
		
			$title = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $title);
			$description = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $description);
			$link = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $link);
		
			$output .= "<div class=\"rssTitle\">\n";
			$output .= "<a href=\"#\" onclick=\"openURL('".$link[1]."', '$channeltitle'); return false\" target=\"blank\" title=\"";
			if($encode != "no")
			{$output .= htmlentities($title[1],ENT_QUOTES,$encoding);}
			else
			{$output .= $title[1];}
			$output .= "\">";
			if($encode != "no")
			{$output .= htmlentities($title[1],ENT_QUOTES,$encoding)."</a>\n";}
			else
			{$output .= $title[1]."</a>\n";}
			$output .= "</div>\n";
			if($mode == 2 || $mode == 3 && ($description[1]!="" && $description[1]!=" "))
			{
				$output .= "<div class=\"rssDescription\">\n";
				if($encode != "no")
				{$output .= htmlentities($description[1],ENT_QUOTES,$encoding)."\n";}
				else
				{$output .= $description[1];}
				$output .= "</div><hr>\n";
			}
			if ($anzahl-- <= 1) break;
		}
		$output .= "</div>\n\n";
		return $output;

 }
 function showRssFeed($url){
        $feed_url = "$url";

   // INITIATE CURL. 
   $curl = curl_init();

   // CURL SETTINGS. 
   curl_setopt($curl, CURLOPT_URL,"$feed_url"); 
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
   curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 

   // GRAB THE XML FILE. 
   $xmlTwitter = curl_exec($curl); 
   curl_close($curl);

   // SET UP XML OBJECT.
   // Use either one of these, depending on revision of PHP.
   // Comment-out the line you are not using.
   //$xml = new SimpleXMLElement($xmlTwitter);
   $xml = simplexml_load_string($xmlTwitter); 

   // How many items to display 
   $count = 10; 

   // How many characters from each item 
   // 0 (zero) will show them all. 
   $char = 0; 
   
   
   //show channel image
   echo '<img src="'.($xml->channel->image->url).'" class="newsTitleImage">';
   foreach ($xml->channel->item as $item) { 
   if($char == 0){ 
   $newstring = $item->description; 
   } 
   else{ 
   $newstring = substr($item->description, 0, $char); 
   } 
   if($count > 0){
   //in case they have non-closed italics or bold, etc ... 
   echo"</i></b></u></a>\n"; 
   echo" 
   <div style='font-family:arial; font-size: 12pt;' class='newsArticleBox'>
   <h3 style='line-height: 30px;'>{$item->title}</h3>
   $newstring <br><br><a href='#' onclick=\"openURL('{$item->guid}', '{".mysql_real_escape_string($item->title)."}'); return false\" target='_blank' class='btn btn-info'>read all</a>
   <br /><br /> 
   </div> 
   ";  
   } 
   $count--; 
   } 
 }    
 
 
    //shows the settins button for folders, elements, files, playlists and posts.
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

  function userLogin($username, $password){
  	
	     $username = mysql_real_escape_string($username);
	     $sql = mysql_query("SELECT userid, password, hash, cypher FROM user WHERE username='$username'");
	     $data = mysql_fetch_array($sql);
		 $userid = $data['userid'];
		 
	     $timestamp = time();
	     $timestamp = ($timestamp / 2);
	     $hash = md5($timestamp);
		 
		 //old version
		 if($data['cypher'] == 'md5'){
	     	$password = md5($password);
		 }
		 
	     if(!empty($userid) && $password == $data['password']) {
			 //set cookies
			 $_SESSION['guest'] = false;
	       	 $_SESSION['userid'] = $data['userid'];
	         $_SESSION['userhash'] = $hash;
			 
			 //update db
	         mysql_query("UPDATE user SET hash='$hash' WHERE userid='".$_SESSION['userid']."'");
	         
	         createFeed($_SESSION['userid'], "is logged in", "60", "feed", "p");
	         updateActivity($_SESSION['userid']);
			 
			 
			 return 1;
	     }else{
	     	return 0;
	     }
  }
  
  function createSalt($type, $itemId, $receiverType, $receiverId, $salt){
  	//stores encrypted salt in db
  	
  	//delete all old salts
	mysql_fetch_array(mysql_query("DELETE FROM `salts` WHERE `type`='$type' AND itemId='$itemId'"));
	
	
  		if(mysql_query("INSERT INTO `salts` (`type`, `itemId`, `receiverType`, `receiverId`, `salt`) VALUES ('$type', '$itemId', '$receiverType', '$receiverId', '$salt')"))
			return true;
		else 
			return false;

  }
  
  function updateSalt($type, $itemId, $salt){
  	mysql_query("UPDATE  `salts` SET salt='".save($salt)."' WHERE `type`='".save($type)."' AND itemId='".save($itemId)."'");
  }
  
  function getSalt($type, $itemId){
  	$type = save($type);
	$itemId = save($itemId);
	
  	$data = mysql_fetch_array(mysql_query("SELECT * FROM `salts` WHERE `type`='$type' AND itemId='$itemId' LIMIT 1"));
  	return $data['salt'];
  }
  
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
  
  function updateUserPassword($oldPassword, $newPassword, $newAuthSalt=NULL, $newKeySalt=NULL, $privateKey=NULL, $userid=NULL){
  	if($userid == NULL){
  		$userid = getUser();
  	}
	
	
	$userData = getUserData($userid);
	if($userData['password'] == $oldPassword){
		
		if(!empty($newAuthSalt)){
			//store salt
			updateSalt('auth', $userid, $newAuthSalt);
		}
		if(!empty($newKeySalt)){
			//store salt
			updateSalt('privateKey', $userid, $newKeySalt);
		}
		  
		//create signature
		$sig = new signatures();
		$sig->updatePrivateKey('user', $userid, $privateKey); //store encrypted private key
		
		mysql_query("UPDATE `user` SET  `password`='".save($newPassword)."' WHERE userid='$userid'");
	
  		return $newPassword;
	}else{
		return 'Old Password was wrong';
	}
  }
  
  function proofLogin(){
      if(isset($_SESSION['userid'])){
          return true;
      }else{
          return false;
      }
  }
  
  function proofLoginMobile($user, $hash){
  	
	$userData = getUserData($user);
	
	if($userData['password'] == $hash){
		return true;
	}else{
		if(proofLogin()){
			return true;
		}else{
			return false;
		}
	}
  }
  
  function getUser(){
  	
  	if(isset($_SESSION['userid'])){
  		return $_SESSION['userid'];
  	}else{
  		return false;
  	}
  }
  
  function hasRight($type){
	  //checks if user has right to ...
	  //gets information from $global_userGroupData
	  //whis is defined in config.php
	  
	  $userData = mysql_fetch_array(mysql_query("SELECT `usergroup` FROM `user` WHERE `userid`='$_SESSION[userid]'"));
	  $userGroupData = mysql_fetch_array(mysql_query("SELECT * FROM `userGroups` WHERE `id`='$userData[usergroup]'"));
	  
	  if($userGroupData["$type"] == "1"){
	  	return true;
	  }else{
	  	return false;
	  }
  	
  }
  
  // function hasLies($type){
	  // return $global_userGroupData["protectFileSystemItems"];
//   	
  // }
  
  function checkMobileAuthentification($username, $hash){
      
        $username = $username;
        $hash = $hash;
        
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$username'");
        $loginData = mysql_fetch_array($loginSQL);
        $dbPassword = $loginData['password'];
        $dbPassword = hash('sha1', $dbPassword);
        if($hash == $dbPassword){
            return true;
        }
  }
  
  function usernameToUserid($username){
        $loginSQL = mysql_query("SELECT userid, username FROM user WHERE username='".save($username)."'");
        $loginData = mysql_fetch_array($loginSQL);
        return $loginData['userid'];
  }
  
  function useridToUsername($userid){
        $loginSQL = mysql_query("SELECT userid, username FROM user WHERE userid='".save($userid)."'");
        $loginData = mysql_fetch_array($loginSQL);
        return $loginData['username'];
  }
  function useridToRealname($userid){
        $loginSQL = mysql_query("SELECT realname FROM user WHERE userid='".save($userid)."'");
        $loginData = mysql_fetch_array($loginSQL);
        return $loginData['realname'];
  }
  
  function getUserData($userid=NULL){
  	if(empty($userid)){
  		$userid = getUser();
  	}
	
	$userData = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE userid='$userid'"));
	return $userData;
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
//            $return .= "<script>";
//            $return .= "$('.userPicture_$userid').css('border-color', '$color');";
//            $return .= "</script>";
            echo $return;
        }
      }function updateActivity($userid){
      $time = time();
      mysql_query("UPDATE user SET lastactivity='$time' WHERE userid='$userid'");
  }
  //shows online status
     
  function userSignature($userid, $timestamp, $subpath = NULL, $reverse=NULL){
    $feedUserSql = mysql_query("SELECT userid, username, userPicture FROM user WHERE userid='$userid'");
    $feedUserData = mysql_fetch_array($feedUserSql);
    if(isset($subpath)){
        $path = "./../.";
        $subPath = 1;
    }else{
        $subPath = NULL;
    }
      ?>
    <div class="signature" style="background: #EDEDED; border-bottom: 1px solid #c9c9c9;">
    <table width="100%">
        <tr width="100%">
            <?php
            if(empty($reverse)){ ?>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 10pt;line-height: 17px;">&nbsp;<a href="#" onclick="showProfile(<?=$feedUserData['userid'];?>);"><?=$feedUserData['username'];?></a></td>
                    </tr>             
                    <tr>
                        <td style="font-size: 15pt;line-height: 23px;">&nbsp;<i><?=universeTime($timestamp);?></i></td>
                    </tr>
                </table>
            </td>
            <td align="right"><span class="pictureInSignature"><?=showUserPicture($feedUserData['userid'], "30", $subpath);?></span></td>
            <?}else{?>
            <td><?=showUserPicture($feedUserData['userid'], "30", $subpath);?></td>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 10pt;">&nbsp;<?=$feedUserData['username'];?></td>
                    </tr>             
                    <tr>
                        <td style="font-size: 08pt;">&nbsp;<i><?=universeTime($timestamp);?></i></td>
                    </tr>
                </table>
            </td>
            <?}?>
        </tr>
    </table>
    </div>
      <?php
  }


  function getUserFavs($userid=NULL){
  	if(empty($userid)){
  		$userid=$_SESSION['userid'];
  	}
  	$favSQL = mysql_query("SELECT * FROM fav WHERE user='$userid'");
		while($favData = mysql_fetch_array($favSQL)){
			$return[] = $favData;
		}
		
		return $return;
  }

	function getUserFavOutput($user){
		if(empty($user)){
			$user = $_SESSION['userid'];
		}
		
		
	}
	
	
	function markMessageAsRead($buddy, $user){
			
		$user = save($user);
		$buddy = save($buddy);
		
	        mysql_query("UPDATE `messages` SET `read`='1' WHERE `sender` ='$buddy' AND `receiver` ='$user';");
	}
	
		
	function getLastMessage($userid){
		$userid = save($userid);
		updateActivity($userid);
		$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' OR receiver='$userid' ORDER BY timestamp DESC LIMIT 1");
		$chatData =  mysql_fetch_array($chatSQL);
		
		
		if($chatData['read'] == 0){
			return $chatData['id'];
		}
		
	}
	
	function getUnseenMessageAuthors($userid){;
		$chatSQL = mysql_query("SELECT * FROM `messages` WHERE (`sender`='$userid' AND `seen`='0') OR (`read`='0' AND `receiver`='$userid')");
		while($chatData =  mysql_fetch_array($chatSQL)){
			if($chatData['sender'] == $userid){
				if(!in_array($chatData['receiver'], $return))
					$return[] = $chatData['receiver'];
			}else if($chatData['receiver'] == $userid){
				
				if(!in_array($chatData['sender'], $return))
					$return[] = $chatData['sender'];
			}
		}
		return $return;
	}
	
	function getMessages($userid, $buddyId, $limit){
		$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddyId' OR sender='$buddyId' && receiver='$userid' ORDER BY timestamp DESC LIMIT $limit");
		while($chatData =  mysql_fetch_array($chatSQL)){
			$id = $chatData['id'];
			$return[$id] = $chatData;
		}
		return $return;
	}
	
	
	function showMessages($userid, $buddyId, $limit){
		
		$buddyData = getUserData($buddyId);
		$userData = getUserData($userid);
		
				
		
		
		$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddyId' OR sender='$buddyId' && receiver='$userid' ORDER BY timestamp DESC LIMIT $limit");
		while($chatData = mysql_fetch_array($chatSQL)) {
	    
		    if($chatData['receiver'] == getUser() && $chatData['read'] == "0"){
		    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
		    }
		    if($chatData['sender'] == $_SESSION['userid'] && $chatData['seen'] == "0"){
		    mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
		    }
		    
		    $sender = $chatData['sender'];
		    $whileid = getUser();
		    if($sender == $userid){
			    $receiver = $buddyId;
			    $authorid =  $userData['userid'];
			    $class = 'incoming';
		    } else {
			    $authorid =  $buddyData['userid'];
			    $receiver = $userid;
			    $class = 'outgoing';
		    }
		    
			$authorName = useridToUsername($authorid);
			$buddyName = str_replace(" ","_",$buddyData['username']); //replace spaces with _ to get classname
			
			
			//check if message is crypted
		    if($chatData['crypt'] == "1"){
		    	
				$messageClasses = "cryptedChatMessage_$buddyId";
		        $message = $chatData['text'];
			} else{
				
				$messageClasses = "";
		        $message = $chatData['text'];
		    }
		    $message = universeText($message);
			
			//show message
		              echo '<div class="box-shadow chatMessage '.$class.'">';
		              	echo '<span class="username">';
						echo showUserPicture($authorid, "15");
						echo $authorName;
		              	echo "</span>";
		              	echo'<span class="timestamp pull-right">'.universeTime($chatData['timestamp']).'</span>';
		              	echo'<span class="chatMessage_'.$buddyId.' '.$messageClasses.'" data-sender="'.$authorid.'" data-receiver="'.$receiver.'" data-id="'.$chatData['id'].'" data-decrypted="false">'.$message.'</span>';
		              echo'</div>';
			}
	
	}

   //gets all unseen messages for receiver=user
   function getLastMessages($user=NULL){
   	if($user == NULL){
   		$user = getUser();
   	}else{
   		$user = save($user);
   	}
   	$listedUsers[] = $user;
	$newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  receiver='$user' OR sender='$user' ORDER BY timestamp DESC LIMIT 0, 5");
	while($newMessagesData = mysql_fetch_array($newMessagesSql)){
		$session .= "newMessage $newMessagesData[id]";
		
		
		//each sender is only listed once
		if(!in_array($newMessagesData['sender'], $listedUsers)){
	    $text = substr($newMessagesData['text'], 0, 100);

		//define everything that is important	    
		$return['messageId'] = $newMessagesData['id'];
		$return['sender'] = $newMessagesData['sender'];
		$return['receiver'] = $newMessagesData['receiver'];
		$return['timestamp'] = $newMessagesData['timestamp'];
		$return['text'] = $newMessagesData['text'];
		
		$return['seen'] = $newMessagesData['seen'];
		$return['read'] = $newMessagesData['read'];
		$return['senderUsername'] = useridToUsername($newMessagesData['sender']);
		
		//add sender too users array
		$listedUsers[] = $newMessagesData['sender'];
		
		//add all return data to returner array
		$returner[] = $return;
			
		}
	}
	
	return $returner;
	
   }
   
   function getMessageData($messageId){
   	$newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  id='".mysql_real_escape_string($messageId)."'");
	return mysql_fetch_array($newMessagesSql);
   }
   
//fav
//fav
//fav

  function addFav($type, $typeid, $userid){
      $type = $_GET['type'];
      $check = mysql_query("SELECT type,item FROM fav WHERE type='$_GET[type]' && item='$_GET[item]'");
      $checkData = mysql_fetch_array($check);
      if(isset($checkData[type])){
        echo"allready your favourite";
      } else {
        $time = time();
        mysql_query("INSERT INTO fav (`type` ,`item` ,`user` ,`timestamp`) VALUES('$_GET[type]', '$_GET[item]', '$_SESSION[userid]', '$time');"); 
        echo"worked :)";
    }
  }

    
    function showFav($user=NULL){ 
                        if($user == NULL){
                        	$user = getUser();
                       	}
                        
                    	$userFavs = getUserFavs($user);
						$i = 0;
					foreach($userFavs AS $filefdata){
							$item = $filefdata['item'];
                            $type = $filefdata['type'];
                            
                            //derive the table and the image from fav-type
                            if($type == "folder"){
                                $typeTable = "folders";
                                $img = "filesystem/folder.png";
                                $link = "openFolder($item); return false;";
                            }else if($type == "element"){
                                $typeTable = "elements";
                                $img = "filesystem/element.png";
                                $link = "openElement($item); return false;";
                            }else if($type == "file"){
                                $typeTable = "files";
                                $fileType = fileIdToFileType($item);
                                $img = "fileIcons/".getFileIcon($fileType, $size=NULL);
                                
                            }else if($type == "link"){
                                $typeTable = "links";
                                $fileType = linkIdToFileType($item);
                                $img = "fileIcons/".getFileIcon($fileType, $size=NULL);
                                
                            }
                            $favFolderSql = mysql_query("SELECT * FROM $typeTable WHERE id='$item'");
                            $favFolderData = mysql_fetch_array($favFolderSql);
                            if($filefdata['type'] == "folder"){
                            $filefdata['title'] = $filefdata['name'];
                            }
                            if($i%2 == 0){
                                $color="FFFFFF";
                            }else {
                                $color="e5f2ff";
                            }
                            $i++;
							
							$output .= "<tr class=\"strippedRow\" onmouseup=\"showMenu('folder$filefdata[id]')\">";
								$output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\" width=\"35\">&nbsp;<img src=\"./gfx/icons/$img\" height=\"20\"></td>";
								$output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\"><a href=\"#\" onclick=\"$link\">".$favFolderData['name'].""."".$favFolderData['title']."/</a></td>";
                                    if($user == getUser()){
                                    	
                                    $output .= "<td align=\"right\"><a class=\"btn btn-mini\" onclick=\"removeFav('$type', '$item')\"><i class=\"icon-remove\"></i></a></td>";
                                    
                                    }

                                $output .= "</tr>";
                         }
						   if($i == 0){
						   		$output .="<tr>";
							   		$output .="<td colspan=\"2\" style=\"padding: 5px; padding-top: 12px;\">";
									$output .="You don't have any favourites so far. Add folders, elements, files, playlists or other items to your favourites and they will appear here.";
							   		$output .="</td>";
						   		$output .="</tr>";
						   }
						   
						   return $output;
        
        
    }

	function removeFav($type, $item){
		
			if(mysql_query("DELETE FROM fav WHERE type='".mysql_real_escape_string($type)."' AND user='$_SESSION[userid]' AND item='".mysql_real_escape_string($item)."'")){
				return true;
			}
		
	}
    
//comments
//comments
//comments	
	
  function addComment($type, $itemid, $author, $message){
     $time = time();
     mysql_query("INSERT INTO comments (`type`,`typeid`,`author`,`timestamp`,`text`, `privacy`) VALUES('$type','$itemid','$author','$time','$message', 'p');");
     $commentId = mysql_insert_id();
     if($type == "feed"){
         //fügt Benachrichtigung für den Author des Feeds hinzu, falls ein anderer User einen Kommentar erstellt
         $feedSql = mysql_query("SELECT owner FROM userfeeds WHERE feedid='$itemid'");
         $feedData = mysql_fetch_array($feedSql);
         if($_SESSION['userid'] !== "$feedData[owner]"){
         mysql_query("INSERT INTO personalEvents (`owner`,`user`,`event`,`info`,`eventId`,`timestamp`) VALUES('$feedData[owner]','$_SESSION[userid]', 'comment','feed','$itemid','$time');");
         }
	   }
	   else if($type == "profile"){
	        mysql_query("INSERT INTO personalEvents (`owner`,`user`,`event`,`info`,`eventId`,`timestamp`) VALUES('$itemid','$_SESSION[userid]', 'comment','profile','$itemid','$time');");
	   }
   }
   
   function deleteComments($type, $itemid){
       if(mysql_query("DELETE FROM `comments` WHERE `type`='$type' AND `typeid`='$itemid'")){
           return true;
       }
   }
   
function countComment($type, $itemid){
    $result = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp");
    $num_rows = mysql_num_rows($result);
    return $num_rows;
}

function showComments($type, $itemid) {
    if(proofLogin()){?>
    <div id="<?=$type;?>Comment_<?=$itemid;?>">    
     <script>
     $('#addComment').submit(function() {
     return false;
     });
    </script>
    <div class="shadow commentRow">
      <center>
      <form action="showComment.php" method="post" id="addComment" target="submitter">
          <table>
              <tr>
                  <td><?=showUserPicture(getUser(), "25");?></td>
                  <td><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%; height:17px;"></td>
                  <td><input type="submit" value="send" class="btn btn-small" name="submitComment" style="margin-left:13px;"></td>
              </tr>
                <input type="hidden" name="itemid" value="<?=$itemid;?>">
                <input type="hidden" name="user" value="<?=$_SESSION[userid];?>">
                <input type="hidden" name="type" value="<?=$type;?>">
          </table>
      </form>
      </center>
    </div>

    <?php
    }
    if($type == "comment"){
        $comment_sql = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp DESC");
        while($comment_data = mysql_fetch_array($comment_sql)) {?>
            <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment" style="background-color: #FFF;">
            <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
            <br><?=$comment_data['text'];?><br><br>

            <div style="padding: 15px; "><div><span style="float:left;"><?=showScore(comment, $comment_data[id]);?></span><span style="float:left;"><?=showItemSettings('comment', $comment_data['id']);?></span></div></div>
            </div>
            <?php
            }

    }else{
    $comment_sql = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp DESC");
    while($comment_data = mysql_fetch_array($comment_sql)) { 
    $jsId = $comment_data['id'];
    ?>
    <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment">
    <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
    <br><?=$comment_data['text'];?><br><br>

    <div style="padding: 15px; margin-bottom: 20px;"><div><div style="float:left;"><?=showScore('comment', $comment_data['id']);?></div><div style="float:left; margin-left: 10px;"><?=showItemSettings('comment', $comment_data['id']);?></div></div>
				<a href="javascript:showSubComment(<?=$jsId;?>);" class="btn btn-mini" style="float: right; margin-right: 30px; color: #606060;"><i class="icon-comment"></i>&nbsp;(<?=countComment("comment", $comment_data['id']);?>)</a></div>
                <div class="shadow subComment" id="comment<?=$jsId;?>" style="display: none;"></div>
    </div>
<?php
}}
echo"</div>";
}
    
    function showFeedComments($feedid) 
    { ?>
    <div id="feedComment_<?=$feedid;?>">
    <div class="shadow comments">
    <?php
    if(proofLogin()){
        ?>
    <div class="shadow commentRow" id="feedfeed" style="margin:5px;">
      <center> 
          <form action="showComment.php" method="post" id="addComment" target="submitter">
              <table>
                  <tr>
                      <td width="10"></td>
                      <td><?=showUserPicture($_SESSION['userid'], "25");?></td>
                      <td style="vertical-align:middle;"><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%;"></td>
                      <td><input type="submit" value="send" class="btn btn-small" name="submitComment" style=""></td>
                      <td width="10"></td>

                  </tr><input type="hidden" name="itemid" value="<?=$feedid;?>"><input type="hidden" name="user" value="<?=$_SESSION['userid'];?>"><input type="hidden" name="type" value="feed">
              </table>
          </form>
      </center>
    </div>
        <? }else{
            echo"Please log in to write a comment";
        } ?>

    <?php

    $comment_sql = mysql_query("SELECT * FROM comments WHERE type='feed' && typeid='$feedid' ORDER BY timestamp DESC");
    while($comment_data = mysql_fetch_array($comment_sql)) { 
    ?>
    <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="feedComment">
    <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
        <div style="margin: 7px;">
            <br>
            <?=$comment_data['text'];?><br><br>
            <div style="padding: 15px;"><div style="margin-bottom:15px;"><span style="float:left;margin-right:15px;"><?=showScore('comment', $comment_data['id']);?></span><span style="float:left;"><?=showItemSettings('comment', $comment_data['id']);?></span></div></div>
        </div>
    </div>
    <?php
    }
    echo"</div>";
    echo"</div>";
    }

//groups
//groups
//groups

	function userJoinGroup($group, $user=NULL){
		
		$userid = getUser();
		
		if(empty($user)){
			$user = $userid;
		}
		
        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$group', 'user', '$user', '$time', '$userid');");
         
	}
	
	function userLeaveGroup($group, $user=NULL){
		if(mysql_query("DELETE FROM `groupAttachments` WHERE group='$group' AND item='user' AND itemId='".save($user)."'")){
			return true;
		}
	}

	function getGroups($userid=NULL){
		//moved to class groups->get();
		$groups = new Groups();
		return $groups->get($userid);
		
	}
	
	function getGroupData($groupId){
		$data = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE id='".mysql_real_escape_string($groupId)."'"));
		return $data;
	}

	function getGroupName($groupId){
		$data = mysql_fetch_array(mysql_query("SELECT title FROM groups WHERE id='".mysql_real_escape_string($groupId)."'"));
		return $data['title'];
	}
	
    function countGroupMembers($groupId){
        $total = mysql_query("SELECT COUNT(*) FROM `groupAttachments` WHERE `group`='$groupId' AND `item`='user' AND `validated`='1' "); 
        $total = mysql_fetch_array($total); 
        return $total[0];
    }
	
  function createGroup($title, $privacy, $description, $users){
  	
	
            $userid = getUser();

            //check if nessecary informations are given
            if((isset($description)) && (isset($title)) && (isset($privacy))){
            //insert group into db    
            mysql_query("INSERT INTO `groups` (`title`, `description`, `public`, `admin`) VALUES ('$title', '$description', '$privacy', '$userid');");
            $groupId = mysql_insert_id();

                //add users to group
                if(isset($users)){
                foreach ($users as &$user) {

                mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$groupId', 'user', '$user', '$time', '$userid');");


                }}
                
                $groupFolder = createFolder("3", $groupId, $userid, "$groupId//$groupId");
				$groupElement = createElement($groupFolder, $title, "other", $userid,  "$groupId//$groupId");
                mysql_query("UPDATE `groups` SET `homeFolder`='$groupFolder', `homeElement`='$groupElement' WHERE id='$groupId'");

           		//add user which added group to group and validate
           		 mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$groupId', 'user', '$userid', '$time', '$userid', '1');");

				return true;

           	}else{
                jsAlert("please fill out everything");
            }
  	
  }

  function deleteUserFromGroup($userid, $groupid){
		
	if(mysql_query("DELETE FROM groupAttachments WHERE `group`='".save($groupid)."' AND `item`='user' AND `itemId`='".save($userid)."'")){
		return true;
	}
  }
  
  function updateGroup($groupId, $privacy, $description, $membersInvite){
  	
  		if(mysql_query("UPDATE groups SET public='$privacy', description='$description', membersInvite='$membersInvite' WHERE id='$groupId'")){
  			return true;
  		}
         
  }
  
  function groupMakeUserAdmin($groupId, $userId){
  	
		$groupData = getGroupData($groupId);
		
		$adminString = $groupData['admin'];
		
		//proof if user is allready admin
		$admins = explode($adminString, ";");
		if(!in_array("$userId", $admins)){
			$adminString = "$adminString;$userId";
			
			if(mysql_query("UPDATE `groups` SET `admin`='$adminString' WHERE id='".save($groupId)."'")){
				return true;
			}
			
		}
  	
  }
  function groupRemoveAdmin($groupId, $userId){
  	
		$groupData = getGroupData($groupId);
		
		$adminString = $groupData['admin'];
		
		//proof if user is allready admin
		$admins = explode($adminString, ";");
  }


//basic universe stuff

   function jsAlert($text){
        ?>
        <script>
        //check if function is calles from window or from iframe
        //if it is called from iframe parent. needs to be used
        if (typeof(window.jsAlert) === "function") {
		   jsAlert('', '<?=addslashes($text);?>');
		}else{
		   parent.jsAlert('', '<?=addslashes($text);?>');
		}
        </script> 
            <?php
    }
  function universeTime($unixtime){
     $time = time();
     $difference = ($time - $unixtime);
     if($difference < 60){
         $unTime = "just";
     } else if($difference > 60 && $difference < 600){
         $unTime = "some minutes ago";
     } else if($difference > 600 && $difference < 3600){
         $unTime = round($difference / 60);
         $unTime = "$unTime minutes ago";
     } else if($difference > 3600 && $difference < 3600*24){
         $unTime = "one day ago";
     } else if($difference > 3600*24 && $difference < 3600*24*31){
         $udTime = round($difference / 86400);
         $unTime = "$udTime days ago";
     } else if($difference > 3600*24*31){
         $unTime = "one month ago";
     }
     return $unTime;
     
  }
     
  
   
    function plusOne($type, $typeid){
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       //score++
       }
       function minusOne($type, $typeid){
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
           
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       } //score--
       
    
    function showScore($type, $typeid, $reload=NULL) {
        if(proofLogin()){
               if($type == "comment"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM comments WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql);
               }
               else if($type == "feed"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM feed WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "folder"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM folders WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "element"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM elements WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "file"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM files WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "link"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM links WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               if(!isset($reload)){
                   $output =  "<div class=\"score$type$typeid\">";
               }
			   
			   
			   if($scoreData['score'] > 0){
			   	$class = "btn-success";
			   }else if($scoreData['score'] < 0){
			   	$class = "btn-warning";
			   }else{
			   	$class = '';
			   }
			   
			   $output .= '<div class="btn-toolbar" style="margin: 0px;">';
			   $output .= '<div class="btn-group">';
			   $output .="<a class=\"btn btn-mini\" href=\"doit.php?action=scoreMinus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-down\"></i></a>";
			   $output .= "<p class=\"btn btn-mini $class\" href=\"#\">$scoreData[score]</p>";
			   $output .= "<a class=\"btn btn-mini\" href=\"doit.php?action=scorePlus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-up\"></i></a>";
			   $output .= '</div>';
			   $output .= '</div>';
               if(!isset($reload)){
                   $output .=  "</div>";
               }
			   
			   return $output;
        }
           
       }

		function showLanguageDropdown($value=NULL){
			//References :
		    //1. http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
		    //2. http://blog.xoundboy.com/?p=235
			$languages = array(
	        'en' => 'English' , 
	        'aa' => 'Afar' , 
	        'ab' => 'Abkhazian' , 
	        'af' => 'Afrikaans' , 
	        'am' => 'Amharic' , 
	        'ar' => 'Arabic' , 
	        'as' => 'Assamese' , 
	        'ay' => 'Aymara' , 
	        'az' => 'Azerbaijani' , 
	        'ba' => 'Bashkir' , 
	        'be' => 'Byelorussian' , 
	        'bg' => 'Bulgarian' , 
	        'bh' => 'Bihari' , 
	        'bi' => 'Bislama' , 
	        'bn' => 'Bengali/Bangla' , 
	        'bo' => 'Tibetan' , 
	        'br' => 'Breton' , 
	        'ca' => 'Catalan' , 
	        'co' => 'Corsican' , 
	        'cs' => 'Czech' , 
	        'cy' => 'Welsh' , 
	        'da' => 'Danish' , 
	        'de' => 'German' , 
	        'dz' => 'Bhutani' , 
	        'el' => 'Greek' , 
	        'eo' => 'Esperanto' , 
	        'es' => 'Spanish' , 
	        'et' => 'Estonian' , 
	        'eu' => 'Basque' , 
	        'fa' => 'Persian' , 
	        'fi' => 'Finnish' , 
	        'fj' => 'Fiji' , 
	        'fo' => 'Faeroese' , 
	        'fr' => 'French' , 
	        'fy' => 'Frisian' , 
	        'ga' => 'Irish' , 
	        'gd' => 'Scots/Gaelic' , 
	        'gl' => 'Galician' , 
	        'gn' => 'Guarani' , 
	        'gu' => 'Gujarati' , 
	        'ha' => 'Hausa' , 
	        'hi' => 'Hindi' , 
	        'hr' => 'Croatian' , 
	        'hu' => 'Hungarian' , 
	        'hy' => 'Armenian' , 
	        'ia' => 'Interlingua' , 
	        'ie' => 'Interlingue' , 
	        'ik' => 'Inupiak' , 
	        'in' => 'Indonesian' , 
	        'is' => 'Icelandic' , 
	        'it' => 'Italian' , 
	        'iw' => 'Hebrew' , 
	        'ja' => 'Japanese' , 
	        'ji' => 'Yiddish' , 
	        'jw' => 'Javanese' , 
	        'ka' => 'Georgian' , 
	        'kk' => 'Kazakh' , 
	        'kl' => 'Greenlandic' , 
	        'km' => 'Cambodian' , 
	        'kn' => 'Kannada' , 
	        'ko' => 'Korean' , 
	        'ks' => 'Kashmiri' , 
	        'ku' => 'Kurdish' , 
	        'ky' => 'Kirghiz' , 
	        'la' => 'Latin' , 
	        'ln' => 'Lingala' , 
	        'lo' => 'Laothian' , 
	        'lt' => 'Lithuanian' , 
	        'lv' => 'Latvian/Lettish' , 
	        'mg' => 'Malagasy' , 
	        'mi' => 'Maori' , 
	        'mk' => 'Macedonian' , 
	        'ml' => 'Malayalam' , 
	        'mn' => 'Mongolian' , 
	        'mo' => 'Moldavian' , 
	        'mr' => 'Marathi' , 
	        'ms' => 'Malay' , 
	        'mt' => 'Maltese' , 
	        'my' => 'Burmese' , 
	        'na' => 'Nauru' , 
	        'ne' => 'Nepali' , 
	        'nl' => 'Dutch' , 
	        'no' => 'Norwegian' , 
	        'oc' => 'Occitan' , 
	        'om' => '(Afan)/Oromoor/Oriya' , 
	        'pa' => 'Punjabi' , 
	        'pl' => 'Polish' , 
	        'ps' => 'Pashto/Pushto' , 
	        'pt' => 'Portuguese' , 
	        'qu' => 'Quechua' , 
	        'rm' => 'Rhaeto-Romance' , 
	        'rn' => 'Kirundi' , 
	        'ro' => 'Romanian' , 
	        'ru' => 'Russian' , 
	        'rw' => 'Kinyarwanda' , 
	        'sa' => 'Sanskrit' , 
	        'sd' => 'Sindhi' , 
	        'sg' => 'Sangro' , 
	        'sh' => 'Serbo-Croatian' , 
	        'si' => 'Singhalese' , 
	        'sk' => 'Slovak' , 
	        'sl' => 'Slovenian' , 
	        'sm' => 'Samoan' , 
	        'sn' => 'Shona' , 
	        'so' => 'Somali' , 
	        'sq' => 'Albanian' , 
	        'sr' => 'Serbian' , 
	        'ss' => 'Siswati' , 
	        'st' => 'Sesotho' , 
	        'su' => 'Sundanese' , 
	        'sv' => 'Swedish' , 
	        'sw' => 'Swahili' , 
	        'ta' => 'Tamil' , 
	        'te' => 'Tegulu' , 
	        'tg' => 'Tajik' , 
	        'th' => 'Thai' , 
	        'ti' => 'Tigrinya' , 
	        'tk' => 'Turkmen' , 
	        'tl' => 'Tagalog' , 
	        'tn' => 'Setswana' , 
	        'to' => 'Tonga' , 
	        'tr' => 'Turkish' , 
	        'ts' => 'Tsonga' , 
	        'tt' => 'Tatar' , 
	        'tw' => 'Twi' , 
	        'uk' => 'Ukrainian' , 
	        'ur' => 'Urdu' , 
	        'uz' => 'Uzbek' , 
	        'vi' => 'Vietnamese' , 
	        'vo' => 'Volapuk' , 
	        'wo' => 'Wolof' , 
	        'xh' => 'Xhosa' , 
	        'yo' => 'Yoruba' , 
	        'zh' => 'Chinese' , 
	        'zu' => 'Zulu' , 
	        );
			
			
			
			$return .= "<select name=\"language\">";
			
			
			foreach($languages AS $language){
				if(isset($value)){
					if($language == $value){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
				}
				$return .= "<option $selected>$language</option>";
				
			}
			
			$return .= "</select>";
			
			return $return;
		}
    



//buddylist
//buddylist
//buddylist

	function addBuddy($buddyid, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddyid);
		$timestamp = time();
        $check = mysql_query("SELECT * FROM buddylist WHERE (owner='".$user."' && buddy='$buddy') OR (buddy='".$user."' && owner='$buddy')");
		$checkData = mysql_fetch_array($check);
		
        if(!isset($checkData['owner'])){
        	$message = true;
            if($buddy == $user){
				//buddy = user
                $message = false;
            }
            $requestSQL = mysql_query("SELECT * FROM user WHERE userid='$buddy'");
            $requestData = mysql_fetch_array($requestSQL);
			
            if($requestData['priv_buddyRequest'] == "1"){
                $request = "1";
            } else{
                $request = "0";
            }
			
	       if($message){
	        mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('".$user."', '$buddy', '$timestamp', '$request');");
	
	        
	        //if privacy settings dont need allowance, the user needs to be added on the buddies buddylist
	        if($request == "0"){
	        	mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('$buddy', '".$user."', '$timestamp', '$request');");
	        }
	        
	        $message = true;
	       }

        } else {
        	///already there
            $message = false;
        }
		return $message;
		
	}

	function replyRequest($buddy, $user=false){
		
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddy);
		
		
        $value = "0";
		$timestamp = time();
		
     	mysql_query("UPDATE buddylist SET request='$value' WHERE owner='".mysql_real_escape_string($buddy)."' && buddy='".$user."'");
 		mysql_query("INSERT INTO `buddylist` (`owner`, `buddy`,`timestamp`,`request`) VALUES('".$user."', '".mysql_real_escape_string($buddy)."', '$timestamp', '0');");
		return true;
	}

	function denyRequest($buddy, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddy);
		
        mysql_query("DELETE FROM buddylist WHERE owner='".$buddy."' && buddy='".$user."'");
        mysql_query("DELETE FROM buddylist WHERE owner='".$user."' && buddy='".$buddy."'");
        return true;
	}


   function deleteBuddy($buddy, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		mysql_query("DELETE FROM `buddylist` WHERE owner='".save($user)."' AND buddy='".save($buddy)."'");
		mysql_query("DELETE FROM `buddylist` WHERE buddy='".save($user)."' AND owner='".save($buddy)."'");
	
   }
   
   function getOpenRequests($user=NULL){
        if(empty($user)){
            $user= getUser();
        }
		
		$friendRequestSql = mysql_query("SELECT owner FROM buddylist WHERE buddy='".$user."' && request='1'");
		while($friendRequestData = mysql_fetch_array($friendRequestSql)){
			$userid = $friendRequestData['owner'];
			$return[$userid] = useridToUsername($userid);
		}
		
		return $return; 
   }

   function buddyListArray($user=NULL, $request=0){
   //returns all buddies of $user
        if(empty($user)){
            $user= getUser();
        }
        
        
        $buddylistSql = mysql_query("SELECT buddy FROM buddylist WHERE owner='$user' && (request='$request')");
        while($buddylistData = mysql_fetch_array($buddylistSql)) {
            $buddies[] = $buddylistData['buddy'];
        }
        
        return $buddies;
       
   }
   
   function buddy($userid){
   	//checks if user with id=$userid is buddy
   	//of user with id=$_SESSION[userid]
   	
   	$buddies = buddyListArray($userid);
	
	if(in_array($_SESSION['userid'], $buddies) OR $userid == $_SESSION['userid']){
		return true;
	}
   }
   
   function friendsYouMayKnow(){
       //returns an array with people that match the buddylist of >2 friends
       
       //load all buddies of user into an array
       //request is used for sql-injection to also
       //get buddies who didn't answered the
       //request
       $buddies = buddyListArray('', "1' OR request='0");
       
	   $notSuggest = getNotSuggestList(); //get array of users that will not be suggested
       
       //return every single buddy
       foreach($buddies AS &$buddy){
           
           //get every buddy of this buddy
           $buddiesOfBuddy = buddyListArray($buddy);
           
           
           //return every single buddy of this buddy
           foreach($buddiesOfBuddy AS &$buddyOfBuddy){
               //the most counted userid will be the userid
               //of the user who send the request so it has
               //to be removed from the whole array
               //
               //all buddies which are allready in the users
               //buddylist also need to be removed
               if($buddyOfBuddy != getUser() && !in_array($buddyOfBuddy, $buddies) && $buddyOfBuddy != 0 && !in_array($buddyOfBuddy, $notSuggest)){
                    $finalArray[] = $buddyOfBuddy;
               }
           }
           
           
       }
       
       //gives out the value inside the array which occures most
       $c = array_count_values($finalArray); 
       $return = array_search(max($c), $c);

       return $return;
       
   }

	function getNotSuggestList(){
         $userSql = mysql_query("SELECT buddySuggestions FROM user WHERE userid='".getUser()."'");
         $userData = mysql_fetch_array($userSql);
		 $buddySuggestions = $userData['buddySuggestions'];
		 
		 if(!empty($buddySuggestions)){
		 	
		 	$buddySuggestions = json_decode($buddySuggestions, true);
		 
			foreach($buddySuggestions AS $buddySuggestion){
				$return[] = $buddySuggestion['user'];
			}
			
			return $return;
			
		 }
	}

	function addToNotSuggestList($user){
		
		$user = save(intval($user));
		
		$notSuggest = getNotSuggestList();
		
			if(!in_array($user, $notSuggest)){
				
				//script needs to access db again, to parse also timestamp
				//into the json string. the timestamp will be used to delete
				//old suggestions after a while
				
		        $userSql = mysql_query("SELECT buddySuggestions FROM user WHERE userid='".getUser()."'");
		        $userData = mysql_fetch_array($userSql);
				
				$notSuggest = json_decode($userData['buddySuggestions'],true);
			
				$userObj['user'] = $user;
				$userObj['timestamp'] = time();
				
				$notSuggest[] = $userObj;
				
		        if(mysql_query("UPDATE user SET buddySuggestions='".json_encode($notSuggest)."' WHERE userid='".getUser()."'")){
		        	return true;
		       	}
			}
	}
	
	function showBuddySuggestions($frame=true){
		
		$mayKnow = friendsYouMayKnow();
		if(!empty($mayKnow)){
			if($frame){
				echo"<div id=\"buddySuggestions\">";
			}
			echo"<header>";
				echo"you may know";
				echo"<a id=\"closeSuggestions\" onclick=\"$('#buddySuggestions').hide();\">x</a>";
			echo"</header>";
			echo'<div>';
				echo"<a href=\"#\" onclick=\"showProfile('$mayKnow')\">";
				echo"";
				echo"<span>&nbsp;";
				echo stripslashes(showUserPicture($mayKnow, 16, NULL, true));
				echo"</span>";
				echo useridToUsername($mayKnow);
				echo'<a href="doit.php?action=addbuddy&buddy='.$mayKnow.'" class="btn btn-success btn-mini pull-right" target="submitter">add</a>';
				echo'<a href="doit.php?action=addToNotSuggestList&user='.$mayKnow.'" class="btn btn-mini pull-right" style="margin-right:5px;" target="submitter">later</a>';
				echo"</a>";
			echo'</div>';
			if($frame){
			echo"</div>";
			}
			
		}else{
			echo"<script>$('#buddySuggestions').hide();</script>";
		}
	}

//feed
//feed
//feed
    function showFeed($feedid, $type=NULL, $mobile=NULL) {
        if($mobile == 1){
            $subPath = '1';
        }
        $where = "ORDER BY timestamp DESC LIMIT 30"; //defines Query
        $needStructure = "1"; //defines if whole HTML structure is needed
        if($type == "single"){
            $where = "WHERE owner='$feedid' ORDER BY timestamp DESC LIMIT 0,1";
            $needStructure = "";
        }
        $feedSql = mysql_query("SELECT * FROM userfeeds $where");
        while($feedData = mysql_fetch_array($feedSql)) {
            if($feedData['owner'] == "$_SESSION[userid]"){
                $ownerLink = "<a href=\"doit.php?action=deleteFeed&feedId=$feedData[feedid]\" target=\"submitter\"><img src=\"gfx/trash.gif\" alt=\"delete\" style=\"position: absolute; margin-left: 60px; margin-top: -20px;\"></a>";
            }else{
                unset($ownerLink);
            }
            
            
            
            // in the table elements the elements have a "title", in the table folders the folders have a "name" that realy sucks!
            if($feedData['protocoll_type'] == "fileUpload"){
                $folderAddSql = mysql_query("SELECT id, title FROM elements WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"createNewTab('fileBrowser_tabView','".$folderAddData['title']."','','modules/filesystem/showelement.php?element=".$folderAddData['id']."',true);return false\"> ".$folderAddData[title]."</a>";
                }if($feedData['protocoll_type']=="elementAdd"){
                $folderAddSql = mysql_query("SELECT id, name FROM folders WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData['id']."&reload=1');return false\"> ".$folderAddData['name']."/</a>";   
                }if($feedData['protocoll_type']=="folderAdd"){
                $folderAddSql = mysql_query("SELECT id, name FROM folders WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData[id]."&reload=1');return false\"> ".$folderAddData[name]."/</a>";   
                }
                

?>
    <? if($needStructure == "1"){ ?><div id="add"></div><? } ?>
    <div id="realFeed" class="feedNo<?=$feedData['feedid'];?>">
        <?=userSignature($feedData['owner'], $feedData['timestamp']);?>
        <div style="padding: 10px;"><?=nl2br(htmlspecialchars($feedData['feed']));?><?=$text;?></div><br>
        <div style="padding: 15px;"><div><?=showScore("feed", $feedData['feedid']);?><div style=" width: 150px; float:right; margin-top: -24px;"><?=showItemSettings('feed', "$feedData[feedid]");?></div></div></div>
                <a href="javascript:showfeedComment(<?=$feedData['feedid'];?>);" class="btn btn-mini" style="float: right; margin-top: -29px; margin-right: 5px; color: #606060">comments&nbsp;(<?=countComment("feed", $feedData[feedid]);?>)</a><div class="shadow" id="feed<?=$feedData[feedid];?>" style="padding:15px; display:none;"></div>
    </div>
    <?php
                //feeds like the login feed are deleted after the validity passed
                if($feedData['validity'] == TRUE) {
                $time = time();
                if($feedData['validity'] < $time){
                    mysql_query("DELETE FROM userfeeds WHERE feedid='$feedData[feedid]'");
                }}
                unset($text);
    }
    ?>
    <div>...load more</div>
    <?php
    
    }
    
   function addFeed($owner, $feed, $type, $feedLink1, $feedLink2, $validity=NULL, $groups=NULL){
       $time = time();
       if(empty($validity)){
           $validity = ($time + $validity); 
       }
       
       if($validity == $time) {
           $validity = "0"; //otherwise validity = time() => feed will be deleted
       }
       if(isset($groups)){
           $privacy = $groups;
       }
       mysql_query("INSERT INTO userfeeds (`owner`,`timestamp`,`validity`,`feed`,`protocoll_type`,`feedLink1`,`feedLink2`,`privacy`) VALUES('$owner', '$time', '$validity', '$feed', '$type', '$feedLink1', '$feedLink2', '$privacy');");
    }
    
    function createFeed($author, $feed, $validity, $type, $privacy, $attachedItem=NULL, $attachedItemId=NULL){
        $feed = mysql_real_escape_string($feed);
		
       //if privacy==h feed is not shown anyway so an insert would be jabberuserless
       if($privacy != "h"){
           
       $time = time();
       if(empty($validity)){
           $validity = ($time + $validity); 
       }
       
       if($validity == $time) {
           $validity = "0"; //otherwise validity = time() => feed will be deleted
       }
       mysql_query("INSERT INTO `feed` (`id`, `author`, `feed`, `timestamp`, `validity`, `type`, `attachedItem`, `attachedItemId`, `privacy`) VALUES (NULL, '$author', '$feed', '$time', '$validity', '$type', '$attachedItem', '$attachedItemId', '$privacy');");
       $id = mysql_insert_id();
       
       return $id;
       
       }
    }
    
    
   function deleteFeeds($type, $itemid){
       if(mysql_query("DELETE FROM feed WHERE attachedItem='$type' AND attachedItemId='$itemid'")){
           return true;
       }
   }
    
    function showFeedNew($type, $user=NULL, $limit=NULL, $feedId=NULL){
        
        if(empty($limit)){
            $limit = "0,30";
        }
        
        switch($type){
            
            //shows every entry in the system
            case "public":
                
                $where = "ORDER BY timestamp DESC LIMIT $limit"; //defines Query
                $needStructure = "1"; //defines if whole HTML structure is needed
                
                
            break;
        
            //shows just entries of buddies
            case "friends":
                
                
                //get all users which are in the buddylist
                $buddies = buddyListArray();
                $buddies[] = getUser();
                $buddies = join(',',$buddies);  
                //push array with the user, which is logged in
                
                $where = "WHERE author IN ($buddies) ORDER BY timestamp DESC LIMIT  $limit";
                $needStructure = "";
                
            break;
        
            //only shows entries of one user
            case "singleUser":
            
                $where = "WHERE author='$user' ORDER BY timestamp DESC LIMIT  $limit";
                $needStructure = "";
            break;
        
            //only shows entries which are attached to a grouo $user => $groupId
            case "group":
                
                
                $group = $user; //$user is used in this cased to pass the groupId
                $where = "WHERE INSTR(`privacy`, '{$group}') > 0 ORDER BY timestamp DESC limit $limit";
                $needStructure = "";
                
            break;
        
            //only shows a single feed entry
            case "singleEntry":
                $where = "WHERE id='$feedId'";
                break;
        }
        
        
        
        if(!empty($where)){
            
        $allreadyLoaded = explode(';',$_SESSION["feedsession_$type"]);
        
        $feedSql = mysql_query("SELECT * FROM feed $where");
        while($feedData = mysql_fetch_array($feedSql)) {
            
            if(!in_array($feedData['id'], $allreadyLoaded)){
                $token[] = $feedData['id'];
            }
            
            unset($attachment);
            
            switch($feedData['type']){
                case 'showThumb':
                
                $attachment = showItemThumb($feedData['attachedItem'], $feedData['attachedItemId']);
                break;
            }
            
            
            
           
                

        if($needStructure == "1"){ ?><div id="add"></div><? } ?>
        <div id="realFeed" class="feedNo<?=$feedData['id'];?>">
            <?=userSignature($feedData['author'], $feedData['timestamp']);?>
            <div style="padding: 10px;"><?=nl2br(universeText(htmlspecialchars($feedData['feed'])));?><?=$text;?></div><br>
            <div style="padding: 15px;">
                <?=$attachment;?>
            </div>
            <div style="padding: 15px;">
                <div>
                    <?=showScore("feed", $feedData['id']);?>
                    <div style="float:right; position: absolute; margin-top: -24px; margin-left: 108px;"><?=showItemSettings('feed', $feedData['id']);?></div>
                </div>
            </div>
            <a href="javascript:showfeedComment(<?=$feedData['id'];?>);" class="btn btn-mini" style="float: right; margin-top: -38px; margin-right: 15px; color: #606060"><i class="icon-comment"></i>&nbsp;(<?=countComment("feed", $feedData[id]);?>)</a>
            <div class="shadow" id="feed<?=$feedData['id'];?>" style="display:none;"></div>
            
        </div>
                <?php
                //feeds like the login feed are deleted after the validity passed
                if($feedData['validity'] == TRUE) {
                $time = time();
                if($feedData['validity'] < $time){
                    mysql_query("DELETE FROM feed WHERE id='$feedData[id]'");
                }}
                unset($text);
    }
    $_SESSION["feedsession_$type"] = implode(';', $token);
        
        
    }}
    
    
    
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
    function init(){
    	
		
         $checkSql = mysql_query("SELECT userid, hash FROM user WHERE userid='$_SESSION[userid]'");
         $checkData = mysql_fetch_array($checkSql);

    }
    function bc_really_big_int($bc_func, $a, $b) {
           // check if function exists
           if(function_exists($bc_func)) {
                   $scale = (strlen($a) > strlen($b))?strlen($a):strlen($b);
                   $afloat = '0.'.str_repeat('0', $scale-strlen($a)).$a;
                   $bfloat = '0.'.str_repeat('0', $scale-strlen($b)).$b;
                   $result = call_user_func($bc_func, $afloat, $bfloat, $scale);
                   $result = str_replace('.', '', $result);
                   return($result);
           } else {
                   return(false);
           }
    }
    function Encode($str){
       $array = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY); //zerlegt String in einzelne Zeichen
       foreach ($array as &$value){ 
       $value = ord($value);
       $txtData = ($value);
       return $txtData;
       }
    }
    function Decode($txtData){
       $txtData = ($txtData);
       $txtData = chr($txtData);
       return $txtData;
    }
    function universeEncode($text, $password){
    $password2 = Encode($password);
    $str = $text;   
        $array = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY); //zerlegt String in einzelne Zeichen
        foreach ($array as &$value){
        $value = Encode($value);
        $value = ($value * $password2);
        $readystring = "$readystring$value;";
        }
        return $readystring;
    }
    function universeDecode($text, $password){
    $password2 = Encode($password);
        $teile = explode(";", $text);
        foreach($teile as &$value){
            $value = ($value / $password2);
            $value = Decode($value);
            $return=  "$return$value";
        }
        return $return;
    }
	
    function commaToOr($string, $type){
        //converts Strings with Values, which are separeted with commas into SQL conform STRINGS
        $string = explode(";", $string);
        foreach($string as &$value){
            if(empty($deddl)){
                $return = "$type='$value'";
                $deddl = "checked";
            }else{
            
            $return = "$type='$value' OR $return";

            }
        }
        return $return;
    }
    
    
    function save($text){
        //should be a standard way to return save POST, GET or SESSION variables
        
        $text = mysql_real_escape_string($text);
        return $text;
    }
    
    
	  /**
	  * finds out if current user is authorized to see or edit
	  * item with $privacy and $author
	  *
	  * @param string $privacy      contains privacy query.
	  *
	  * @param string $type      	see//edit
	  *
	  * @param int $author      	author of the item
	  *
	  * @return bool 
	  */
    function authorize($privacy, $type, $author=NULL){
        
		if(end(explode(";", $privacy)) == "UNDELETABLE"){
			$undeletable = true;
			$privacy = str_replace(";UNDELETABLE", "", $privacy);
		}
		
		
        if(end(explode(";", $privacy)) == "PROTECTED"){
        	
             $show = true;
             if(hasRight("editProtectedFilesystemItem")){
             	$edit = true;
				 $delete = true;
			 }else{
			 	$edit = false;
				$delete = false;
			 }
        	
        }else{
             $show = false;
             $edit = false;
			 $delete = false;
        
	        if($privacy == "p"){
	            
	            
	             $show = true;
	             if(proofLogin()){
	             $edit = true;
				 $delete = true;
				 }
	            
	        }else if($privacy == "h"){
	            
	            if($author == $_SESSION['userid'] && proofLogin()){
	            
	                $show = true;
	                $edit = true;
					$delete = true;
	                
	            }else{
	                
	                $show = false;
	                $edit = false;
					$delete = true;
	                
	            }
	            
	        }else{
	            
	
	
	                $custom = explode("//", $privacy);
	                $customShow = $custom[1];
	                $customShow = explode(";", $customShow);
	                $customEdit = $custom[0];
					
					if($customEdit == "h" && $author == getUser()){
	                	$edit = true;
					}
	                $customEdit = explode(";", $customEdit);
	                
	                //check if friends are allowed to see or edit this
	                if((in_array("f", $customShow) || in_array("f", $customEdit)) && proofLogin()){
	                    if($author == getUser()){
	                            //if friends are allowed to see => show = true
	                            if(in_array("f", $customShow)){
	                                $show = true;
	                            }
	                            //if friends are allowed to edit => show = true
	                            if(in_array("f", $customEdit)){
	                                $edit = true;
	                                
	                            }
	                    	
	                    }
	                    //get friends from SQL unefizient
	                    $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$author' && request='0'");
	                    while($buddylistData = mysql_fetch_array($buddylistSql)) {
	                        
	                        //check if user is buddy of $author
	                        if($buddylistData['buddy'] == getUser() && proofLogin()){
	                            //if friends are allowed to see => show = true
	                            if(in_array("f", $customShow)){
	                                $show = true;
	                            }
	                            //if friends are allowed to edit => show = true
	                            if(in_array("f", $customEdit)){
	                                $edit = true;
	                                
	                            }
	                        }
	                    }
	                }
	                
	                
	                
	                
	                if(proofLogin()){
	                //check if user is in group which is allowed to see this item
		                $groupAtSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".getUser()."' and validated='1'");
		                while($groupAtData = mysql_fetch_array($groupAtSql)){
		
		                    if(in_array($groupAtData['group'], $customShow) && proofLogin()){
		                        $show = true;  
		                    }
		                    if(in_array($groupAtData['group'], $customEdit) && proofLogin()){
		                        $edit = true;  
		                    }
		                }
					}
	                
	                
	                
	            
	            
	            
	        }
        }
        
        if($type == "show"){
            return $show;
        }else if($type == "edit"){
            return $edit;
        }else if($type == "delete"){
        	if($undeletable){
        		if(hasRight("editUndeletableFilesystemItems")){
        			return true;
        		}else{
        			return false;
        		}
        	}else{
            	return $delete;
        	}
        }
        
    }
    
	  /**
	  * finds out whether or not a privacies value is "protected"
	  *
	  * @param string $value      contains privacy query.
	  *
	  * @return bool 
	  */
	function isProtected($value){
		if(end(explode(";", $value)) == "PROTECTED"){
			return true;
		}else{
			return false;
		}
	}
	
	  /**
	  * finds out whether or not a privacies value is "undeletable"
	  *
	  * @param string $value      contains privacy query.
	  *
	  * @return bool 
	  */
	function isUndeletable($value){
		if(end(explode(";", $value)) == "UNDELETABLE"){
			return true;
		}else{
			return false;
		}
	}
	
	
	  /**
	  * shows the privacysettings box 
	  *
	  * @param string $value      contains privacy query.
	  *
	  * @return
	  */
    function showPrivacySettings($value=NULL, $editable=true){
    	
		$oldValue = $value;
		if(end(explode(";", $oldValue)) == "PROTECTED"){
        	
			
			$protected = true;
			$value = str_replace(";PROTECTED", "", $value);
        	
        }
		if(end(explode(";", $oldValue)) == "UNDELETABLE"){
        	
			
			$undeletable = true;
			$value = str_replace(";UNDELETABLE", "", $value);
        	
        }
		
    	if(empty($value)){
    		$value = "p";
    	}
		
        if($value == "p"){
            //check checkbox on public
            $checked['privacyPublic'] = 'checked="checked"';
        }else if($value == "h"){
            //check checkbox on hidden
            $checked['privacyHidden'] = 'checked="checked"';
        }else{
			$showCustom = "notHidden";
            //handle other cases
            //   f;4;3;2//f;2;
            $custom = explode("//", $value);
            
            $customShow = $custom[1];
            $customShow = explode(";", $customShow);
            
            if(in_array("f", $customShow)){
                //check checkbox on show friends
                $checked['privacyCustomShowF'] = 'checked="checked"';
            }
            
            
            
            $customEdit = $custom[0];
            $customEdit = explode(";", $customEdit);
            
            
            if(in_array("f", $customEdit)){
                //check checkbox on allow friends to edit
                $checked['privacyCustomEditF'] = 'checked="checked"';
            }
            if(in_array("h", $customEdit)){
                //only authour is allow to edit
                $checked['privacyCustomEditH'] = 'checked="checked"';
            }
            
            
        }
		
		if(true){
			if($protected OR !$editable){
				$disabled = 'disabled="disabled"'; //added to checkboxes
			}
        			if($protected){
        				echo"<li style=\"font-size:16pt;\">Protected</li>";
        			}else if($undeletable){
        				//echo"<li style=\"font-size:16pt;\">Undeletable</li>";
        			}
			if(true){
        ?>
        	<div class="privacySettings">
        		<header>Privacy Settings</header>
        		<ul>
        			<li>
        				<h2><input type="checkbox" name="privacyPublic" value="true" class="privacyPublicTrigger uncheckCustom uncheckHidden" <?=$checked[privacyPublic];?> <?=$disabled;?>>Public</h2>
        				Every user is allowed to see and edit.
        			</li>
        			<li>
        				<h2><input type="checkbox" class="privacyHiddenTrigger uncheckPublic uncheckCustom" name="privacyHidden" value="true" <?=$checked[privacyHidden];?> <?=$disabled;?>>Only me</h2>
        				You are the only one who is allowed to see and edit.
        			</li>
        			<li>
        				<h2><input type="checkbox" class="privacyBuddyTrigger privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked['privacyCustomShowF'];?> <?=$checked['privacyCustomEditF'];?> <?=$disabled;?>>Friends</h2>
        				Friends cann see or edit.
        			</li>
        			<li class="<?=$showCustom;?> sub privacyShowBuddy">
        				<div><input type="checkbox" name="privacyCustomSee[]" value="f" data-privacytype="see" class="privacyCustomTrigger uncheckPublic uncheckHidden privacyBuddyTrigger privacyBuddyTrigger_see" <?=$checked['privacyCustomShowF'];?> <?=$disabled;?>>See</div>
        				<div><input type="checkbox" name="privacyCustomEdit[]" value="f" data-privacytype="edit" class="uncheckPublic privacyCustomTrigger uncheckHidden privacyBuddyTrigger privacyBuddyTrigger_edit" <?=$checked['privacyCustomEditF'];?> <?=$disabled;?>>Edit</div>
        			</li>
        			<li>
        				<h2><input type="checkbox" class="uncheckPublic privacyGroupTrigger privacyCustomTrigger uncheckHidden" <?=$disabled;?>>Groups</h2>
        				Particular Groups can see and edit.
        			</li>
        			<li class="<?=$showCustom;?> sub privacyShowGroups <?=$showBuddy;?>">
        				<ul class="groupList">
        					                           <?
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".getUser()."' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData['title'];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            if(in_array("$groupData[id]", $customEdit)){
                                                                $checked['editGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['editGroup'] = '';
                                                            }
                                                            ?>
                                                                <li>
                                                                	<div><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="createNewTab('reader_tabView','<?=$title10;?>','','group.php?id=<?=$groupData[id];?>',true);return false"><?=$title15;?></a></div>
                                                                	<div>
                                                                		<input type="checkbox" name="privacyCustomSee[]" value="<?=$groupData['id'];?>" data-groupid="<?=$groupData['id'];?>" data-privacytype="see" class="privacyGroupTrigger privacyCustomTrigger uncheckPublic privacySee uncheckHidden privacyGroupTrigger_<?=$groupData['id'];?>_see" <?=$checked['editGroup'];?> <?=$disabled;?>>
																		show
                                                                	</div>      
                                                                	<div>
                                                                		<input type="checkbox" name="privacyCustomEdit[]" value="<?=$groupData['id'];?>" data-groupid="<?=$groupData['id'];?>" data-privacytype="edit"  class="privacyGroupTrigger privacyCustomTrigger uncheckPublic checkPrev uncheckHidden privacyGroupTrigger_<?=$groupData['id'];?>_edit" <?=$checked['editGroup'];?> <?=$disabled;?>>
																		edit
                                                                	</div>
                                                                </li>
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
        				</ul>
        			</li>
        			<li style="height:1px;"></li>
        			<?php
        			}
        			if(1 == 2){
        			if($protected){
        				echo"<li style=\"font-size:16pt;\">Protected</li>";
        			}
        			?>
        			<li><input type="checkbox" name="privacyPublic" value="true" class="privacyPublicTrigger uncheckCustom uncheckHidden" <?=$checked[privacyPublic];?> <?=$disabled;?>> Public</li>
					<li>Custom:</li>
					<li style="padding-left:5px;">See</li>
					<li style="padding-left: 10px;"><input type="checkbox" name="privacyCustomSee[]" value="f" class="privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked[privacyCustomShowF];?> <?=$disabled;?>> All your Friends</li>

                                                        <?                            
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='$_SESSION[userid]' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData[title];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            
                                                            if(in_array($groupData['id'], $customShow)){
                                                                $checked['showGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['showGroup'] = '';
                                                            }
                                                            ?>
                                                            <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomSee[]" value="<?=$groupData[id];?>" class="privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked[showGroup];?> <?=$disabled;?>><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="createNewTab('reader_tabView','<?=$title10;?>','','group.php?id=<?=$groupData[id];?>',true);return false"><?=$title15;?></a></li>
       
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
                     <li style="padding-left:5px;">Edit</li>
                     <li style="padding-left:5px;"><input type="checkbox" name="privacyCustomEdit[]" value="f" class="privacyCustomTrigger uncheckPublic uncheckOnlyMe uncheckHidden"<?=$checked[privacyCustomEditF];?> <?=$disabled;?>>All your Friends</li>

                                                        <?                            
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='$_SESSION[userid]' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData['title'];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            if(in_array($groupData['id'], $customEdit)){
                                                                $checked['editGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['editGroup'] = '';
                                                            }
                                                            ?>
                                                                <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomEdit[]" value="<?=$groupData[id];?>" class="privacyCustomTrigger uncheckOnlyMe uncheckPublic uncheckHidden" <?=$checked[editGroup];?> <?=$disabled;?>><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="createNewTab('reader_tabView','<?=$title10;?>','','group.php?id=<?=$groupData[id];?>',true);return false"><?=$title15;?></a></li>
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
                                                        <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomEdit[]" value="h" class="privacyCustomTrigger privacyOnlyMeTrigger uncheckPublic uncheckHidden" <?=$checked[privacyCustomEditH];?> <?=$disabled;?>>Only Me</li>
	
                                        <li><input type="checkbox" class="privacyHiddenTrigger uncheckPublic uncheckCustom" name="privacyHidden" value="true" <?=$checked[privacyHidden];?> <?=$disabled;?>>Hidden:</li>
					<?php }?>
        		</ul>
        	</div>
                                <script>
									initPrivacy();
                                </script>
       <?php
       }
    }
    
    function exploitPrivacy($public, $hidden, $customEdit, $customShow){
    	if(!empty($public) OR !empty($hidden) OR !empty($customEdit) OR !empty($customShow)){
	        if($public == "true"){
	            $privacy = "p";
	        }
	        else if($hidden == "true"){
	            $privacy = "h";
	        }else{
	            
	            $customEdit = implode(";", $customEdit);
	            $customShow = implode(";", $customShow);
	            if(empty($customShow)){
	            	$customShow = "h";
	            }
	            if(empty($customEdit)){
	            	$customEdit = "h";
	            }
	            if(empty($customEdit) OR empty($customShow)){
	            	$privacy = "p";
	           	}else{
	            $privacy = "$customEdit//$customShow";
				}
	        }
	        
	        return $privacy;
		}
    }
    
    
    
    function jPlayerFormat($title, $fileId, $type){
        $path = getFilePath($fileId);
        $path = "$path/$title";
        echo "{";
        echo "title: \"$title\",";
        echo "$type: \"./upload/$path\"";
        echo "}";
    }
	
	function getPlaylists($userid=NULL){
		if(empty($userid))
			$userid = getUser();
		
		$sql = mysql_query("SELECT `id` FROM playlist WHERE `user`='".mysql_real_escape_string($userid)."'");
		while($data = mysql_fetch_array($sql)){
			$playlists[] = $data[id];
		}
		
		return $playlists;
	}
	
	function getPlaylistTitle($playlistId){
		$sql = mysql_query("SELECT `title` FROM playlist WHERE id='".mysql_real_escape_string($playlistId)."'");
		$data = mysql_fetch_array($sql);
		return $data['title'];
		
		
		
		
	}
	
	function getUserPlaylistArray($userId=null, $type='show'){
		if($userId == null){
			$userId = getUser();
		}
		
		//get all the groups in which the current user is
        $userGroupsSql = mysql_query("SELECT `group` FROM `groupAttachments` WHERE item='user' AND itemId='$userId'");
        while($userGroupsData = mysql_fetch_array($userGroupsSql)){
            $userGroups[] = "$userGroupsData[group]";
        }
        
        //add them to the query
        foreach($userGroups AS &$userGroup){
                $query = "$query OR (INSTR(`privacy`, '{$userGroup}') > 0)";
        }
		
		
			//get playlists from friends
				$buddies = buddyListArray();
                $buddies = join(',',$buddies);
                $query .= "OR (INSTR(`user`, '{$buddies}') > 0)";
        
            //get playlists for user and groups
            $playListsSql = mysql_query("SELECT id, title, privacy, user FROM playlist WHERE user='".getUser()."' $query");
            while($playListsData = mysql_fetch_array($playListsSql)){
                if(authorize($playListsData['privacy'], $type, $playListsData['user'])){
	                $ids[] = $playListsData['id'];
	                $titles[] = $playListsData['title'];
				}
            }
			
			$return['ids'] = $ids;
			$return['titles'] = $titles;
			
			return $return;
        
		
	}
	
	function showPlaylist($id, $query=NULL){
		if(!empty($id))
			$query = "id='$id'";
		
        $playListSql = mysql_query("SELECT * FROM playlist WHERE $query");
        $playListData = mysql_fetch_array($playListSql);
		?>
		<table cellspacing="0" width="100%">                
                            <?
                            $i = 0;
                            $query = commaToOr("$playListData[folders]", "id");
                            $playListFolderSql = mysql_query("SELECT * FROM folders WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation(folder, $playListFolderData['id'])){;
                            ?>
                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/folder.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData['name']?></td>
                                    </tr>

                            <? 
                            $i++;

                            }}
                            $query = commaToOr($playListData['elements'], "id");
                            $playListFolderSql = mysql_query("SELECT id, title FROM elements WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){   
                            ?>

                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;e_<?=$playListFolderData['title']?></td>
                                    </tr>
                        <? 
                            $i++;

                            }}
                            $query = commaToOr("$playListData[files]", "id");    
                            $playListFolderSql = mysql_query("SELECT * FROM files WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=file&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListfileNo<?=$playListFolderData['id'];?>">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData['title']?></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++; }}
                            $query = commaToOr($playListData['links'], "id");
                            $playListFolderSql = mysql_query("SELECT * FROM links WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){    
                                if($playListLinkData['type'] == "youTube"){

                                }
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=link&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }

                            ?>

                                    <tr class="strippedRow playListlinkNo<?=$playListFolderData[id];?>">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')"><?=$playListFolderData[title]?></a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                        <?
                            $i++; }}
                            $videos = explode(";", $playListData['youTube'], -1);
                            foreach($videos as &$vId){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=youTube&itemId=$vId\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListyouTubeNo<?=$vId;?> tooltipper" onmouseover="mousePop('youTube', '<?=$vId;?>', '');" onmouseout="$('.mousePop').hide();">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')">Youtube Video</a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++;

                            }?>
                                </table>
		<?
	}
	
	
	function showPlaylistOld($playListId){
        $playListSql = mysql_query("SELECT * FROM playlist WHERE id='$playListId'");
        $playListData = mysql_fetch_array($playListSql);
        
        $query = commaToOr("$playListData[files]", "id");
        $playListFileSql = mysql_query("SELECT title, id FROM files WHERE $query");
        $num = mysql_num_rows($playListFileSql);
        while($playListFileData = mysql_fetch_array($playListFileSql)){
            jPlayerFormat($playListFileData['title'], $playListFileData['id'], "mp3");
        if($i < $num){
            echo",";
        }
        $i++;
        }
    }
    function universeText($str){
        
        $str = str_replace(":'(", '<a class="smiley smiley1"></a>', $str);//crying smilye /&#039; = '
        $str = str_replace(':|', '<a class="smiley smiley2"></a>', $str);
        $str = str_replace(';)', '<a class="smiley smiley3"></a>', $str);
        $str = str_replace(':P', '<a class="smiley smiley4"></a>', $str);
        $str = str_replace(':-D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':)', '<a class="smiley smiley6"></a>', $str);
        $str = str_replace(':(', '<a class="smiley smiley7"></a>', $str);
        $str = str_replace(':-*', '<a class="smiley smiley8"></a>', $str);
		$str = preg_replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4",$str);
        # Links
        $str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);

      

        return $str;
    }function youTubeURLs($text) {
        $text = preg_replace('~
            # Match non-linked youtube URL in the wild. (Rev:20111012)
            https?://         # Required scheme. Either http or https.
            (?:[0-9A-Z-]+\.)? # Optional subdomain.
            (?:               # Group host alternatives.
              youtu\.be/      # Either youtu.be,
            | youtube\.com    # or youtube.com followed by
              \S*             # Allow anything up to VIDEO_ID,
              [^\w\-\s]       # but char before ID is non-ID char.
            )                 # End host alternatives.
            ([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
            (?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
            (?!               # Assert URL is not pre-linked.
              [?=&+%\w]*      # Allow URL (query) remainder.
              (?:             # Group pre-linked alternatives.
                [\'"][^<>]*>  # Either inside a start tag,
              | </a>          # or inside <a> element text contents.
              )               # End recognized pre-linked alts.
            )                 # End negative lookahead assertion.
            [?=&+%\w-]*        # Consume any URL (query) remainder.
            ~ix', 
            '$1',
            $text);
        return $text;
    }
    function youTubeIdToTitle($id){
        //takes a Youtube video id and returns the title
        $video_id = $id;
        $video_info = simplexml_load_file('http://gdata.youtube.com/feeds/api/videos/'.$video_id.'?v=1');
        $title = $video_info->title;
        return $title; // title
    }
	
	
	
    
    function createElement($folder, $title, $type, $user, $privacy){
    	$title = mysql_real_escape_string($title);
        $time = time();
        mysql_query("INSERT INTO `elements` (`title`, `folder`, `type`, `author`, `timestamp`, `privacy`) VALUES ('$title', '$folder', '$type', '$user', '$time', '$privacy');");
        return mysql_insert_id();
    }
    
    function deleteElement($elementId){
         
            $checkElementSql = mysql_query("SELECT privacy, folder, author FROM elements WHERE id='$elementId'");
            $checkElementData = mysql_fetch_array($checkElementSql);
			
			//deleting all  atached items
			//this should be after the deletion of the mysql row of the file
			//but the functions need fileinformations
			$files = getFilesInElement($elementId);
			$links = getLinksInElement($elementId);
			
                    
                    if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){

					//delete all Files
					foreach($files AS &$fileId){
						deleteFile($fileId);
					}
					
					//delete all links
					foreach($links AS &$linkId){
						deleteLink($linkId);
					}
					//delete all comments
		            deleteComments("element", $elementId);
					//delete all feeds
		            deleteFeeds("element", $elementId);
                                //delete all shortcuts
                    deleteInternLinks("element", $elementId);


                        if(mysql_query("DELETE FROM elements WHERE id='$elementId'")){


                            return true;
                        }else{
                            return false;
                        }


                    }
               
    }
	
	function getFilesInElement($elementId){
		$fileSQL = mysql_query("SELECT id FROM files WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($fileData = mysql_fetch_array($fileSQL)){
			$return[] = $fileData[id];
		}
		
		return $return;
	}
	
	function getFiles($element){
		
		$fileSQL = mysql_query("SELECT id FROM files WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($fileData = mysql_fetch_array($fileSQL)){
			//identifier to distinguish the output between files and links
			$fileData['returnType'] = 'file';
			$return[] = $fileData;
		}
		
		$linkSQL = mysql_query("SELECT id FROM links WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($linkData = mysql_fetch_array($linkSQL)){
			//identifier to distinguish the output between files and links
			$fileData['returnType'] = 'link';
			$return[] = $linkData;
		}
		
		return $return;
	}
	
	function getLinksInElement($elementId){
		$linkSQL = mysql_query("SELECT id FROM links WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($linkData = mysql_fetch_array($linkSQL)){
			$return[] = $linkData[id];
		}
		
		return $return;
	}
	
	function getElementName($elementId){
            $checkElementSql = mysql_query("SELECT title FROM elements WHERE id='".mysql_real_escape_string($elementId)."'");
            $checkElementData = mysql_fetch_array($checkElementSql);
			
			return $checkElementData['title'];
	}
	
    function getFilePath($fileId){
        $documentSQL = mysql_query("SELECT id, folder, filename FROM files WHERE id='$fileId'");
        $documentData = mysql_fetch_array($documentSQL);
            $documentElementSQL = mysql_query("SELECT id, title, folder FROM elements WHERE id='$documentData[folder]'");
            $documentElementData = mysql_fetch_array($documentElementSQL);
			
			$path = getFolderPath($documentElementData['folder']);
			$path .= $documentData['filename'];
			
            return $path;
    }
	
	function getFolderPath($folderId){
		
            $path = "upload/";
            $folderArray = loadFolderArray("path", $folderId);
            $folderArray = array_reverse($folderArray['names'], true);
            foreach($folderArray as &$folder){
                $folder = urldecode($folder);
                $path .= "$folder/";
            }
            
            return $path;
	}
    
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
	 
	 function validateTempFile($fileId, $privacy){
	 	$path = getFilePath($fileId);
		$oldpath = $path.'.temp';
		if(rename($oldpath, $path)){
		 	if(mysql_query("UPDATE `files` SET `temp`='0', `status`='1', `privacy`='$privacy' WHERE id='".save($fileId)."'")){
		 		return true;
		 	}else{
		 		return false;
		 	}
		}
	 }
	 
	 function tidyTempFiles(){
	 	mysql_query("DELETE FROM `files` WHERE temp='true' AND timestamp<'".(time()-86400)."'");
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

	function getFileData($fileId){
		$fileData = mysql_fetch_array(mysql_query("SELECT * FROM files WHERE id='".save($fileId)."'"));
		return $fileData;
	}
    
	function fileIdToFileTitle($fileId){
		$fileData = getFileData($fileId);
		return $fileData['title'];
	}
    
    
	function elementIdToElementTitle($elementId){
		$elementData = getElementData($elementId);
		return $elementData['title'];
	}
	
	function folderIdToFolderTitle($folderId){
		$folderData = getFolderData($folderId);
		return $folderData['name'];
		
	}
	
	function fileIdToFileType($fileId){
        $fileData = mysql_fetch_array(mysql_query("SELECT type FROM files WHERE id='".save($fileId)."'"));
        return $fileData['type'];
    }
    
    function linkIdToFileType($fileId){
        
        $fileData = mysql_fetch_array(mysql_query("SELECT type FROM links WHERE id='".save($fileId)."'"));
        return $fileData['type'];
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

//links
//links
//links

	function addLink($folder, $title, $type, $privacy, $link){
             
             
    
                $user = getUser();

                $time = time();
                if(mysql_query("INSERT INTO `links` (`folder`, `type`, `title`, `link`, `privacy`, `author`, `timestamp`) VALUES ( '".save($folder)."', '".save($type)."', '".save($title)."', '".save($link)."', '$privacy', '$user', '$time');")){
                	
                	$feedText = "has created the link $title in the folder";
                    $feedLink1 = mysql_insert_id();
                    $feedLink2 = $folder;
					
                    addFeed($user, $feedText, folderAdd, $feedLink1, $feedLink2);
					
					return true;
                }
	}
    
    function deleteLink($linkId){
        
                $linkSql = mysql_query("SELECT * FROM links WHERE id='$linkId'");
                $linkData = mysql_fetch_array($linkSql);
                    
                //file can only be deleted if uploader = deleter
                if(authorize($linkData['privacy'], "edit", $linkData['author'])){
                    
                       if(mysql_query("DELETE FROM links WHERE id='$linkId'")){
                           
                           //delete comments
                           deleteComments("link", $linkId);
                           deleteFeeds("link", $linkId);
                           deleteInternLinks("link", $linkId);
                           
                           
                           
                               return true;
                       }else{
                               return false;
                       }
                }else{
                    return false;
                }
    }
	
	
//shortcuts
//shortcuts
//shortcuts
	
	function createInternLink($parentType, $parentId, $type, $typeId, $title=NULL){
	//creates shortcut
		
		//check if link allready exists
		$linkCheckData = mysql_fetch_array(mysql_query("SELECT type FROM internLinks WHERE type='$type' AND typeId='$typeId'"));
		if(empty($linkCheckData[type])){
			
			if(mysql_query("INSERT INTO `internLinks` (`id`, `parentType`, `parentId`, `type`, `typeId`, `title`) VALUES (NULL, '$parentType', '$parentId', '$type', '$typeId', '$title');")){
				return true;
			}
			
		}
	}
        
    function deleteInternLink($linkId){
    	//deletes single shortcut
        if(mysql_query("DELETE FROM internLinks WHERE id='$linkId'")){
            return true;
        }
    }
    
    function deleteInternLinks($parentType, $parentId){
        //is used when element which the shortcut is linked to is deleted
        if(mysql_query("DELETE FROM internLinks WHERE type='$parentType' AND typeId='$parentId'")){
            return true;
        }
    }
    
    
    
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
    
    
    function loadFolderArray($type, $folder){
        switch($type){
            
            //loads all subordinated folders of $folder
            
            case 'children':
                
                $folderSQL = mysql_query("SELECT id FROM folders WHERE folder='".mysql_real_escape_string($folder)."'");
                while($folderData = mysql_fetch_array($folderSQL)){
                    $return[] = $folderData['id'];
                }
                break;
                
            case 'path':
                
                //maximum while 
                $maxqueries = 150;
                $i = 0;
                
               	while($folder != "0" || $folder != "0"){
               		if(!empty($folder) && $i < $maxqueries){
		                $folderSQL = mysql_query("SELECT name, folder FROM folders WHERE id='$folder'");
		                $folderData = mysql_fetch_array($folderSQL);
		
		                
		                $folder = $folderData['folder'];
		                if($folder!=0){
		                $returnFolder[] = $folder;
		                $returnName[] = $folderData['name'];
		                }
		                
		                    
		                    $i++;
					}
                }
                
                $return['ids'] = $returnFolder;
                $return['names'] = $returnName;
                break;
        }
        
        return $return;
    }
    
    function showFolderPath($folder, $class=NULL){
        
        $folders = loadFolderArray("path", $folder);
        
        $return .= "<ul>";
        $i = 0;
        $folderNames = $folders['names'];
        foreach($folders['ids'] AS &$folder){
            $return .= "<li>$folderNames[$i]</li>";
            $i++;
        }
        
        $return .=  "</li>";
        
        return $return;
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
    
	
	function getElementData($elementId){
		$query = mysql_query("SELECT * FROM `elements` WHERE id='".save($elementId)."'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
	
	function getFolderData($folderId){
		$query = mysql_query("SELECT * FROM `folders` WHERE id='".save($folderId)."'");
		$data = mysql_fetch_array($query);
		return $data;
	}
	
    function showFileList($element=NULL, $fileQuery=NULL, $git=NULL, $subpath=NULL){
        //shows list of files which are in the element $element or which meets criteria of $fileQuery
        //if git=1 => only basic information without itemsettings etc.
        if(empty($element)){
            $query = $fileQuery;
        }else{
            $query = "folder='".mysql_real_escape_string($element)."'";
            $shortCutQuery = "WHERE parentType='element' AND parentId='$element'";
        }
        
        	$i = 0;
            $fileListSQL = mysql_query("SELECT * FROM files WHERE $query");
            while($fileListData = mysql_fetch_array($fileListSQL)) {
                $i++;
                if(authorize($fileListData['privacy'], "show", $fileListData['owner'])){
                $title10 = substr("$fileListData[title]", 0, 10);
                $link = "openFile('$fileListData[type]', '$fileListData[id]', '$title10');";
                if($fileListData['type'] == "audio/mpeg"){
                    $rightLink = "startPlayer('file', '$fileListData[id]')";
                    $image = "../music.png";
                }
                else if($fileListData['type'] == "video/mp4"){
                    //define link for openFileFunction
                    $openFileType = "video";
                    
                    //define openFile function
                    $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                    $rightLink = "createNewTab('reader_tabView','See $title10','','./modules/reader/player.php?id=$fileListData[id]',true);return false";
                }
                else if($fileListData['type'] == "UFF"){
                //standard from know on (19.02.2013)
                    
                    //define link for openFileFunction
                    $openFileType = "UFF";
                    
                    //define openFile function
                    $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                }
                else if($fileListData['type'] == "text/plain" OR $fileListData['type'] == "application/pdf" OR $fileListData['type'] == "text/x-c++"){
                //standard from know on (19.02.2013)
                    
                    //define link for openFileFunction
                    $openFileType = "document";
                    
                    //define openFile function
                    $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                }
                else if($fileListData['type'] == "image/jpeg" OR $fileListData['type'] == "image/png" OR $fileListData['type'] == "image/gif"){
                //if a image is opened the tab is not named after the file
                //it is named after the parent element, because images are
                //shown in a gallery with all the images listed in the parent
                //element
                $elementData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='$fileListData[folder]'"));
                $elementTitle10 = substr("$elementData[title]", 0,10);
                
                	
                    
                    //define link for openFileFunction
                    $openFileType = "image";
                    
                    //define openFile function
                    $link = "openFile('$openFileType', '$fileListData[id]', '$elementTitle10');";
                }
                $image = getFileIcon($fileListData['type']);
                    ?>
                    <tr class="strippedRow file_<?=$fileListData[id];?>" oncontextmenu="showMenu('file<?=$fileListData['id'];?>'); return false;" height="40px">
                        <td width="30px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="22"></td>
                        <td><a href="<?=$subpath;?>out/?file=<?=$fileListData[id];?>" onclick="<?=$link;?> return false"><?=substr($fileListData[title],0,30);?></a></td>
                        <td width="80" align="right">
                        		<? if($fileListData['download']){ ?><a href="./out/download/?fileId=<?=$fileListData['id'];?>" target="submitter" class="btn btn-mini" title="download file"><i class="icon-download"></i></a><? } ?>
                                <? if(!$git){echo showItemSettings('file', "$fileListData[id]");}?></td>
                        <td width="50"><?=showScore(file, $fileListData['id']);?></td>
                    </tr>
                    <?php
                    if(!$git){
                    echo showRightClickMenu("file", $fileListData['id'], $title10, $openFileType);
                    }

            }}
            $linkListSQL = mysql_query("SELECT * FROM links WHERE $query");
            while($linkListData = mysql_fetch_array($linkListSQL)) {
                $title10 = substr($linkListData['title'], 0, 10);
                
                $link = "$link&id=$linkListData[id]";
                if($linkListData['type'] == "youTube"){
                    $vId = youTubeURLs($linkListData[link]);
                    $link = "openFile('youTube', '$linkListData[id]', '$title10', '$vId');";
                }
                
                if($linkListData['type'] == "audio/mp3"){
                    $rightLink = "startPlayer('file', '$fileListData[id]')";
                }
                
                if($linkListData['type'] == "RSS"){
                    $link = "openFile('RSS', '$linkListData[id]', '$title10');";
                }
                $image = getFileIcon($linkListData['type']);
                
                
                    $i++;
                ?>
                <tr class="strippedRow link_<?=$linkListData['id'];?>" oncontextmenu="showMenu('link<?=$linkListData['id'];?>'); return false;" height="40px">
                    <td width="65px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$linkListData['type'];?>" height="22px"></td>
                    <td><a href="#" onclick="<?=$link;?>"><?=substr($linkListData['title'],0,30);?></a></td>
                    <td width="70" align="right">
                    	<?php
                    	if(!$git){
                    		showItemSettings('link', $linkListData['id']);
                    	}
                    	?>
                    	</td>
                    <td><?=showScore(link, $linkListData['id']);?></td>
                </tr>
                <?php
                    if(!$git){
                echo showRightClickMenu("link", $linkListData['id'], $title10, $linkListData['type']);
					}
                }
                
                
                
                $shortCutSql = mysql_query("SELECT * FROM internLinks $shortCutQuery");
                while($shortCutData = mysql_fetch_array($shortCutSql)){
                    $i++;
                    if($shortCutData['type'] == "file"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type FROM files WHERE id='$shortCutData[typeId]'"));
                        $title10 = substr($shortCutItemData['title'], 0,10);
                        $title = $shortCutItemData['title'];
                        if($shortCutItemData['type'] == "UFF"){
                        //standard from know on (19.02.2013)

                            //define link for openFileFunction
                            $openFileType = "UFF";

                            //define openFile function
                            $link = "openFile('$openFileType', '$shortCutItemData[typeId]', '$title10');";
                        }
                        else if($shortCutItemData['type'] == "text/plain" OR $shortCutItemData['type'] == "application/pdf"){
                        //standard from know on (19.02.2013)

                            //define link for openFileFunction
                            $openFileType = "document";

                            //define openFile function
                            $link = "openFile('$openFileType', '$shortCutItemData[typeId]', '$title10');";
                        }
                        
                        
                        
                        
                        

                    }else if($shortCutData['type'] == "link"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type FROM links WHERE id='$shortCutData[typeId]'"));
                        $title10 = substr($shortCutItemData['title'], 0,10);
                        $title = $shortCutItemData['title'];
                        if($shortCutItemData['type'] == "youTube"){
                            $vId = youTubeURLs($shortCutItemData[link]);
                            $link = "openFile('youTube', '$vId', '$title10');";
                        }

                        if($shortCutItemData['type'] == "RSS"){
                            $link = "openFile('RSS', '$shortCutData[typeId]', '$title10');";
                        }
                    }
                    
                    
                    $image = getFileIcon($shortCutItemData['type']);

                    echo'<tr>';
                        echo'<td>';
                            echo"&nbsp;<img src=\"$subpath"."gfx/icons/fileIcons/$image\" height=\"22\"><i class=\"shortcutMark\"> </i>";
                        echo"</td>";
                        echo'<td colspan="3">';
                            echo"<a href=\"./out/?$shortCutData[type]=$shortCutData[typeId]\" onclick=\"$link return false\">$title</a>";
                        echo"</td>";
                    echo'</tr>';
                }
					if($i == 0){
						
	                    echo'<tr class="strippedRow" style="height: 20px;">';
	                        echo'<td colspan="3">';
	                            echo'This Element is empty.';
	                        echo'</td>';
	                    echo'</tr>';
					}

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
	
	
		
		function sendMessage($receiver, $text, $crypted, $sender=NULL){
			
	        if($sender == NULL)
	        	$sender = getUser();
			
			updateActivity($sender);
	     	$buddy = $receiver;
	        if($crypted == "true"){
	            $crypt = "1";
	        }else{
	            $crypt = "0";
	        }
			
			$message = addslashes($text);
	        if(mysql_query("INSERT INTO `messages` (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('$sender', '$buddy', '".time()."', '$text', '0', '$crypt');")){
	        	return mysql_insert_id();
	        }
	        $postCheck = 1;
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
	    	$content .= "<li onclick=\"javascript: showModuleSettings();\" onmouseup=\"closeDockMenu()\"><img src=\"./gfx/settings.png\" border=\"0\" height=\"16\">Settings</li>";
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

class users{
	var $userid;
   	function __construct($userid) {
       $this->userid = $userid;
	}
	function getData($query='*'){
		$data = mysql_fetch_array(mysql_query("SELECT $query FROM `user` WHERE `userid`='".save($this->userid)."'"));
		return $data;
	}
	public function updateData($values){
		foreach($values as $param=>$value) {
		    
			$query .= ", `".save($param)."`='".save($value)."' "; //will return "   ,`param`='value'   "
			
			
		}
	}
	public function updateBirthdate(){
		
	}
	public function updateUserpicture(){
		
	}
	public function showUserpicture(){
		
	}
	
}

class groups{
	public function get($userid=NULL){
		if(empty($userid))
			$userid = getUser();
		
		$sql = mysql_query("SELECT `group` FROM `groupAttachments` WHERE `item`='user' AND `validated`='1' AND `itemId`='".mysql_real_escape_string($userid)."'");
		while($data = mysql_fetch_array($sql)){
			$groups[] = $data['group'];
		}
		return $groups;
	}
	public function getTitle($groupId){
		$data = mysql_fetch_array(mysql_query("SELECT `title` FROM `groups` WHERE id='".save($groupId)."'"));
		return $data['title'];
	}
}

class db{
	public function insert($table, $options){
					
			//generate update query	
			foreach($options AS $row=>$value){
				$query[] = "`".save($row)."`";
				$values[] = "'".save($value)."'";
			}
			
			
			$query = "(".implode(',', $query).")";
			$values = "(".implode(',', $values).");";
			
			
		mysql_query("INSERT INTO `$table` $query VALUES $values");
		
	}
	public function update($table, $options, $primary){
					
			//generate update query	
			foreach($options AS $row=>$value){
				
				//only add row to query if value is not empty
				if(!empty($value)){
					$query[] = " $row='".save($value)."'";
				}
			}
			$query = implode(',', $query);
			
			
		mysql_query("UPDATE `$table` SET $query WHERE $primary[0]='".save($primary[1])."'");
		
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
	
	public function create($user, $startStamp, $stopStamp, $title, $place, $privacy){
		mysql_query("INSERT INTO `events` (`user`, `startStamp`, `stopStamp`, `title`, `place`, `privacy`) VALUES ('$user', '$startStamp', '$stopStamp', '$title', '$place', '$privacy');");
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