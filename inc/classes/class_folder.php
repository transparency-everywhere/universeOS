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
        
        
function loadFolderArray($type, $folder){
        switch($type){
            
            //loads all subordinated folders of $folder
            
            case 'children':
                
                $folderSQL = mysql_query("SELECT id FROM folders WHERE folder='".mysql_real_escape_string($folder)."'");
                while($folderData = mysql_fetch_array($folderSQL)){
                    $return[] = $folderData['id'];
                }
                break;
                
            case 'path':
                
                //maximum while 
                $maxqueries = 150;
                $i = 0;
                
               	while($folder != "0" || $folder != "0"){
               		if(!empty($folder) && $i < $maxqueries){
		                $folderSQL = mysql_query("SELECT name, folder FROM folders WHERE id='$folder'");
		                $folderData = mysql_fetch_array($folderSQL);
		
		                
		                $folder = $folderData['folder'];
		                if($folder!=0){
		                $returnFolder[] = $folder;
		                $returnName[] = $folderData['name'];
		                }
		                
		                    
		                    $i++;
					}
                }
                
                $return['ids'] = $returnFolder;
                $return['names'] = $returnName;
                break;
        }
        
        return $return;
    }


function folderIdToFolderTitle($folderId){
		$folderData = getFolderData($folderId);
		return $folderData['name'];
		
	}
        
function getFolderPath($folderId){
		
            $path = "upload/";
            $folderArray = loadFolderArray("path", $folderId);
            $folderArray = array_reverse($folderArray['names'], true);
            foreach($folderArray as &$folder){
                $folder = urldecode($folder);
                $path .= "$folder/";
            }
            
            return $path;
	}
