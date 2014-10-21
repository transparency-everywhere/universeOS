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

            createFeed($_SESSION['userid'], "is logged in", "60", "feed", "p");
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
