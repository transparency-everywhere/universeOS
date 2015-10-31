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
class user {
    var $userid;
    function __construct($userid=NULL) {
        $this->userid = $userid;
    }
    public function exists($selector){
        
        if($selector == NULL){
            $userid = $this->userid;
        }else{
            if(is_numeric($selector))
                $userid = $selector;
            else
                $userid = $this->usernameToUserid($selector);
        }
  	if(empty($userid))
  		$userid = getUser();
        
        $db = new db();
        $data = $db->query('user', array('userid', $userid), array('userid'));
        return isset($data['userid']);
    }
    public function login($username, $password){
  
        $username = escape::sql($username);
        $db = new db();
        $data = $db->select('user', array('username', $username), array('userid', 'password', 'hash', 'cypher'));
        $userid = $data['userid'];

        $timestamp = time();
        $timestamp = ($timestamp / 2);
        $hash = md5($timestamp);
 
        //old version
        if($data['cypher'] == 'md5'){
            $password = md5($password);
        }
 
        if(!empty($userid) && $password == $data['password']){
            //regenerate session_id
            session_regenerate_id();
            
            //set cookies
            $_SESSION['guest'] = false;
            $_SESSION['userid'] = $data['userid'];
            $_SESSION['userhash'] = $hash;

            //update db
            $values['hash'] = $hash;
            $db->update('user', $values, array('userid', $userid));
            
            $userClass = new user();
            $userClass->updateActivity($_SESSION['userid']);


            return 1;
        }else{
            return 0;
        }
        
    }
    public function usernameToUserid($username){
        $db = new db();
        $userData = $db->select('user', array('username', $username), array('userid', 'username'));
        return $userData['userid'];
    }
    public function updateProfileInfo($realname, $city, $hometown, $birthdate, $school, $university, $work, $selector=NULL){
    
        if($selector == NULL){
            $userid = $this->userid;
        }else{
            if(is_numeric($selector))
                $userid = $selector;
            else
                $userid = $this->usernameToUserid($selector);
        }
        
  	if(empty($userid)){
  		$userid = getUser();
  	}
        $values['realname'] = sanitizeText($realname);
        $values['place'] = sanitizeText($city);
        $values['home'] = sanitizeText($hometown);
        $values['birthdate'] = sanitizeText($birthdate);
        $values['school1'] = sanitizeText($school);
        $values['university1'] = sanitizeText($university);
        $values['employer'] = sanitizeText($work);
        $db = new db();
        $db->update('user', $values, array('userid', $userid));
    }
    public function getProfileInfo($selector=NULL){
        if($selector == NULL){
            $userid = $this->userid;
        }else{
            if(is_numeric($selector))
                $userid = $selector;
            else
                $userid = $this->usernameToUserid($selector);
        }
        
        
  	if(empty($userid)&&$userid!=0){
  		$userid = getUser();
  	}else if($userid==0){
            //returns anon userdata
                return array('userid'=>0,'username'=>'anonymous', 'realname'=>'Ano Nymous');
        }else{
                 //check privacy rights!
                 $privacy = new userPrivacy($userid);
                 if(!$privacy->proofRight('info')){
                     return array();
                 }
        }
        
	$db = new db();
	$data = $db->select('user', array('userid', $userid), array('userid','username','realname', 'birthdate', 'school1', 'university1', 'employer', 'place', 'home', 'homefolder', 'myFiles'));
        
        if(is_array($data)){
            return $data;
        }else
            return array();
    }
    public function getData($selector=NULL){
        if($selector == NULL){
            $userid = $this->userid;
        }else{
            if(is_numeric($selector))
                $userid = $selector;
            else
                $userid = $this->usernameToUserid($selector);
        }
        
        
  	if(empty($userid)){
  		$userid = getUser();
  	}
	$db = new db();
	$userData = $db->select('user', array('userid', $userid));
	return $userData;
        
    }
    public function updateActivity($userid){
          $time = time();
          $values['lastactivity'] = $time;
          $db = new db();
          $db->update('user', $values, array('userid', $userid));
    }
    public function updateUserPicture($fileArray){
        $userData = $this->getData();
        $fileClass = new files();
        $elementClass = new element();
        $elementData = $elementClass->getData($userData['profilepictureelement']);
        $fileId = $fileClass->addFile($fileArray, $userData['profilepictureelement'], $elementData['folder'], 'p', $this->userid, NULL, true, false);

        $file = new file($fileId);
        $fileData = $file->getFileData();
        
        $db = new db();
        $db->update('user', array('userPicture'=>$fileData['filename']), array('userid',getUser()));

        $feed = new feed();
        $feed->create($this->userid, 'has a new userpicture', 0, 'feed', 'f//f');
        
        echo '<script>';
        echo 'parent.settings.showUpdateProfileForm();parent.User.updateUserpicture('.getUser().');parent.gui.alert(\'Your userpicture has been changed. You will see the change after the next login.\');';
        echo '</script>';
        
    }
    public function updatePassword($oldPasswordHash, $newPasswordHash, $newAuthSalt, $userid=NULL){
        if($userid == NULL)
            $userid = $this->userid;
        
        
	$userClass = new user($userid);
	$userData = $userClass->getData($userid);
	if($userData['password'] == $oldPasswordHash){
		
                $saltClass = new salt();
		if(!empty($newAuthSalt)){
			//store salt
			$saltClass->update('auth', $userid, $newAuthSalt);
		}
		  
		$db = new db();
                $values['password'] = $newPasswordHash;
                $db->update('user', $values, array('userid', $userid));
                
  		return true;
	}else{
		return 'Old Password was wrong';
	}
        
    }
    public function getUserPictureBASE64($userid){
        
	$userData = $this->getData($userid);
	
	//check if user is standard user
	if(empty($userData['userPicture'])){
		$src = universeBasePath.'/gfx/standardusersm.png';
	}else{
		$src = universeBasePath.'/upload/userFiles/'.$userid.'/userPictures/thumb/300/'.$userData['userPicture'];
	}
	$mime = mime_content_type($src);
        $file = fopen($src, 'r');
        $output = base64_encode(fread($file, filesize($src)));
	return 'data:'.$mime.';base64,'.$output;
    }
    
    
    public function getLastActivity($userid){
        $db = new db();
        $data = $db->select('user', array('userid', $userid), array('lastactivity'));
        
	$diff = time() - $data['lastactivity'];
	if($diff < 90){
		return 1;
	}
        return 0;
    }
    
    public function getFav($userid=NULL){
        $return;
        if(empty($userid)){
                $userid=$this->userid;
        }
        $db = new db();
        $favs = $db->shiftResult($db->select('favs', array('user', $userid)),'user');
        foreach($favs AS $favData){
                $return[] = $favData;
        }

        return $return;
    }

    function getUserFavOutput($user){
		if(empty($user)){
			$user = $_SESSION['userid'];
		}
		
		
    }
    
    public function getUsername(){
        $userData = $this->getData();
        return $userData['username'];
    }
  
    public function create($username, $password, $authSalt, $keySalt, $privateKey, $publicKey){

        $username = sanitizeText($username);
        $username = $username;
        $db = new db();
        $data = $db->select('user', array('username', $username), array('username'));

        if(empty($data['username'])||$data['username'] == 't'){
            $time = time();
            
            $values['password'] = $password;
            $values['cypher'] = 'sha512_2';
            $values['username'] = $username;
            $values['email'] = ''; //could be usefull for businesses
            $values['regdate'] = $time;
            $values['usergroup'] = 0;
            $values['lastactivity'] = $time;
            
            $db = new db();
            $userid = $db->insert('user', $values);
            
            
            //create record in user_privacy_rights  
            $privacyValues['userid'] = $userid;
            $privacyValues['profile_realname'] = 'p';
            $privacyValues['profile_fav'] = 'p';
            $privacyValues['profile_files'] = 'p';
            $privacyValues['profile_playlists'] = 'p';
            $privacyValues['profile_activity'] = 'p';
            $privacyValues['receive_messages'] = 'p';
            $privacyValues['buddylist'] = 'p';
            $privacyValues['info'] = 'p';
            $privacyValues['groups'] = 'p';
            $db->insert('user_privacy_rights', $privacyValues);

                    //store salts
                    $saltClass = new salt();
                    $saltClass->create('auth', $userid, 'user', $userid, $authSalt);
                    $saltClass->create('privateKey', $userid, 'user', $userid, $keySalt);

                    //create signature
                    $sig = new signatures();
                    $sig->create('user', $userid, $privateKey, $publicKey);

            //create user folder(name=userid) in folder userFiles
            $folderClass = new folder();
            $userFolder = $folderClass->create("2", $userid, $userid, "h");

            //create folder for userpics in user folder
            $pictureFolder = $folderClass->create($userFolder, "userPictures", $userid, "h");

            //create thumb folders || NOT LISTED IN DB!
            $path3 = universeBasePath."//upload//userFiles//$userid//userPictures//thumb";
            $path4 = universeBasePath."//upload//userFiles//$userid//userPictures//thumb//25";
            $path5 = universeBasePath."//upload//userFiles//$userid//userPictures//thumb//40";
            $path6 = universeBasePath."//upload//userFiles//$userid//userPictures//thumb//300";
            mkdir($path3,0755);  //Creates Thumbnail Folder
            mkdir($path4,0755); //Creates Thumbnail Folder
            mkdir($path5,0755); //Creates Thumbnail Folder
            mkdir($path6,0755); //Creates Thumbnail Folder


            //create Element "myFiles" in userFolder
            $element = new element();
            $myFiles = $element->create($userFolder, "myFiles", "myFiles", $userid, "h");

            //create Element "user pictures" to collect profile pictures
            $pictureElement = $element->create($pictureFolder, "profile pictures", "userPicture", $userid, "p");

            $updateValues['homefolder'] = $userFolder;
            $updateValues['myFiles'] = $myFiles;
            $updateValues['profilepictureelement'] = $pictureElement;
            
            $db->update('user', $updateValues, array('userid', $userid));
            
            return true;
        }
    }
    
    
    //not in use so far
    function deleteAccount($passwordHash){
          //get userData from session id
          $userData = $this->getData(getUser());
          if($passwordHash == $userData['password']){
              $db = new db();
              //delete all files
              $files = $db->select('files', array('owner', $userid), array('id'));
              foreach($files AS $fileData){
                  $fileClass = new file($fileData['id']);
                  $fileClass->delete();
              }

              //delete all links
              $linkClass = new link();
              $links = $db->query('links', array('author', $userid), array('id'));
              foreach($links AS $linkData){
                $linkClass->deleteLink($linkData['id']);
              }


              //elements
              $elements = $db->select('elements', array('author', $userid), array('id'));
              foreach($elements AS $elementData){
                  $element = new element($elementData['id']);
                  $element->delete();
              }


              //folders
              $folders = $db->select('folders', array('creator', $userid), array('id'));
              foreach($folders AS $folderData){
                  $classFolder = new folder($folderData['id']);
                  $classFolder->delete();
              }

              //comments


              //buddy
              $db->delete('buddylist', array('buddy', $userid, 'OR', 'owner', $userid));

              //delete user
              $db->delete('user', array('userid', $userid));


              return true;
          }
          return false;
    }
    
}


//@old, @sec
function updateUserPassword($oldPassword, $newPassword, $newAuthSalt=NULL, $newKeySalt=NULL, $privateKey=NULL, $userid=NULL){
  	if($userid == NULL){
  		$userid = getUser();
  	}
	
	$userClass = new user($userid);
	$userData = $userClass->getData($userid);
        
	if($userData['password'] == $oldPassword){
		
                $saltClass = new salt();
		if(!empty($newAuthSalt)){
			//store salt
			$saltClass->update('auth', $userid, $newAuthSalt);
		}
		if(!empty($newKeySalt)){
			//store salt
			$saltClass->update('privateKey', $userid, $newKeySalt);
		}
		  
		//create signature
		$sig = new signatures();
		$sig->updatePrivateKey('user', $userid, $privateKey); //store encrypted private key
		$db = new db();
                $values['password'] = $newPassword;
                $db->update('user', $values, array('user', $userid));
                
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
//@sec
//@del
function proofLoginMobile($user, $hash){
  	
        $userClass = new user($user);
	$userData = $userClass->getData($user);
	
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
	  //whis is defined in config.php
	  $db = new db();
          
	  $userData = $db->select('user', array('userid', getUser()), array('usergroup'));
          $userGroupData = $db->select('userGroups', array('id', $userData['usergroup']));
          
	  if($userGroupData[$type] == "1"){
	  	return true;
	  }else{
	  	return false;
	  }
}

function checkMobileAuthentification($username, $hash){
      
        $username = $username;
        $hash = $hash;
        
        $db = new db();
        
        $loginData = $db->select('user', array('username', $username), array('userid', 'username', 'password'));
        $dbPassword = $loginData['password'];
        $dbPassword = hash('sha1', $dbPassword);
        if($hash == $dbPassword){
            return true;
        }
  }

function usernameToUserid($username){
        debug::write('use of function usernameToUserid');
        return user::usernameToUserid($username);
  }

function useridToUsername($userid){
        $db = new db();
        
        $loginData = $db->select('user', array('userid', $userid), array('username'));
        return $loginData['username'];
  }
function useridToRealname($userid){
        $db = new db();
        $loginData = $db->select('user', array('userid', $userid), array('realname'));
        if(empty($loginData['realname']))
            return 'no realname';
        
        return $loginData['realname'];
  }

function userSignature($userid, $timestamp, $subpath = NULL, $reverse=NULL){
    $db = new db();
    $feedUserData =  $db->select('user', array('userid', $userid), array('userid', 'username', 'userPicture'));
    if(isset($subpath)){
        $path = "./../.";
        $subPath = 1;
    }else{
        $subPath = NULL;
    }
      ?>
    <div class="signature" style="background: #F9F9F9;">
    <table width="100%">
        <tr width="100%">
            <?php
            if(empty($reverse)){ ?>
            <td style="width:50px; padding-right:10px;"><?=showUserPicture($feedUserData['userid'], "40", $subpath);?></td>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 16px;line-height: 17px;" align="left"><a href="#" onclick="showProfile(<?=$feedUserData['userid'];?>);"><?=$feedUserData['username'];?></a></td>
                    </tr>             
                    <tr>
                        <td style="font-size: 12px;line-height: 23px;">
                            <i>
                            <?php
                            $guiClass = new gui();
                            echo $guiClass->universeTime($timestamp);?>
                            </i>
                        </td>
                    </tr>
                </table>
            </td>
            <?php }else{?>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 10pt;">&nbsp;<?=$feedUserData['username'];?></td>
                    </tr>             
                    <tr>
                        <td style="font-size: 08pt;">&nbsp;<i>
                        <?php
                        $guiClass = new gui();
                        $gui->universeTime($timestamp);?></i>
                        </td>
                    </tr>
                </table>
            </td>
            <td><span class="pictureInSignature"><?=showUserPicture($feedUserData['userid'], "40", $subpath);?></span></td>
            <?php } ?>
        </tr>
    </table>
    </div>
      <?php
  }
  

        
function showUserPicture($userid, $size, $subpath = NULL, $small = NULL /*defines if functions returns or echos and if script with bordercolor is loaded*/){
    
    $db = new db();
    $picData = $db->select('user', array('userid', $userid), array('userid', 'lastactivity', 'userPicture', 'priv_profilePicture'));
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
		$size.="px";
    
    if(empty($picData['userPicture'])){
        
        
    	$class = "standardUser";
    
    }else{
        
		$class = "";
		
        if($subpath !== "total"){
            
            $src = "$path./upload/userFiles/$userid/thumbs/".$picData['userPicture']."";
			if(empty($class)){
            	$style = "background-image: url('$src');";
			}
        }else{
            $src = "./upload/userFiles/$userid/userPictures/thumbs/".$picData['userPicture']."";
			if(empty($class)){
            	$style = "background-image: url('$src');";
			}
        }
        
    }
       

        if($subpath !== "total"){
            
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus('$userid', '$color');\" onclick=\"showProfile('$userid');\" style=\"width: $size; border-radius:".(int)($size/2)."px; height: $size; border-color: $color; $style\"></div>";
        }else{
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus('$userid', '$color');\" onclick=\"showProfile('$userid;');\" style=\"width: $size; border-radius:".(int)($size/2)."px; height: $size; $style border-color: $color; $style\"></div>";
        }
      
      
      
      
        if($small){
        	if(!empty($picData['userPicture'])){
        		$style = " background-image: url(\\'$src\\');";
				if($small == 'unescaped'){
        			$style = " background-image: url(\\'$src\\');";
				}
			}
			
            $return="<div class=\"userPicture userPicture_$userid $class\" onload=\"updatePictureStatus(\'jjj$userid\', \\'$color\\');\" onclick=\"showProfile(\\'$userid\\');\" style=\"$style width: $size; height: $size; border-radius:".(int)($size/2)."px;border-color: $color;\"></div>";

            return $return;
        }else{
            echo $return;
        }
}