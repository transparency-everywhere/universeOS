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
class folder {
    
    public $id;
    
    function __construct($id=NULL){
        if($id != NULL){
            $this->id = $id;
        }
    }
    function create($superiorFolder, $title, $user, $privacy){
		
		if(strpos($title, '/') == false){
                    $titleURL = urlencode($title);
			
                    $title = mysql_real_escape_string($title);
		    $dbClass = new db();
                    $folderData = $dbClass->select('folders', array('id', $superiorFolder));
                    $folderClass = new folder($superiorFolder);
		    $folderpath = universeBasePath.'/'.$folderClass->getPath().urldecode("$titleURL");
			if (!file_exists("$folderpath")) {
		    mkdir($folderpath);
		    $time = time();
                    
                    $values['folder'] = $superiorFolder;
                    $values['name'] = $title;
                    $values['path'] = $folderpath;
                    $values['creator'] = $user;
                    $values['timestamp'] = $time;
                    $values['privacy'] = $privacy;
                    $db = new db();
		    $folderId = $db->insert('folders', $values);
		    $feed = "has created a folder";
                    
                    $feedClass = new feed();
		    $feedClass->create($user, $feed, "", "showThumb", $privacy, "folder", $folderId);
		    //return true;
		    
		    return $folderId;
			}else{
				jsAlert("The folder already exists.");
			}
		}else{
			jsAlert("The title contains forbidden characters.");
		}
    }
    function select(){
        $db = new db();
        return $db->select('folders', array('id', $this->id));
    }
    function update($parent_folder, $title, $privacy){
        
        $db = new db();
        $checkFolderData = $db->select('folders', array('id', $this->id));
        
            $folderClass = new folder($checkFolderData['folder']);
            $parentFolderPath = universeBasePath.'/'.$folderClass->getPath();
					
            //check if folder exists
            if (!file_exists($parentFolderPath.urldecode($title))) {
		//rename folder
	        if(rename($parentFolderPath.$checkFolderData['name'], $parentFolderPath.urldecode(save($title)))){
	            	//update db
                        $values['name'] = $title;
                }
            }else{
                $values['privacy'] = $privacy;
                $db->update('folders', $values, array('id', $this->id));
            }
        
            $values['privacy'] = $privacy;

            $db->update('folders', $values, array('id', $this->id));
            return true;
    }
    
    
    function loadFolderArray($type){
        $folder = $this->id;
            $db = new db();
            switch($type){

                //loads all subordinated folders of $folder

                case 'children':
                    $folders = $db->shiftResult($db->select('folders', array('folder', $folder), array('id')), 'id');
                    foreach($folders AS $folderData){
                        $return[] = $folderData['id'];
                    }
                    break;

                case 'path':

                    //maximum while 
                    $maxqueries = 150;
                    $i = 0;

                    while(($folder != "0" || $folder != "0") && $i < $maxqueries){
                            if(!empty($folder)){
                                    $folderData = $db->select('folders', array('id', $folder), array('name', 'folder'));


                                    $folder = $folderData['folder'];
                                    if($folder!=0){
                                        $returnFolder[] = $folder;
                                        $returnName[] = $folderData['name'];
                                    }


                                            }
                                        $i++;
                    }

                    $return['ids'] = $returnFolder;
                    $return['names'] = $returnName;
                    break;
            }

            return $return;
    }	
    function getFolderData(){
        $folderId = $this->id;
        $dbClass = new db();
        $data = $dbClass->select('folders', array('id', save($folderId)));
        return $data;
    }
    function getTitle(){
            $folderId = $this->id;
		$folderData = $this->getFolderData();
		return $folderData['name'];
		
	}
    function getPath($showRealPath=true){
            $folderId = $this->id;
            if($showRealPath)
                $path = "upload/";
            else
                $path = '';
            $folderArray = $this->loadFolderArray("path");
            $folderArray = array_reverse($folderArray['names'], true);
            foreach($folderArray as &$folder){
                $folder = urldecode($folder);
                $path .= "$folder/";
            }
            
            return $path;
	} 
    function delete(){
        $folderId = $this->id;
        $dbClass = new db();
        $folderData = $dbClass->select('folders', array('id', $folderId));


        //select and delete folders which are children of this folder
        $folders = $dbClass->shiftResult($dbClass->select('folders', array('folder', $folderId), array('id')), 'id');
        foreach($folders AS $childrenFolderData){
            $folder = new folder($childrenFolderData['id']);
            $folder->delete();
        }
        //select and delete element which are children of this folder
        $childElements = $dbClass->shiftResult($dbClass->select('elements', array('folder', $folderId), array('id')),'id');
        foreach($childElements AS $childrenElementData){
            $element = new element($childrenElementData['id']);
            $element->delete();
        }
        $folderClass = new folder($folderId);
        $folderpath = universeBasePath.'/'.$folderClass->getPath();
        $dbClass->delete('folders', array('id', $folderId));
        system('/bin/rm -rf ' . escapeshellarg($folderpath));

        //delete comments, feeds and shortcuts
        $commentClass = new comments();
        $commentClass->deleteComments("folder", $folderId);

        $classFeed = new feed();
        $classFeed->deleteFeeds("folder", $folderId);

        $shortcutClass = new shortcut();
        $shortcutClass->deleteInternLinks("folder", $folderId);

        jsAlert("The folder has been deleted.");
        return true;
        
    }
}
