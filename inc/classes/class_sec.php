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