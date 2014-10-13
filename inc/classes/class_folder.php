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
class newPHPClass {
    //put your code here
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
    
    
	
function getFolderData($folderId){
		$query = mysql_query("SELECT * FROM `folders` WHERE id='".save($folderId)."'");
		$data = mysql_fetch_array($query);
		return $data;
	}
