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
		
                $title = sanitizeText($title);
		//check if link allready exists
                $db = new db();
                $linkCheckData = $db->select('internLinks', array('type', $type, 'AND', 'typeId', $typeId), array('type'));
		if(empty($linkCheckData['type'])){
                        $values['parentType'] = $parentType;
                        $values['parentId'] = $parentId;
                        $values['type'] = $type;
                        $values['typeId'] = $typeId;
                        $values['title'] = $title;
			
			if($db->insert('internLinks', $values)){
				return true;
			}
			
		}
	}
    function delete($linkId){
        
        $db = new db();
    	//deletes single shortcut
        if($db->delete('internLinks', array('id', $linkId))){
            return true;
        }
    }
    function getData($linkId){
        $db = new db();
        return $db->select('internLinks', array('id', $linkId));
    }
    
    function deleteInternLinks($parentType, $parentId){
        $db = new db();
        //is used when element which the shortcut is linked to is deleted
        if($db->delete('internLinks', array('type', $parentType, '&&', 'typeId', $parentId))){
            return true;
        }
    }
    
    
}


        
