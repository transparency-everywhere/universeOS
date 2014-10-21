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
class salt {
    //put your code here
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
  	mysql_query("DELETE FROM `salts` WHERE `type`='".save($type)."' AND `itemId`='".save($itemId)."'");
	
  	
	
  		if(mysql_query("INSERT INTO `salts` (`type`, `itemId`, `receiverType`, `receiverId`, `salt`) VALUES ('$type', '$itemId', 'user', '$itemId', '$salt')"))
			return true;
		else 
			return false;
  }

function getSalt($type, $itemId){
  	$type = save($type);
	$itemId = save($itemId);
	
  	$data = mysql_fetch_array(mysql_query("SELECT * FROM `salts` WHERE `type`='$type' AND itemId='$itemId' LIMIT 1"));
  	return $data['salt'];
  }
