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
    public function login($username, $password){
  
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
 
        if(!empty($userid) && $password == $data['password']){
            //set cookies
            $_SESSION['guest'] = false;
            $_SESSION['userid'] = $data['userid'];
            $_SESSION['userhash'] = $hash;

            //update db
            mysql_query("UPDATE user SET hash='$hash' WHERE userid='".$_SESSION['userid']."'");

            $feedClass = new feed();
            $feedClass->create($_SESSION['userid'], "is logged in", "60", "feed", "p");
            updateActivity($_SESSION['userid']);


            return 1;
        }else{
            return 0;
        }
        
    }
    public function usernameToUserid($username){
        $loginSQL = mysql_query("SELECT userid, username FROM user WHERE username='".save($username)."'");
        $loginData = mysql_fetch_array($loginSQL);
        return $loginData['userid'];
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
	
	$userData = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE userid='$userid'"));
	return $userData;
        
    }
}

function getUsername(){
    $userData = $this->getData();
    return $userData['username'];
}


function updateUserPassword($oldPassword, $newPassword, $newAuthSalt=NULL, $newKeySalt=NULL, $privateKey=NULL, $userid=NULL){
  	if($userid == NULL){
  		$userid = getUser();
  	}
	
	
	$userData = getUserData($userid);
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
	  //whis is defined in config.php
	  
	  $userData = mysql_fetch_array(mysql_query("SELECT `usergroup` FROM `user` WHERE `userid`='".$_SESSION['userid']."'"));
	  $userGroupData = mysql_fetch_array(mysql_query("SELECT * FROM `userGroups` WHERE `id`='".$userData['usergroup']."'"));
	  
	  if($userGroupData["$type"] == "1"){
	  	return true;
	  }else{
	  	return false;
	  }
  	
  }

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
        debug::write('use of function usernameToUserid');
        return user::usernameToUserid($username);
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
  
  
function updateActivity($userid){
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
                        <td style="font-size: 15pt;line-height: 23px;">
                            &nbsp;<i>
                            <?php
                            $guiClass = new gui();
                            $guiClass->universeTime($timestamp);?>
                            </i>
                        </td>
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
                        <td style="font-size: 08pt;">&nbsp;<i>
                        <?php
                        $guiClass = new gui();
                        $gui->universeTime($timestamp);?></i>
                        </td>
                    </tr>
                </table>
            </td>
            <?}?>
        </tr>
    </table>
    </div>
      <?php
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
        mkdir($path3);  //Creates Thumbnail Folder
        mkdir($path4); //Creates Thumbnail Folder
        mkdir($path5); //Creates Thumbnail Folder
        mkdir($path6); //Creates Thumbnail Folder
        
        
        //create Element "myFiles" in userFolder
        $element = new element();
        $myFiles = $element->create($userFolder, "myFiles", "myFiles", $userid, "h");
        
        //create Element "user pictures" to collect profile pictures
        $pictureElement = $element->create($pictureFolder, "profile pictures", "image", $userid, "p");


        mysql_query("UPDATE user SET homefolder='$userFolder', myFiles='$myFiles', profilepictureelement='$pictureElement' WHERE userid='$userid'");

        return true;
    }
      
  }


function getUserFavs($userid=NULL){
    $return;
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

        

function deleteUser($userid, $reason){
      $authorization = true;
      if($authorization){
          
          //delete all files
          $fileSQL = mysql_query("SELECT id FROM files WHERE owner='$userid'");
          while($fileData = mysql_fetch_array($fileSQL)){
              $fileClass = new file($fileData['id']);
              $fileClass->deleteFile();
              
          }
          
          //delete all links
          $linkClass = new link();
          $linkSQL = mysql_query("SELECT id FROM links WHERE author='$userid'");
          while($linkData = mysql_fetch_array($linkSQL)){
            $linkClass->deleteLink($linkData['userid']);
          }
          
          
          //elements
          $elementSQL = mysql_query("SELECT id FROM elements WHERE author='$userid'");
          while($elementData = mysql_fetch_array($elementSQL)){
              $element = new element($elementData['id']);
              $element->delete();
          }
          
          
          //folders
          $folderSQL = mysql_query("SELECT id FROM folders creator='$userid'");
          while($folderData = mysql_fetch_array($folderSQL)){
              $classFolder = new folder($folderData['id']);
              $classFolder->delete();
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