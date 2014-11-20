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
		    mysql_query("INSERT INTO `folders` (`folder`, `name`, `path`, `creator`, `timestamp`, `privacy`) VALUES ( '$superiorFolder', '$title', '$folderpath', '$user', '$time', '$privacy');");
		    $folderId = mysql_insert_id();
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
    function loadFolderArray($type){
        $folder = $this->id;
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
        $childrenFolderSQL = mysql_query("SELECT id FROM folders WHERE folder='$folderId'");
        while($childrenFolderData = mysql_fetch_array($childrenFolderSQL)){
            $folder = new folder($childrenFolderData['id']);
            $folder->delete();
        }
        //select and delete element which are children of this folder
        $childrenElementSQL = mysql_query("SELECT id FROM elements WHERE folder='$folderId'");
        while($childrenElementData = mysql_fetch_array($childrenElementSQL)){
            $element = new element($childrenElementData['id']);
            $element->delete();
        }
        $folderClass = new folder($folderId);
        $folderpath = universeBasePath.'/'.$folderClass->getPath();
        mysql_query("DELETE FROM folders WHERE id='$folderId'");
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
