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
class shortcut {
    //put your code here
	function create($parentType, $parentId, $type, $typeId, $title=NULL){
	//creates shortcut
		
		//check if link allready exists
		$linkCheckData = mysql_fetch_array(mysql_query("SELECT type FROM internLinks WHERE type='$type' AND typeId='$typeId'"));
		if(empty($linkCheckData[type])){
			
			if(mysql_query("INSERT INTO `internLinks` (`id`, `parentType`, `parentId`, `type`, `typeId`, `title`) VALUES (NULL, '$parentType', '$parentId', '$type', '$typeId', '$title');")){
				return true;
			}
			
		}
	}
    function delete($linkId){
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
    
    
}


        
