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
class personalEvents{
		
		function create($owner,$user,$event,$info,$eventId){
			
	         mysql_query("INSERT INTO personalEvents (`owner`,`user`,`event`,`info`,`eventId`,`timestamp`) VALUES('$owner','$user', '$event','$info','$eventId', '".time()."');");
	        
		}
	}