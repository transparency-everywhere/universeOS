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
